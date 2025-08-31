package user

import (
	"gorm.io/gorm"
	"myGolangFramework/internal/domain"
	"myGolangFramework/internal/domain/user"
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

func (r *UserRepository) List() ([]*user.User, error) {
	var usersModel []models.User
	if err := r.db.Find(&usersModel).Error; err != nil {
		return nil, err
	}

	var users []*user.User
	for _, m := range usersModel {
		users = append(users, models.ToEntity(&m))
	}
	return users, nil
}
