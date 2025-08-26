package response

type HTTPError struct {
	Code     int
	Messages *[]string
}

func NewHTTPError(code int, messages ...string) *HTTPError {
	return &HTTPError{
		Code:     code,
		Messages: &messages,
	}
}
