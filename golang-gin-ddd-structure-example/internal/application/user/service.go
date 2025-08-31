package user

import (
	"myGolangFramework/internal/application"
	"myGolangFramework/internal/bootstrap/config/db"
	"myGolangFramework/internal/domain"
	"myGolangFramework/internal/domain/user"
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
