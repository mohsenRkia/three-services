package request

type CreateUserRequestDTO struct {
	Email    string `json:"email" validate:"required,email"`
	Phone    string `json:"phone" validate:"required,min=5"`
	Password string `json:"password" validate:"required,min=8"`
}
