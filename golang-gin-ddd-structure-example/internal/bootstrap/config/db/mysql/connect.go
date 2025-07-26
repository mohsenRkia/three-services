package mysql

import (
	"fmt"
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
	"myGolangFramework/internal/bootstrap/config"
)

func Connect(viper *config.Environment) (*gorm.DB, error) {
	host := viper.GetString("DB_HOST")
	port := viper.GetString("DB_PORT")
	dbName := viper.GetString("DB_NAME")
	user := viper.GetString("DB_USER")
	pass := viper.GetString("DB_PASSWORD")

	// "user:pass@tcp(127.0.0.1:3306)/dbname?charset=utf8mb4&parseTime=True&loc=Local"
	dsn := fmt.Sprintf("%s:%s@tcp(%s:%s)/%s?charset=utf8mb4&parseTime=True&loc=Local",
		user, pass, host, port, dbName)

	db, err := gorm.Open(mysql.Open(dsn), &gorm.Config{})
	if err != nil {
		fmt.Println(err.Error())
		return nil, err
	}

	fmt.Printf("Connected to mysql db %s:%s", host, port)
	fmt.Println("...")
	return db, nil
}
