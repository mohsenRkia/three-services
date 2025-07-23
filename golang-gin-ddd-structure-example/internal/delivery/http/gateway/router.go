package gateway

import "github.com/gin-gonic/gin"

func Routes(router *gin.RouterGroup) {
	handler := NewHandler()

	router.GET("/gateway", handler.Test)
}
