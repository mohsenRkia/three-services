package models

import (
	"gorm.io/gorm"
	"myGolangFramework/internal/domain/user"
	"time"
)

type User struct {
	gorm.Model
	Email     string `gorm:"uniqueIndex;size:128"`
	Phone     string `gorm:"uniqueIndex;size:128"`
	Password  string
	CreatedAt time.Time
}

func ToModel(u *user.User) *User {
	return &User{
		Email:     u.Email,
		Phone:     u.Phone,
		Password:  u.Password,
		CreatedAt: u.CreatedAt,
	}
}

func ToEntity(m *User) *user.User {
	return &user.User{
		ID:        m.ID,
		Email:     m.Email,
		Phone:     m.Phone,
		Password:  m.Password,
		CreatedAt: m.CreatedAt,
	}
}
