<?php
declare(strict_types=1);

namespace TaskForce\Convertor;

use TaskForce\Exporter\ExporterSQL;
use TaskForce\Model\Model;
use TaskForce\Provider\ProviderCSV;


class Convertor
{
    private $CSVfo;
    private $SQLfo;
    private $Model;


    public function __construct(ProviderCSV $CSVfo, Model $Model, ExporterSQL $SQLfo)
    {
        $this->CSVfo = $CSVfo;
        $this->SQLfo = $SQLfo;
        $this->Model = $Model;
    }

    public function start(): void
    {
        $this->CSVfo->open();
        $this->SQLfo->prepare();
        $this->convertFile();
    }

    private function createSqlCommand(): void
    {

        $this->SQLfo->saveData('INSERT INTO `' . $this->Model->tableName . '` ' . chr(13) . chr(10));
        $this->SQLfo->saveData($this->formatValues($this->Model->fields, '`') . chr(13) . chr(10)
            . ' VALUES ');
    }

    private function convertFile(): void
    {
        $this->createSqlCommand();
        $not_first_element = false;
        foreach ($this->CSVfo->getNextLine() as $csvLine) {
            if ($csvLine != null && $csvLine[0] != null) {
                if ($not_first_element) {
                    $this->SQLfo->saveData(',' . chr(13) . chr(10));
                } else {
                    $not_first_element = true;
                };
                $newElements = $this->createElements($csvLine);
                $this->SQLfo->saveData($this->formatValues($newElements));
                $this->Model->count++;
            }
        }
    }

    private function createElements($csvElements): array
    {
        $newArray = [];
        foreach ($this->Model->dataObjects as $dataObject) {
            if ($dataObject->getData() !== null) {
                array_push($newArray, $dataObject->getData());
            } else array_push($newArray, array_shift($csvElements));
        }

        return $newArray;
    }

    private function formatValues(array $line_array, string $symbol = "type"): string
    {
        $sql = '(';
        $i = 0;
        $quotes = $symbol;
        do {
            if ($symbol == 'type') {
                if (!is_numeric($line_array[$i])) {
                    $quotes = "'";
                } else {
                    $quotes = "";
                };
            }
            $sql .= $quotes . $line_array[$i] . $quotes . ",";
            $i++;
        } while ($i < count($line_array));

        $sql = rtrim($sql, ',');
        $sql .= ')';
        return $sql;
    }
}
