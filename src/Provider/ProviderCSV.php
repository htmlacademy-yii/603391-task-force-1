<?php
declare(strict_types=1);

namespace TaskForce\Provider;

use SplFileObject;
use TaskForce\Exception\FileException;
use TaskForce\Exception\TaskForceException;

class ProviderCSV extends Provider
{

    private $csvFile;
    public $fo;
    public $columns;
    public $isValidColumns;
    private $distColumns;

    public function __construct(string $csvFile, array $distColumns)
    {
        $this->csvFile = $csvFile;
        $this->distColumns = $distColumns;
    }

    public function open(): void
    {
        if (!file_exists($this->csvFile)) {
            throw new FileException("Файл не существует");
        }

        $this->fo = new SplFileObject($this->csvFile, "r");

        if (!$this->fo) {
            throw new TaskForceException("Не удалось открыть файл на чтение");
        }
        $this->fo->setFlags(SplFileObject::READ_CSV);

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

    private function getHeaderData(): ?array
    {
        $this->fo->rewind();
        return $this->fo->fgetcsv();
    }

    public function getNextLine(): ?iterable
    {
        $result = null;
        while (!$this->fo->eof()) {
            yield $this->fo->fgetcsv();
        }
        return $result;
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
