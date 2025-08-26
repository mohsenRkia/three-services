package bootstrap

import (
	"myGolangFramework/internal/bootstrap/config/db"
	"myGolangFramework/internal/bootstrap/routing"
	"myGolangFramework/internal/infrastructure/validations"
)

func Boot() {
	db.Connect()
	validations.Init()
	routing.Serve()
}
