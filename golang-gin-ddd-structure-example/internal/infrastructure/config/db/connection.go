package db

import (
	"gorm.io/gorm"
	"log"
	"myGolangFramework/internal/infrastructure/config"
	"myGolangFramework/internal/infrastructure/config/db/mysql"
	"os"
)

var DB *gorm.DB

func Connect() *gorm.DB {
	viper := config.NewEnvironment()
	driver := viper.GetString("DB_DRIVER")

	switch driver {
	case "mysql":
		//return &Database{Connection: mysql.Connect(viper)}
		DB, _ = mysql.Connect(viper)
	case "postgres":
		log.Fatalf("DB_DRIVER '%s' is not supported", driver)
		os.Exit(1)
	default:
		log.Fatalf("DB_DRIVER '%s' is not supported", driver)
		os.Exit(1)

	}

	if DB == nil {
		os.Exit(1)
	}
	return DB
}

func Connection() *gorm.DB {
	return DB
}
