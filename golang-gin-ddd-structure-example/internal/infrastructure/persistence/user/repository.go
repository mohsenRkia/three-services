package user

import (
	"gorm.io/gorm"
	"myGolangFramework/internal/domain"
	"myGolangFramework/internal/domain/user"
	"myGolangFramework/internal/infrastructure/pagination"
	"myGolangFramework/internal/infrastructure/persistence/models"
)

type UserRepository struct {
	db *gorm.DB
}

func NewUserRepository(gdb *gorm.DB) domain.UserRepository {
	return &UserRepository{db: gdb}
}

func (r *UserRepository) FindByID(id string) (*user.User, error) {
	var m models.User
	if err := r.db.First(&m, "id = ?", id).Error; err != nil {
		return nil, err
	}
	return models.ToEntity(&m), nil
}

func (r *UserRepository) Create(u *user.User) error {
	return r.db.Create(models.ToModel(u)).Error
}

func (r *UserRepository) Update(u *user.User) error {
	return r.db.Save(models.ToModel(u)).Error
}

func (r *UserRepository) Delete(id string) error {
	return r.db.Delete(&models.User{}, "id = ?", id).Error
}

func (r *UserRepository) List(p *pagination.Pagination) ([]*models.User, error) {
	var usersModel []*models.User

	if err := p.WithTotal(r.db, &models.User{}); err != nil {
		return nil, err
	}

	if err := r.db.Scopes(p.Scope()).Find(&usersModel).Error; err != nil {
		return nil, err
	}

	return usersModel, nil
}

//if err := r.db.Scopes(p.Scope()).Find(&usersModel).Error; err != nil {
//return nil, err
//}
