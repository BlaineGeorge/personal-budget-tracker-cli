<?php

namespace Blaine\PersonalBudgetTrackerCli;

class Application
{
    private array $options = [
        '1' => 'Add Transaction',
        '2' => 'List Transaction',
        '3' => 'Edit Transaction',
        '4' => 'Delete Transaction',
        '5' => 'View Summary',
        '0' => 'Exit'
    ];

    public function run(): int
    {
        while (true) {
            echo $this->renderMenu();

            $input = readline("Choose an option: ");

            if ($input == 0) {
                return 0;
            }

            echo "You chose option $input \n";
        }
    }

    private function renderMenu(): string
    {
        $menu_title = "=== Budget Tracker ===";

        $menu_options = "";

        foreach ($this->options as $index => $option) {

            $menu_options .= "$index . $option \n";
        }

        return $menu_title . "\n\n" . $menu_options;
    }
}