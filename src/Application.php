<?php

namespace Blaine\PersonalBudgetTrackerCli;

use Blaine\PersonalBudgetTrackerCli\Exceptions\StorageException;
use Blaine\PersonalBudgetTrackerCli\Repository\TransactionRepository;
use JsonException;

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

    /**
     * @throws JsonException
     */
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

            $repository = new TransactionRepository('storage/test.json');

            if ($input == 1) {
                try {
                    $repository->saveTransactions([
                        ['date' => '01/03/2026', 'transaction' => 150],
                        ['date' => '01/02/2026', 'transaction' => 150],
                        ['date' => '01/01/2026', 'transaction' => 150],
                    ]);
                } catch (StorageException $e) {
                    echo $e->getMessage();
                }
            }

            if ($input == 2) {
                try {
                     print_r($repository->loadTransactions());
                } catch (StorageException $e) {
                    echo $e->getMessage();
                }
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