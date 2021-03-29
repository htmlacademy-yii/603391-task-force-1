<?php

namespace TaskForce\Seeder;

use Exception;

class RandomIntSeeder extends Seeder
{
    public function __construct(
        private int $from,
        private int $to,
    ) { }

    /**
     * @return int
     * @throws Exception
     */
    public function getData(): int
    {
        return random_int($this->from, $this->to);
    }
}
