package cmd

import (
	"fmt"
	"github.com/spf13/cobra"
	"os"
)

var rootCmd = &cobra.Command{
	Use:   "help",
	Short: "Help CMD",
	Long:  `Description Of CMD`,
}

func init() {
	rootCmd.AddCommand(serveCmd)
}
func Execute() {
	if err := serveCmd.Execute(); err != nil {
		fmt.Println(err)
		os.Exit(1)
	}
}
