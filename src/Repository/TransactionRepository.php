<?php

namespace Blaine\PersonalBudgetTrackerCli\Repository;

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

    private function loadTransactions()
    {

    }

    private function saveTransactions()
    {

    }

    private function atomicWrite()
    {

    }
}