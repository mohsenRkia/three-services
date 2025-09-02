package response

import (
	sharedDto "myGolangFramework/internal/application/shared/dto"
	"myGolangFramework/internal/infrastructure/pagination"
	"myGolangFramework/internal/infrastructure/persistence/models"
)

type ListUserResponseDTO struct {
	ID    uint   `json:"id"`
	Email string `json:"email"`
}

func toResponse(data *models.User) ListUserResponseDTO {
	return ListUserResponseDTO{
		ID:    data.ID,
		Email: data.Email,
	}
}

func ListUserDTOResponses(usersModel []*models.User, p *pagination.Pagination) sharedDto.PaginationData[ListUserResponseDTO] {
	var list []ListUserResponseDTO
	for _, m := range usersModel {
		list = append(list, toResponse(m))
	}

	data := sharedDto.NewPaginationData[ListUserResponseDTO](list, p)

	return data
}
