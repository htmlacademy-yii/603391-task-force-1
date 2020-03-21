<?php
declare(strict_types=1);

namespace TaskForce\Exporter;


use SplFileObject;
use TaskForce\Exception\FileException;



class ExporterSQL extends Exporter
{
    private $SQLfo;

    /**
     * CSVToSQLconvertor constructor.
     * @param string $sqlFile
     */
    public function __construct(string $sqlFile)
    {
        $this->SQLfo = new SplFileObject($sqlFile, "w");
    }


    public function prepare(): void
    {
        if (!$this->SQLfo) {
            throw new FileException("Can not open file for write.");
        }
    }

    public function saveData(string $data): void
    {
        if ($data === null) {
            throw new FileException('Can not write null to file.');
        }

        $written = $this->SQLfo->fwrite($data);

        if ($written === null) {
            throw new FileException('Error write to file.');
        }
    }

}
