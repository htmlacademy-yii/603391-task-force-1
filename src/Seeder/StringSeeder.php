<?php


namespace TaskForce\Seeder;


class StringSeeder extends Seeder
{

    private $string;

    public function __construct(string $value)
    {
        $this->string =  $value;
    }

    public function getData()
    {
        return $this->string;
    }
}
