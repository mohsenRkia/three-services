package migration

import (
	"myGolangFramework/internal/bootstrap/config/db"
)

func Migrator() {
	db.Connect()
	Migrations()
}
