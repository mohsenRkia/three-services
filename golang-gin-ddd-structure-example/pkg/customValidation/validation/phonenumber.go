package validation

import (
	"github.com/go-playground/validator/v10"
	"regexp"
)

func PhoneNumber(fl validator.FieldLevel) bool {
	phoneNumber := fl.Field().String()

	var phoneRegex = "^09(1[0-9]|3[0-9]|2[1-9]|9[1-9]|0[1-9]|4[1-9])-?[0-9]{3}-?[0-9]{4}$"

	match, _ := regexp.MatchString(phoneRegex, phoneNumber)

	return match
}
