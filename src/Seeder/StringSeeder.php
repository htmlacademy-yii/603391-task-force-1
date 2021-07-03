<?php

namespace TaskForce\Seeder;

class StringSeeder extends Seeder
{
    /**
     * StringSeeder constructor.
     * @param string $string
     */
    public function __construct(private string $string)
    {
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->string;
    }
}
