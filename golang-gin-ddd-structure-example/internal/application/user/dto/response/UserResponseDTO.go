package response

import (
	sharedDto "myGolangFramework/internal/application/shared/dto"
	"myGolangFramework/internal/domain/user"
	"myGolangFramework/internal/infrastructure/pagination"
	"myGolangFramework/pkg/mapper"
)

type UserResponseDTO struct {
	ID    uint   `json:"id"`
	Email string `json:"email"`
	Phone string `json:"phone"`
}

func ToResponseUserDTO(data *user.User) *UserResponseDTO {
	if data == nil {
		return nil
	}
	return &UserResponseDTO{
		ID:    data.ID,
		Email: data.Email,
		Phone: data.Phone,
	}
}

func ToResponsesUserDTO(users []*user.User, p *pagination.Pagination) *sharedDto.PaginationData[UserResponseDTO] {
	list := mapper.Map(users, func(u *user.User) *UserResponseDTO {
		return ToResponseUserDTO(u)
	})
	return sharedDto.NewPaginationData(list, p)
}
