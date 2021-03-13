<?php
declare(strict_types=1);

namespace TaskForce\Model;

class Model
{
    public function __construct(
        public string $tableName,
        public array $fields,
        public ?array $dataObjects = null,
        public ?int $count = null,
    ) {
    }
}
