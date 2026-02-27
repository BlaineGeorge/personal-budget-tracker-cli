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

            if (!$this->validateInput($input))
            {
                echo "Invalid choice, please choose another option";
                continue;
            }

            if ($input === '0') {
                return 0;
            }

            echo "You chose option $input \n\n";
        }
    }

    private function renderMenu(): string
    {
        $menuTitle = "=== Budget Tracker ===";
        $menuOptions = "";

        foreach ($this->options as $index => $option) {
            $menuOptions .= "$index. $option \n";
        }

        return $menuTitle . "\n\n" . $menuOptions;
    }

    private function validateInput(string $input): bool
    {
        return array_key_exists($input, $this->options);
    }
}