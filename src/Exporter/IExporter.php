<?php


namespace TaskForce\Exporter;


interface IExporter
{
    function prepare(): void;
    function saveData(string $template, string $data = ''): void;
}
