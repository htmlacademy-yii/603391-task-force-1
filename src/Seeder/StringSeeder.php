<?php

namespace TaskForce\Seeder;

class StringSeeder extends Seeder
{
    public function __construct(private string $string)
    {
    }

    public function getData(): string
    {
        return $this->string;
    }
}
