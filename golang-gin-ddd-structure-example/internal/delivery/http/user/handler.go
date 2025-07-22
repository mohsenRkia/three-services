package user

import (
	"github.com/gin-gonic/gin"
	"myGolangFramework/internal/application/user"
	"net/http"
)

type Handler struct {
	service *user.Service
}

func NewHandler() *Handler {
	return &Handler{service: user.NewService()}
}

func (h *Handler) CreateUser(c *gin.Context) {
	var req struct {
		ID       string `json:"id"`
		Email    string `json:"email"`
		Password string `json:"password"`
	}
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "bad request"})
		return
	}

	if err := h.service.CreateUser(req.ID, req.Email, req.Password); err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}

	c.Status(http.StatusCreated)
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
