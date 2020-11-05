<?php


namespace TaskForce\Exporter;


interface IExporter
{
    public function prepare(): void;

    public function saveData(string $template, string $data = ''): void;
}
