<?php
declare(strict_types=1);

namespace TaskForce\Model;

class Model
{
    public string $tableName;
    public array $fields;
    public int $count;
    public ?array $dataObjects;

    public function __construct(string $tableName, array $fields, array $dataObjects = null)
    {
        $this->tableName = $tableName;
        $this->fields = $fields;
        $this->dataObjects = $dataObjects;
    }
}
