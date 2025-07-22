package validation

import (
	"bytes"
	"fmt"
	"myGolangFramework/internal/infrastructure/config/db"
	"reflect"
	"strings"
	"unicode"

	"github.com/go-playground/validator/v10"
)

func Unique(fl validator.FieldLevel) bool {
	tx := db.Connection()
	params := strings.Split(fl.Param(), ":")
	value := typedValue(fl)

	table, field := getParams(params, pascalToSnake(fl.FieldName()))
	var count int64 = 0
	tx.Table(table).Where(fmt.Sprintf("%s = ?", field), value).Count(&count)

	return count == 0
}

func typedValue(fl validator.FieldLevel) interface{} {
	var value interface{}
	switch fl.Field().Kind() {
	case reflect.String:
		value = fl.Field().String()
	case reflect.Bool:
		value = fl.Field().Bool()
	case reflect.Uint, reflect.Uint8, reflect.Uint16, reflect.Uint32, reflect.Uint64:
		value = fl.Field().Uint()
	case reflect.Int, reflect.Int8, reflect.Int16, reflect.Int32, reflect.Int64:
		value = fl.Field().Int()
	}
	return value
}

func getParams(params []string, fieldName string) (string, string) {
	if len(params) == 1 {
		return params[0], fieldName
	}
	return params[0], params[1]
}

func pascalToSnake(s string) string {
	var result bytes.Buffer

	for i, r := range s {
		if unicode.IsUpper(r) {
			if i > 0 {
				result.WriteRune('_')
			}
			result.WriteRune(unicode.ToLower(r))
		} else {
			result.WriteRune(r)
		}
	}

	return result.String()
}
