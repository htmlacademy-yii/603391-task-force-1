<?php


namespace TaskForce\Seeder;


use Exception;

class ArrayElementSeeder
{

    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
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
