package migration

import (
	"myGolangFramework/internal/infrastructure/config/db"
)

func Migrator() {
	db.Connect()
	Migrations()
}
