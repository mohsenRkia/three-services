package user

import (
	"myGolangFramework/internal/application"
	sharedDto "myGolangFramework/internal/application/shared/dto"
	"myGolangFramework/internal/application/user/dto/response"
	"myGolangFramework/internal/bootstrap/config/db"
	"myGolangFramework/internal/domain"
	"myGolangFramework/internal/domain/user"
	"myGolangFramework/internal/infrastructure/pagination"
	userRepo "myGolangFramework/internal/infrastructure/persistence/user"
)

type Service struct {
	uow  application.UnitOfWork
	repo domain.UserRepository
}

func NewService() *Service {
	return &Service{
		uow:  db.UoWInstance(),
		repo: userRepo.NewUserRepository(db.Connection()),
	}
}

func (s *Service) List(page, limit int) (sharedDto.PaginationData[response.ListUserResponseDTO], error) {
	p := pagination.New(page, limit)
	users, err := s.repo.List(p)
	if err != nil {
		return response.ListUserDTOResponses(users, p), err
	}
	return response.ListUserDTOResponses(users, p), nil
}

// return &Service{repo: userRepo.NewUserRepository()}
func (s *Service) GetUser(id string) (*user.User, error) {
	return s.repo.FindByID(id)
}

func (s *Service) CreateUser(email, password string) error {
	u, err := user.NewUser(email, password)
	if err != nil {
		return err
	}

	return s.uow.Do(func(r domain.TXRepositoryProviderInterface) error {
		if err := r.User().Create(u); err != nil {
			return err
		}
		///other creation for transactions
		return nil
	})
}
