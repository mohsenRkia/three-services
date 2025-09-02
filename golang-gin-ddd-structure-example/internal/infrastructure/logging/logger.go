package logging

import (
	"fmt"
	"time"
)

type Logger interface {
	AddLog(message string, userID uint)
}
type ConsoleLogger struct {
}

func (c *ConsoleLogger) AddLog(message string, userID uint) {
	time.Sleep(500 * time.Millisecond)
	fmt.Printf("📝 Log: %s [userID=%d]\n", message, userID)
}
