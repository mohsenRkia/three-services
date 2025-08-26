package helpers

import (
	"github.com/gin-gonic/gin"
	"myGolangFramework/internal/infrastructure/validations"
	"net/http"
)

type APIResponse[T any] struct {
	Status  int       `json:"statusCode"`
	Data    *T        `json:"data"` // Use a pointer to T to allow nil values
	Errors  *[]string `json:"errors"`
	Message string    `json:"message"`
}

type SuccessResponse struct {
	Success bool `json:"success"`
}

func NewAPIResponse[T any](statusCode int, data *T, errors []string, message string) APIResponse[T] {
	return APIResponse[T]{
		Status:  statusCode,
		Data:    data,
		Errors:  &errors,
		Message: message,
	}
}

func OkResponse[T any](data T) APIResponse[T] {
	return NewAPIResponse[T](http.StatusOK, &data, nil, "عملیات با موفقیت انجام شد")
}

func ErrorResponse[T any](statusCode int, errors []string, message string) APIResponse[T] {
	return NewAPIResponse[T](statusCode, nil, errors, message)
}

func SendErrorResponse(ctx *gin.Context, statusCode int, faMessage string, messages ...string) {
	ctx.AbortWithStatusJSON(statusCode, ErrorResponse[any](statusCode, messages, faMessage))
}

func NewHTTPError(code int, messages ...string) *validations.HTTPError {
	return &validations.HTTPError{
		Code:     code,
		Messages: &messages,
	}
}

func HandleHTTPErrors(ctx *gin.Context, err *validations.HTTPError, message string) {
	SendErrorResponse(ctx, err.Code, message, *err.Messages...)
}
