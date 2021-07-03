<?php
declare(strict_types=1);

namespace TaskForce\Model;

class Model
{
    /**
     * Model constructor.
     * @param string $tableName
     * @param array $fields
     * @param array|null $dataObjects
     * @param int|null $count
     */
    public function __construct(
        public string $tableName,
        public array $fields,
        public ?array $dataObjects = null,
        public ?int $count = null,
    ) {
    }
}
