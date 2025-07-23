package gateway

import (
	"github.com/gin-gonic/gin"
	"net/http"
)

type Handler struct {
}

func NewHandler() *Handler {
	return &Handler{}
}

func (h *Handler) Test(c *gin.Context) {
	c.JSON(http.StatusOK, "is `ok")
	//response, err := http.Get("products-service:9000/api/products")
	//if err != nil {
	//	fmt.Printf("error")
	//	return
	//}
	//defer func(Body io.ReadCloser) {
	//	err := Body.Close()
	//	if err != nil {
	//
	//	}
	//}(response.Body)
	//
	//body, error2 := io.ReadAll(response.Body)
	//if error2 != nil {
	//	fmt.Printf("error 2")
	//	return
	//}
	//fmt.Println(string(body))
	//fmt.Println("test 2")
	//return
}
