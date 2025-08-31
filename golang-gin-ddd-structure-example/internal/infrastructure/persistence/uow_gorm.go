package persistence

import (
	"gorm.io/gorm"
	"myGolangFramework/internal/domain"
	"myGolangFramework/internal/infrastructure/persistence/user"
)

type UowGorm struct {
	db *gorm.DB
}

func NewUowGorm(db *gorm.DB) *UowGorm {
	return &UowGorm{db: db}
}

func (u *UowGorm) Do(fn func(repository domain.RepositoryProvider) error) error {
	return u.db.Transaction(func(tx *gorm.DB) error {
		repoProvider := &gormRepositoryProvider{
			tx: tx,
		}
		return fn(repoProvider)
	})
}

// ///////////////
type gormRepositoryProvider struct {
	tx       *gorm.DB
	userRepo domain.UserRepository
}

func (p *gormRepositoryProvider) User() domain.UserRepository {
	if p.userRepo == nil {
		p.userRepo = user.NewUserRepository(p.tx)
	}
	return p.userRepo
}
