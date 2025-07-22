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

func (s *Service) CreateUser(id, email, password string) error {
	u := user.NewUser(id, email, password)
	return s.repo.Create(u)
}
