package user

import (
	"github.com/gin-gonic/gin"
	"myGolangFramework/internal/application/user"
	"myGolangFramework/internal/application/user/dto"
	helpers "myGolangFramework/internal/delivery/http/shared/response"
	"myGolangFramework/internal/infrastructure/validations"
	"net/http"
)

type Handler struct {
	service *user.Service
}

func NewHandler() *Handler {
	return &Handler{service: user.NewService()}
}

func (h *Handler) CreateUser(c *gin.Context) {
	req, vErr := validations.ValidatePayload[dto.CreateUserDTO](c)
	if vErr != nil {
		helpers.HandleHTTPErrors(c, vErr, validations.FailedToParseBodyErrorMsg)
		return
	}

	if err := h.service.CreateUser(req.Email, req.Password); err != nil {
		helpers.HandleHTTPErrors(c, helpers.NewHTTPError(
			http.StatusInternalServerError,
			err.Error(),
		), validations.FailedToParseBodyErrorMsg)
		return
	}

	c.Status(http.StatusCreated)
	return
}

func (h *Handler) GetUser(c *gin.Context) {
	id := c.Param("id")

	getUser, err := h.service.GetUser(id)
	if err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "not found"})
		return
	}

	c.JSON(http.StatusOK, getUser)
}
