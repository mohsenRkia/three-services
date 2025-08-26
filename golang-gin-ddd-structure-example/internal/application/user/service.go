package user

import (
	"myGolangFramework/internal/domain/user"
	userRepo "myGolangFramework/internal/infrastructure/persistence/user"
)

type Service struct {
	repo user.Repository
}

func NewService() *Service {
	return &Service{repo: userRepo.NewUserRepository()}
}

func (s *Service) GetUser(id string) (*user.User, error) {
	return s.repo.FindByID(id)
}

func (s *Service) CreateUser(email, password string) error {
	u, err := user.NewUser(email, password)
	if err != nil {
		return err
	}
	return s.repo.Create(u)
}
