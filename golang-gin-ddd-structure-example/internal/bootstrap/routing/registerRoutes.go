package routing

import (
	"github.com/gin-gonic/gin"
	"myGolangFramework/internal/delivery/http/gateway"
	"myGolangFramework/internal/delivery/http/middleware"
	"myGolangFramework/internal/delivery/http/user"
)

func registerRoutes(group *gin.RouterGroup) {
	group.Use(middleware.CORSMiddleware())

	user.Routes(group)
	gateway.Routes(group)
}
