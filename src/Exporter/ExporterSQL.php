<?php
declare(strict_types=1);

namespace TaskForce\Exporter;

use TaskForce\Exception\FileException;


class ExporterSQL implements IExporter
{
    private $sqlFile;

    /**
     * ExporterSQL constructor.
     * @param string $sqlFile
     */
    public function __construct(string $sqlFile)
    {
        $this->sqlFile = fopen($sqlFile, 'w');
    }

    /**
     * @throws FileException
     */
    public function prepare(): void
    {
        if (!$this->sqlFile) {
            throw new FileException("Can not open file for write.");
        }
    }

    /**
     * @param string $template
     * @param string $data
     * @throws FileException
     */
    public function saveData(string $template, string $data = ''): void
    {
        if ($data === null) {
            throw new FileException('Can not write null to file.');
        }

        if ($template === null) {
            throw new FileException('Need template.');
        }

        $written = fprintf($this->sqlFile, $template, $data);

        if ($written === null) {
            throw new FileException('Error write to file.');
        }
    }
}
