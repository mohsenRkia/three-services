package customValidation

import (
	"fmt"
	"github.com/gin-gonic/gin/binding"
	"github.com/go-playground/validator/v10"
	validation2 "myGolangFramework/pkg/customValidation/validation"
)

func Init() {
	v := binding.Validator.Engine().(*validator.Validate)
	if v != nil {
		v.RegisterValidation("exists", validation2.Exists)
		v.RegisterValidation("unique", validation2.Unique)
		v.RegisterValidation("phonenumber", validation2.PhoneNumber)
		v.RegisterValidation("in", validation2.IN)
		fmt.Println("Custom Validation Initialized ...")
	}

}
