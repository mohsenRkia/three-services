package user

import (
	"github.com/gin-gonic/gin"
	"myGolangFramework/internal/application/user"
	"myGolangFramework/internal/application/user/dto/request"
	"myGolangFramework/internal/delivery/http/shared/enums"
	"myGolangFramework/internal/delivery/http/shared/response"
	"myGolangFramework/internal/infrastructure/validations"
	"net/http"
)

type Handler struct {
	service *user.Service
}

func NewHandler() *Handler {
	return &Handler{service: user.NewService()}
}

func (h *Handler) CreateUser(ctx *gin.Context) {
	req, vErr := validations.ValidatePayload[request.CreateUserRequestDTO](ctx)
	if vErr != nil {
		response.HandleHTTPErrors(ctx, vErr, enums.FailedToParseBodyErrorMsg)
		return
	}

	if err := h.service.CreateUser(req.Email, req.Password); err != nil {
		response.HandleHTTPErrors(ctx, response.NewHTTPError(
			http.StatusInternalServerError,
			err.Error(),
		), enums.FailedToParseBodyErrorMsg)
		return
	}

	ctx.Status(http.StatusCreated)
	return
}

func (h *Handler) GetUser(ctx *gin.Context) {
	id := ctx.Param("id")

	getUser, err := h.service.GetUser(id)
	if err != nil {
		ctx.JSON(http.StatusNotFound, gin.H{"error": "not found"})
		return
	}

	ctx.JSON(http.StatusOK, getUser)
}

func (h *Handler) List(ctx *gin.Context) {
	req, vErr := validations.ValidateQueries[request.ListUserRequestDTO](ctx)
	if vErr != nil {
		response.HandleHTTPErrors(ctx, vErr, enums.FailedToParseBodyErrorMsg)
		return
	}

	data, err := h.service.List(req.Page, req.Limit)
	if err != nil {
		response.HandleHTTPErrors(ctx, response.NewHTTPError(
			http.StatusInternalServerError,
			err.Error(),
		), enums.InternalServerErrorErrorMsg)
		return
	}

	ctx.JSON(http.StatusOK, data)
	return
}
