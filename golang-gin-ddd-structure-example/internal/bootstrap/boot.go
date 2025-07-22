package bootstrap

import (
	"myGolangFramework/internal/bootstrap/routing"
	"myGolangFramework/internal/infrastructure/config/db"
	"myGolangFramework/pkg/customValidation"
)

func Boot() {
	db.Connect()
	customValidation.Init()
	routing.Serve()
}
