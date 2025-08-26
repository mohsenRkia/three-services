package user

import (
	"github.com/gin-gonic/gin"
)

func Routes(router *gin.RouterGroup) {
	handler := NewHandler()
	router.POST("/users", handler.CreateUser)
	router.GET("/users/:id", handler.GetUser)
}
