<?php

namespace TaskForce\Provider;

interface IProvider
{
    public function open(): void;
    public function getHeaderData(): ?array;
    public function getNextLine(): ?iterable;
    public function validateColumns(array $columns): bool;
}
