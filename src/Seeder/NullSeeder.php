<?php


namespace TaskForce\Seeder;


class NullSeeder extends Seeder
{

    public function __construct()
    {

    }

    public function getData(): ?string
    {
        return null;
    }
}
