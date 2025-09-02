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

func (s *Service) List(page, limit int) (*sharedDto.PaginationData[response.UserResponseDTO], error) {
	p := pagination.New(page, limit)
	users, err := s.repo.List(p)
	if err != nil {
		return response.ToResponsesUserDTO(nil, p), err
	}
	return response.ToResponsesUserDTO(users, p), nil
}

func (s *Service) GetUser(id string) (*response.UserResponseDTO, error) {
	data, err := s.repo.FindByID(id)
	if err != nil {
		return nil, err
	}
	return response.ToResponseUserDTO(data), nil
}

func (s *Service) CreateUser(email, password string) (*response.UserResponseDTO, error) {
	u, err := user.NewUser(email, password)
	if err != nil {
		return nil, err
	}

	var us *user.User

	storeErr := s.uow.Do(func(r domain.TXRepositoryProviderInterface) error {
		us, err = r.User().Store(u)
		if err != nil {
			return err
		}
		///other creation for transactions
		return nil
	})

	if storeErr != nil {
		return nil, err
	}

	return response.ToResponseUserDTO(us), nil
}
