<?php

namespace TaskForce\Seeder;

class StringSeeder extends Seeder
{
    private string $string;

    public function __construct(string $value)
    {
        $this->string =  $value;
    }

    public function getData(): string
    {
        return $this->string;
    }
}
