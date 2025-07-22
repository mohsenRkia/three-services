package cmd

import (
	"github.com/spf13/cobra"
	"myGolangFramework/internal/bootstrap"
)

var serveCmd = &cobra.Command{
	Use:   "serve",
	Short: "Start the API server",
	Run: func(cmd *cobra.Command, args []string) {
		serve()
	},
}

func serve() {
	bootstrap.Boot()
}
