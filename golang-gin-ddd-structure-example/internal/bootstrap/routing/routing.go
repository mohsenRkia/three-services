package routing

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"log"
	"myGolangFramework/internal/infrastructure/config"
)

var GlobalRouterEngine *gin.Engine

func init() {
	GlobalRouterEngine = gin.Default()
}

func GetRouter() *gin.Engine {
	return GlobalRouterEngine
}

func RegisterRoutes() {
	group := GlobalRouterEngine.Group("api")
	registerRoutes(group)
	fmt.Println("Custom Route Registered ...")
}

func Serve() {
	RegisterRoutes()
	router := GetRouter()
	viper := config.NewEnvironment()
	err := router.Run(fmt.Sprintf("%s:%s", viper.GetString("SERVER_HOST"), viper.GetString("SERVER_PORT")))
	if err != nil {
		log.Fatal("Error while routing")
		return
	}
}
