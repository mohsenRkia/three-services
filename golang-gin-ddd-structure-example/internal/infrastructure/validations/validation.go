package validations

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"github.com/go-playground/validator/v10"
	"myGolangFramework/internal/delivery/http/shared/enums"
	"myGolangFramework/internal/delivery/http/shared/response"
	"myGolangFramework/internal/infrastructure/validations/rules"
	"net/http"
)

var validatorInstance = validator.New()

type ValidationErrorResponse struct {
	FailedField string
	Tag         string
	Param       string
	Value       interface{}
}

func ValidatePayload[T any](ctx *gin.Context) (*T, *response.HTTPError) {
	var body T

	if err := ctx.ShouldBindJSON(&body); err != nil {
		return nil,
			response.NewHTTPError(http.StatusBadRequest, enums.FailedToParseBodyErrorMsg)
	}

	if errs := validate(body); len(errs) > 0 {
		return nil,
			response.NewHTTPError(http.StatusBadRequest, getValidationErrors(errs)...)
	}

	return &body, nil
}

func ValidateQueries[T any](ctx *gin.Context) (*T, *response.HTTPError) {
	var query T

	if err := ctx.ShouldBindQuery(&query); err != nil {
		return nil,
			response.NewHTTPError(http.StatusBadRequest, enums.FailedToParseQueryErrorMsg)
	}

	if errs := validate(query); len(errs) > 0 {
		return nil,
			response.NewHTTPError(http.StatusBadRequest, getValidationErrors(errs)...)
	}

	return &query, nil
}

func validate(data interface{}) []ValidationErrorResponse {
	validationErrors := []ValidationErrorResponse{}

	errs := validatorInstance.Struct(data)
	if errs != nil {
		for _, err := range errs.(validator.ValidationErrors) {
			// In this case data object is actually holding the User struct
			var elem ValidationErrorResponse

			elem.FailedField = err.Field() // Export struct field name
			elem.Tag = err.Tag()           // Export struct tag
			elem.Value = err.Value()       // Export field value
			elem.Param = err.Param()       // Export field parameter

			validationErrors = append(validationErrors, elem)
		}
	}

	return validationErrors
}

func getValidationErrors(errors []ValidationErrorResponse) []string {
	var errorsSlice []string

	for _, err := range errors {
		// Customize error messages based on the field and tag
		switch err.Tag {
		case "required":
			errorsSlice = append(errorsSlice, fmt.Sprintf("%s is required.",
				err.FailedField))
		case "email":
			errorsSlice = append(errorsSlice, fmt.Sprintf("%s must be a valid email address.",
				err.FailedField))
		case "gte":
			errorsSlice = append(errorsSlice, fmt.Sprintf("%s must be greater or equal to %s.",
				err.FailedField, err.Param))
		case "lte":
			errorsSlice = append(errorsSlice, fmt.Sprintf("%s must be less than or equal to %s.",
				err.FailedField, err.Param))
		case "min":
			errorsSlice = append(errorsSlice, fmt.Sprintf("%s must be at least %s.",
				err.FailedField, err.Param))
		case "max":
			errorsSlice = append(errorsSlice, fmt.Sprintf("%s must be at most %s.",
				err.FailedField, err.Param))
		case "len":
			errorsSlice = append(errorsSlice, fmt.Sprintf("%s must have exactly %s characters.",
				err.FailedField, err.Param))
		case "dateformat":
			errorsSlice = append(errorsSlice, fmt.Sprintf("%s must be in a valid format.", err.FailedField))
		case "url":
			errorsSlice = append(errorsSlice, fmt.Sprintf("%s must be in a valid format.", err.FailedField))
		case "apiMethod":
			errorsSlice = append(errorsSlice, fmt.Sprintf("%s must be a valid API method.", err.FailedField))
		case "phone":
			errorsSlice = append(errorsSlice, fmt.Sprintf("%s must be in a phone number format.", err.FailedField))
		case "boolean":
			errorsSlice = append(errorsSlice, fmt.Sprintf("%s must be in true or false.", err.FailedField))
		default:
			// Use the default error message
			errorsSlice = append(errorsSlice, fmt.Sprintf("Validation failed for field %s with tag %s.",
				err.FailedField, err.Tag))
		}
	}

	return errorsSlice
}

// me
func Init() {
	//v := validator.New()
	existsErr := validatorInstance.RegisterValidation("exists", rules.Exists)
	if existsErr != nil {
		return
	}
	uniqueErr := validatorInstance.RegisterValidation("unique", rules.Unique)
	if uniqueErr != nil {
		return
	}
	phoneNumberErr := validatorInstance.RegisterValidation("phonenumber", rules.PhoneNumber)
	if phoneNumberErr != nil {
		return
	}
	inErr := validatorInstance.RegisterValidation("in", rules.IN)
	if inErr != nil {
		return
	}
	fmt.Println("Custom Validation Initialized ...")
}
