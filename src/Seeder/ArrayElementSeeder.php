<?php


namespace TaskForce\Seeder;


class ArrayElementSeeder
{

    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data [random_int(0, count($this->data) - 1)];
    }
}
