package user

import (
	"errors"
	"time"
)

type User struct {
	ID        uint
	Email     string
	Password  string
	CreatedAt time.Time
}

func NewUser(email, password string) (*User, error) {
	if email == "" {
		return nil, errors.New("email is required")
	}
	if password == "" {
		return nil, errors.New("password is required")
	}
	return &User{
		Email:     email,
		Password:  password,
		CreatedAt: time.Now(),
	}, nil
}
