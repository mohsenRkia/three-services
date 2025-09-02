package email

import (
	"fmt"
	"myGolangFramework/internal/domain/user"
	"time"
)

type EmailSender interface {
	SendWelcomeEmail(user *user.User)
}

type SMTP struct {
}

func (s *SMTP) SendWelcomeEmail(user *user.User) {
	fmt.Println("***************************")
	fmt.Println(user)
	fmt.Println("***************************")
	time.Sleep(2 * time.Second)
	fmt.Printf("✅ Email sent to %s\n", user.Email)
}
