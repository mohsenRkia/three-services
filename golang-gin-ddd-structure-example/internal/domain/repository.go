package domain

import (
	"myGolangFramework/internal/domain/user"
	"myGolangFramework/internal/infrastructure/pagination"
	"myGolangFramework/internal/infrastructure/persistence/models"
)

type UserRepository interface {
	FindByID(id string) (*user.User, error)
	Create(user *user.User) error
	Update(user *user.User) error
	Delete(id string) error
	List(p *pagination.Pagination) ([]*models.User, error)
}

type TXRepositoryProviderInterface interface {
	User() UserRepository
}
