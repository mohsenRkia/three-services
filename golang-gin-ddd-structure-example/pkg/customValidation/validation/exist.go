package validation

import (
	"myGolangFramework/internal/infrastructure/config/db"
	"strings"

	"github.com/go-playground/validator/v10"
)

func Exists(fl validator.FieldLevel) bool {

	additionalParams := strings.Split(fl.Param(), ":")
	if len(additionalParams) != 2 {
		// Handle invalid parameters
		return false
	}
	table := additionalParams[0]
	fieldName := additionalParams[1]

	value, _ := fl.Field().Interface().(uint)
	var count int64 = 0
	if value == 0 {
		return true
	}
	db := db.Connection()
	db.Table(table).Where(fieldName+" = ?", value).Count(&count)
	return count > 0

}
