<?php
declare(strict_types=1);

namespace TaskForce\Converter;

use TaskForce\Exception\FileException;
use TaskForce\Exception\TaskForceException;
use TaskForce\Exporter\ExporterSQL;
use TaskForce\Model\Model;
use TaskForce\Provider\ProviderCSV;


class Converter
{
    private $csvFileObject;
    private $sqlFileObject;
    private $Model;


    public function __construct(ProviderCSV $csvFileObject, Model $Model, ExporterSQL $sqlFileObject)
    {
        $this->csvFileObject = $csvFileObject;
        $this->sqlFileObject = $sqlFileObject;
        $this->Model = $Model;
    }

    /**
     * @throws FileException
     * @throws TaskForceException
     */
    public function start(): void
    {
        $this->csvFileObject->open();
        $this->sqlFileObject->prepare();
        $this->convertFile();
    }

    /**
     * @throws FileException
     */
    private function convertHeader(): void
    {

        $this->sqlFileObject->saveData("INSERT INTO `%s` ", $this->Model->tableName);
        $this->sqlFileObject->saveData("(%s) \n  VALUES \n", $this->getListValues($this->Model->fields, '`'));
    }

    /**
     * @throws FileException
     */
    private function convertBody(): void
    {

        $list = null;
        foreach ($this->csvFileObject->getNextLine() as $csvElements) {

            if (!count($csvElements) || ($csvElements[0] == null)) {
                return;
            }

            if ($list) {
                $this->sqlFileObject->saveData(",\r\n");
            }

            $list = $this->getFakeData($csvElements);
            $this->sqlFileObject->saveData("(%s)", $this->getListValues($list));
            $this->Model->count++;

        }

    }

    /**
     * Start process conversation.
     * @throws FileException
     */
    private function convertFile(): void
    {
        $this->convertHeader();
        $this->convertBody();
    }


    /**
     * Get fake data.
     * @param $elements
     * @return array
     */
    private function getFakeData(array $elements): array
    {
        $newArray = [];
        foreach ($this->Model->dataObjects as $dataObject) {
            if ($dataObject->getData() !== null) {
                array_push($newArray, $dataObject->getData());
            } else array_push($newArray, array_shift($elements));
        }

        return $newArray;
    }

    /**
     * @param array $line_array
     * @param string $symbol
     * @return string
     */
    private function getListValues(array $line_array, string $symbol = null): string
    {
        $list = '';
        $quotes = $symbol;

        foreach ($line_array as $element) {

            if (!$symbol) {
                $quotes = (is_numeric($element)) ? '' : "'";
            }

            $list .= $quotes . $element . $quotes;
            $list .= (next($line_array)) ? ',' : '';
        }

        return $list;
    }
}
