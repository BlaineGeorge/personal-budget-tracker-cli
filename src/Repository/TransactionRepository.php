<?php

namespace Blaine\PersonalBudgetTrackerCli\Repository;

use JsonException;

class TransactionRepository
{
    private string $filePath;

    function __construct(string $filePath)
    {
        $this->filePath = $filePath;

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    public function loadTransactions(): array
    {
        $file = file_get_contents($this->filePath);

        if ($file === '' || $file === false) {
            return [];
        }

        $decodedFile = json_decode($file, true);

        if (!is_array($decodedFile)) {
            return [];
        }

        return $decodedFile;
    }

    /**
     * @throws JsonException
     */
    public function saveTransactions(array $transactions): void
    {
        $encodedTransactions = json_encode($transactions, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
        $temporaryPath = $this->filePath . '.tmp';
        $temporaryFile = file_put_contents($temporaryPath, $encodedTransactions);

        if ($temporaryFile === false) {
            // Throw an exception
        }

        rename($temporaryPath, $this->filePath);
    }
}