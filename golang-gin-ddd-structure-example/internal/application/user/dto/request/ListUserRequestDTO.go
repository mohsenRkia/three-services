package request

type ListUserRequestDTO struct {
	Page  int `form:"page" validate:""`
	Limit int `form:"limit" validate:""`
}
