package domain

import (
	"myGolangFramework/internal/domain/user"
	"myGolangFramework/internal/infrastructure/pagination"
)

type UserRepository interface {
	FindByID(id string) (*user.User, error)
	Store(user *user.User) (*user.User, error)
	Update(user *user.User) error
	Delete(id string) error
	List(p *pagination.Pagination) ([]*user.User, error)
}

type TXRepositoryProviderInterface interface {
	User() UserRepository
}
