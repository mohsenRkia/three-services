package config

import (
	"github.com/spf13/viper"
	"log"
	"sync"
)

// Environment holds the Viper configuration instance.
type Environment struct {
	viper *viper.Viper
	mu    sync.RWMutex // Optional: for thread-safe access
}

var (
	envInstance *Environment
	once        sync.Once
)

// NewEnvironment initializes and returns a singleton Environment instance.
func NewEnvironment() *Environment {
	once.Do(func() {
		v := viper.New()
		envInstance = &Environment{viper: v}
		envInstance.setup()
	})
	return envInstance
}

// setup configures Viper with .env file and defaults.
func (e *Environment) setup() {
	e.viper.SetConfigName(".env")
	e.viper.SetConfigType("env")
	e.viper.AddConfigPath(".")
	e.viper.AutomaticEnv()

	if err := e.viper.ReadInConfig(); err != nil {
		if _, ok := err.(viper.ConfigFileNotFoundError); ok {
			log.Println(".env file not found, using environment variables only")
		} else {
			log.Fatalf("Error reading config file: %v", err)
		}
	}

	e.viper.SetDefault("PORT", "3306")
	e.viper.SetDefault("DEBUG", false)
}

// Get returns a configuration value by key.
func (e *Environment) Get(key string) any {
	e.mu.RLock()
	defer e.mu.RUnlock()
	return e.viper.Get(key)
}

// GetString is a helper for string values.
func (e *Environment) GetString(key string) string {
	e.mu.RLock()
	defer e.mu.RUnlock()
	return e.viper.GetString(key)
}

// GetInt is a helper for int values.
func (e *Environment) GetInt(key string) int {
	e.mu.RLock()
	defer e.mu.RUnlock()
	return e.viper.GetInt(key)
}

// GetBool is a helper for bool values.
func (e *Environment) GetBool(key string) bool {
	e.mu.RLock()
	defer e.mu.RUnlock()
	return e.viper.GetBool(key)
}
