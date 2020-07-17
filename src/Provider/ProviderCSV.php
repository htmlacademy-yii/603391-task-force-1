<?php
declare(strict_types=1);

namespace TaskForce\Provider;

use SplFileObject;
use TaskForce\Exception\FileException;
use TaskForce\Exception\TaskForceException;

class ProviderCSV implements IProvider
{

    private $csvFile;
    public $fileObject;
    public $columns;
    public $isValidColumns;
    private $distColumns;

    /**
     * ProviderCSV constructor.
     * @param string $csvFile
     * @param array $distColumns
     */
    public function __construct(string $csvFile, array $distColumns)
    {
        $this->csvFile = $csvFile;
        $this->distColumns = $distColumns;
    }

    /**
     * @throws FileException
     * @throws TaskForceException
     */
    public function open(): void
    {
        if (!file_exists($this->csvFile)) {
            throw new FileException("Файл не существует");
        }

        $this->fileObject = new SplFileObject($this->csvFile, "r");

        if (!$this->fileObject) {
            throw new TaskForceException("Не удалось открыть файл на чтение");
        }
        $this->fileObject->setFlags(SplFileObject::READ_CSV);

        $headerData = $this->getHeaderData();
        if ($headerData !== $this->distColumns) {
            throw new FileException("Исходный файл не содержит необходимых столбцов");
        }

        $this->columns = $this->getHeaderData();
        if (!$this->validateColumns($headerData)) {
            throw new FileException("Заданы неверные заголовки столбцов");
        }
        $this->isValidColumns = $this->validateColumns($this->distColumns);


    }

    public function getHeaderData(): ?array
    {
        $this->fileObject->rewind();
        return $this->fileObject->fgetcsv();
    }

    public function getNextLine(): ?iterable
    {
        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }
        return null;
    }

    public function validateColumns(array $columns): bool
    {
        $result = true;
        if (count($columns)) {
            foreach ($columns as $column) {
                if (!is_string($column)) {
                    $result = false;
                }
            }
        } else {
            $result = false;
        }
        return $result;
    }

}
