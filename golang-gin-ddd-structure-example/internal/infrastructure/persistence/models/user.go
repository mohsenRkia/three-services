package models

import (
	"myGolangFramework/internal/domain/user"
	"time"
)

type User struct {
	ID        string `gorm:"primaryKey"`
	Email     string `gorm:"uniqueIndex"`
	Password  string
	CreatedAt time.Time
}

func ToModel(u *user.User) *User {
	return &User{
		ID:        u.ID,
		Email:     u.Email,
		Password:  u.Password,
		CreatedAt: u.CreatedAt,
	}
}

func ToEntity(m *User) *user.User {
	return &user.User{
		ID:        m.ID,
		Email:     m.Email,
		Password:  m.Password,
		CreatedAt: m.CreatedAt,
	}
}
