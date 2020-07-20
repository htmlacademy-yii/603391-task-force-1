<?php


namespace TaskForce\Provider;


interface IProvider
{
     function open(): void;
     function getHeaderData(): ?array;
     function getNextLine(): ?iterable;
     function validateColumns(array $columns): bool;
}
