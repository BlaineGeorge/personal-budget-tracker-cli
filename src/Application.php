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

            $input = readline("\nChoose an option: ");

            if (!$this->validateInput($input)) {
                echo "Invalid choice, please choose another option\n";
                continue;
            }

            if ($input === '0') {
                return 0;
            }

            echo "You chose option $input \n";
        }
    }

    private function renderMenu(): string
    {
        $menuTitle = "\n=== Budget Tracker ===\n";
        $menuOptions = "";

        foreach ($this->options as $index => $option) {
            $menuOptions .= "$index. $option \n";
        }

        return $menuTitle . $menuOptions . "\n";
    }

    private function validateInput(string $input): bool
    {
        return array_key_exists($input, $this->options);
    }
}