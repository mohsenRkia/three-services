package cmd

import (
	"github.com/spf13/cobra"
	"myGolangFramework/internal/bootstrap/migration"
)

func init() {
	rootCmd.AddCommand(migrateCMD)
}

var migrateCMD = &cobra.Command{
	Use:   "migrate",
	Short: "Migrate Exist Migrations",
	Long:  `Migrate Exist Migrations Inside The Application`,
	Run: func(cmd *cobra.Command, args []string) {
		migrate()
	},
}

func migrate() {
	migration.Migrator()
}
