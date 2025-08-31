package domain

import "myGolangFramework/internal/domain/user"

type UserRepository interface {
	FindByID(id string) (*user.User, error)
	Create(user *user.User) error
	Update(user *user.User) error
	Delete(id string) error
	List() ([]*user.User, error)
}

type TXRepositoryProviderInterface interface {
	User() UserRepository
}
