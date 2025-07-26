package bootstrap

import (
	"myGolangFramework/internal/bootstrap/config/db"
	"myGolangFramework/internal/bootstrap/routing"
	"myGolangFramework/pkg/customValidation"
)

func Boot() {
	db.Connect()
	customValidation.Init()
	routing.Serve()
}
