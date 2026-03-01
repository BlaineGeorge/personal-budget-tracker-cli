<?php

namespace Blaine\PersonalBudgetTrackerCli\Repository;

use Blaine\PersonalBudgetTrackerCli\Exceptions\StorageException;
use InvalidArgumentException;
use JsonException;

class TransactionRepository
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        if (trim($filePath) === '') {
            throw new InvalidArgumentException('The supplied path was empty');
        }

        if (!is_dir(dirname($filePath))) {
            throw new InvalidArgumentException('The directory ' . dirname($filePath) . ' does not exist');
        }

        $this->filePath = $filePath;
    }

    /**
     * @throws JsonException
     * @throws StorageException
     */
    public function loadTransactions(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $file = file_get_contents(
            filename: $this->filePath
        );

        if ($file === false) {
            throw new StorageException('Failed to access file data.');
        }

        if ($file === '') {
            return [];
        }

        $load =  json_decode(
            json: $file,
            associative: true,
            flags: JSON_THROW_ON_ERROR,
        );

        if (!is_array($load) || !array_is_list($load)) {
            throw new StorageException('Data is unexpected or corrupted.');
        }

        return $load;
    }

    /**
     * @throws JsonException
     * @throws StorageException
     */
    public function saveTransactions(array $transactions): void
    {
        $temporaryFile = file_put_contents(
            filename: $this->filePath . '.tmp',
            data: json_encode($transactions, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR)
        );

        if ($temporaryFile === false) {
            throw new StorageException('Failed to create file.');
        }

        $save = rename(
            from: $this->filePath . '.tmp',
            to: $this->filePath
        );

        if ($save === false) {
            unlink($this->filePath . '.tmp');
            throw new StorageException('Failed to write file to disk.');
        }
    }
}