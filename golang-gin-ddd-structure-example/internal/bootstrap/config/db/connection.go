package db

import (
	"gorm.io/gorm"
	"log"
	"myGolangFramework/internal/application"
	"myGolangFramework/internal/bootstrap/config"
	"myGolangFramework/internal/bootstrap/config/db/mysql"
	"myGolangFramework/internal/infrastructure/persistence"
	"os"
)

var (
	DB  *gorm.DB
	UoW application.UnitOfWork
)

func Connect() *gorm.DB {
	viper := config.NewEnvironment()
	driver := viper.GetString("DB_DRIVER")
	switch driver {
	case "mysql":
		DB, _ = mysql.Connect(viper)
		UoW = persistence.NewUowGorm(DB)
	case "postgres":
		log.Fatalf("DB_DRIVER '%s' is not supported", driver)
		os.Exit(1)
	default:
		DB, _ = mysql.Connect(viper)
		UoW = persistence.NewUowGorm(DB)
	}

	if DB == nil {
		os.Exit(1)
	}

	return DB
}

func Connection() *gorm.DB {
	return DB
}
func UoWInstance() application.UnitOfWork {
	return UoW
}
