<?php
declare(strict_types=1);

namespace TaskForce\Converter;

use TaskForce\Exception\TaskForceException;
use TaskForce\Exporter\IExporter;
use TaskForce\Model\Model;
use TaskForce\Provider\IProvider;


class SqlToCsvConverter
{

    public $serviceProvider;
    public $serviceExporter;
    public $model;

    /**
     * @param IProvider $srv
     */
    public function setProvider(IProvider $srv)
    {
        $this->serviceProvider = $srv;
    }

    /**
     * @param IExporter $exporter
     */
    public function setExporter(IExporter $exporter)
    {
        $this->serviceExporter = $exporter;
    }

    /**
     * @return void
     * @throws TaskForceException
     */
    private function checkServices(): void
    {
        if (!isset($this->serviceExporter)) {
            throw new TaskForceException('Exporter is not set.');
        }
        if (!isset ($this->serviceProvider)) {
            throw new TaskForceException('Provider is not set.');
        }
        if (!isset ($this->model)) {
            throw new TaskForceException('Model is not set.');
        }
    }

    /**
     * @param Model $model
     * @throws TaskForceException
     */
    public function start(Model $model): void
    {
        $this->model = $model;
        $this->checkServices();
        $this->serviceProvider->open();
        $this->serviceExporter->prepare();
        $this->convertFile();
    }

    private function convertHeader(): void
    {

        $this->serviceExporter->saveData("INSERT INTO `%s` ", $this->model->tableName);
        $this->serviceExporter->saveData("(%s) \n  VALUES \n", $this->getListValues($this->model->fields, '`'));
    }

    private function convertBody(): void
    {

        $list = null;
        foreach ($this->serviceProvider->getNextLine() as $csvElements) {

            if (!count($csvElements) || ($csvElements[0] == null)) {
                return;
            }

            if ($list) {
                $this->serviceExporter->saveData(",\r\n");
            }

            $list = $this->getFakeData($csvElements);
            $this->serviceExporter->saveData("(%s)", $this->getListValues($list));
            $this->model->count++;

        }

    }

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
        foreach ($this->model->dataObjects as $dataObject) {
            if ($dataObject->getData() !== null) {
                array_push($newArray, $dataObject->getData());
            } else {
                array_push($newArray, array_shift($elements));
            }
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
