<?php

namespace TaskForce\Seeder;

use Exception;

class RandomIntSeeder extends Seeder
{
    private int $from;
    private int $to;

    public function __construct(int $from, int $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getData(): int
    {
        return random_int($this->from, $this->to);
    }
}
