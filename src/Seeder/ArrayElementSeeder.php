<?php

namespace TaskForce\Seeder;

use Exception;

class ArrayElementSeeder
{
    public function __construct(private array $data)
    {
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getData()
    {
        return $this->data [random_int(0, count($this->data) - 1)];
    }
}
