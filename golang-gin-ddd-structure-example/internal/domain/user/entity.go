package user

import (
	"errors"
	"time"
)

type User struct {
	ID        uint
	Email     string
	Phone     string
	Password  string
	CreatedAt time.Time
}

func NewUser(email, password, phone string) (*User, error) {
	if email == "" {
		return nil, errors.New("email is required")
	}
	if phone == "" {
		return nil, errors.New("phone is required")
	}
	if password == "" {
		return nil, errors.New("password is required")
	}
	return &User{
		Email:     email,
		Phone:     phone,
		Password:  password,
		CreatedAt: time.Now(),
	}, nil
}
