package sms

import (
	"fmt"
	"time"
)

type SMSSender interface {
	SendSMS(phone, message string)
}

type TwilioSender struct {
}

func (t *TwilioSender) SendSMS(phone, message string) {
	time.Sleep(2 * time.Second)
	fmt.Printf("📱 SMS sent to %s: %s\n", phone, message)
}
