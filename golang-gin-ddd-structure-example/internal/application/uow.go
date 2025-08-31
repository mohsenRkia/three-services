package application

import (
	"myGolangFramework/internal/domain"
)

type UnitOfWork interface {
	Do(fn func(r domain.TXRepositoryProviderInterface) error) error
}
