<?php

use Blaine\PersonalBudgetTrackerCli\Exceptions\StorageException;
use Blaine\PersonalBudgetTrackerCli\Repository\TransactionRepository;
use PHPUnit\Framework\TestCase;
use Random\RandomException;

final class TransactionRepositoryTest extends TestCase
{
    private string $tempFilePath;
    private TransactionRepository $repository;

    /**
     * @throws RandomException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->tempFilePath = sys_get_temp_dir() . '/test-' . bin2hex(random_bytes(8)) . '.json';
        $this->repository = new TransactionRepository($this->tempFilePath);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        if (file_exists($this->tempFilePath)) {
            unlink($this->tempFilePath);
        }
    }

    /**
     * @throws JsonException|StorageException
     */
    public function testTransactionsAreSavedAndLoaded(): void
    {
        $fakeTransactions = [
            ['date' => '01/03/2026', 'amount' => 150],
            ['date' => '28/02/2026', 'amount' => 300],
            ['date' => '25/02/2026', 'amount' => 50],
        ];

        $this->repository->saveTransactions($fakeTransactions);
        $this->assertEquals($fakeTransactions, $this->repository->loadTransactions());
    }

    /**
     * @throws StorageException
     * @throws JsonException
     */
    public function testDataTypesArePreserved():void
    {
        $dataTypes = [
            ['string' => 'string'],
            ['integer' => 150],
            ['float' => 19.99],
        ];

        $this->repository->saveTransactions($dataTypes);
        $load = $this->repository->loadTransactions();

        $this->assertIsString($load[0]['string']);
        $this->assertIsInt($load[1]['integer']);
        $this->assertIsFloat($load[2]['float']);
    }

    /**
     * @throws StorageException
     * @throws JsonException
     */
    public function testMultipleSequentialSaves(): void
    {
        $saveOne = [
            ['date' => '01/04/2025', 'amount' => 150],
            ['date' => '01/08/2025', 'amount' => 150],
            ['date' => '01/12/2025', 'amount' => 150],
        ];

        $saveTwo = [
            ['date' => '01/03/2024', 'amount' => 125],
            ['date' => '01/06/2024', 'amount' => 125],
            ['date' => '01/09/2024', 'amount' => 125],
            ['date' => '01/12/2024', 'amount' => 125],
        ];

        $this->repository->saveTransactions($saveOne);
        $this->repository->saveTransactions($saveTwo);
        $load = $this->repository->loadTransactions();

        $this->assertEquals($saveTwo, $load);
    }

    /**
     * @throws StorageException
     * @throws JsonException
     */
    public function testIfFileDoesntExistReturnsEmpty(): void
    {
        $this->assertEquals([], $this->repository->loadTransactions());
    }

    /**
     * @throws StorageException
     * @throws JsonException
     */
    public function testLoadingAnEmptyFileReturnsEmpty(): void
    {
        file_put_contents($this->tempFilePath, '');

        $this->assertEquals([], $this->repository->loadTransactions());
    }

    /**
     * @throws StorageException
     * @throws JsonException
     */
    public function testLoadingAnEmptyJsonArrayReturnsAnArray(): void
    {
        file_put_contents($this->tempFilePath, json_encode([]));

        $this->assertEquals([], $this->repository->loadTransactions());
    }

    public function testFileDoesntContainValidJson()
    {
    }
}