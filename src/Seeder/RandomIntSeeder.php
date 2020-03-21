<?php


namespace TaskForce\Seeder;


class RandomIntSeeder extends Seeder
{
    private $from;
    private $to;

    public function __construct(int $from, int $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function getData()
    {
        return random_int($this->from, $this->to);
    }
}
