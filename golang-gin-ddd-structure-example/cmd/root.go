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
func Execute(defaultCommand string) {
	if len(os.Args) == 1 && defaultCommand != "" {
		os.Args = append([]string{os.Args[0], defaultCommand}, os.Args[1:]...)
	}

	if err := rootCmd.Execute(); err != nil {
		fmt.Println(err)
		os.Exit(1)
	}
}
