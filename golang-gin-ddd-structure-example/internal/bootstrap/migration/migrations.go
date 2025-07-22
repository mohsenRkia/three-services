package migration

import (
	"fmt"
	"log"
	db2 "myGolangFramework/internal/infrastructure/config/db"
	"myGolangFramework/internal/infrastructure/persistence/models"
)

func Migrations() {
	db := db2.Connection()
	err := db.AutoMigrate(
		//list models here ex: &model.User{},
		&models.User{},
	)

	if err != nil {
		log.Fatal("Can Not Migrate Models")
		return
	}

	fmt.Print("Migration Done...")
}
