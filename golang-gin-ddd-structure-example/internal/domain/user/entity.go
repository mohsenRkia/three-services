package user

import "time"

type User struct {
	ID        string
	Email     string
	Password  string
	CreatedAt time.Time
}

func NewUser(id, email, password string) *User {
	return &User{
		ID:        id,
		Email:     email,
		Password:  password,
		CreatedAt: time.Now(),
	}
}
