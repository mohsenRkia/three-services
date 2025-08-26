package rules

import (
	"github.com/go-playground/validator/v10"
	"strings"
)

func IN(fl validator.FieldLevel) bool {
	value := fl.Field().String()
	allowedValues := strings.Split(fl.Param(), ";")

	for _, v := range allowedValues {
		if value == v {
			return true
		}
	}

	return false
}
