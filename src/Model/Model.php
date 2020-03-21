<?php
declare(strict_types=1);

namespace TaskForce\Model;


class Model
{
    public $tableName;
    public $fields;
    public $count;
    public $dataObjects;

    public function __construct(string $tableName, array $fields, array $dataObjects = null)
    {
        $this->tableName = $tableName;
        $this->fields = $fields;
        $this->dataObjects = $dataObjects;
    }

    public function countFields()
    {
        return count($this->fields);
    }

}
