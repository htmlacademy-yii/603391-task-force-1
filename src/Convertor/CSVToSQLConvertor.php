<?php
//
//declare(strict_types=1);
//namespace TaskForce\Convertor;
//use SplFileObject;
//use TaskForce\Exception\FileException;
//use TaskForce\Exception\TaskForceException;
//
//
//class CSVToSQLConvertor
//{
//    private  $csv_filename;
//    private  $csv_columns;
//    private  $fp;
//    private  $error = null;
//    private  $table_name;
//    private  $sql_columns;
//    private  $sql_fo;
//    private  $data_types;
//
//    /**
//     * CSVToSQLconvertor constructor.
//     * @param string $csv_file
//     * @param string $sql_file
//     * @param string $table_name
//     * @param array $csv_columns
//     * @param array $sql_columns
//     * @param array $data_types
//     */
//
//    {
//        $this->csv_filename = $csv_file;
//        $this->csv_columns = $csv_columns;
//        $this->sql_columns = $sql_columns;
//        $this->table_name = $table_name;
//        $this->data_types = $data_types;
//        $this->sql_fo = new SplFileObject($sql_file, "w");
//    }
//
//    public function import(): void
//    {
//        if (!$this->validateColumns($this->csv_columns)) {
//            throw new FileException("Заданы неверные заголовки столбцов");
//        }
//        if (!file_exists($this->csv_filename)) {
//            throw new TaskForceException("Файл не существует");
//        }
//        $this->fp = fopen($this->csv_filename, 'r');
//        if (!$this->fp) {
//            throw new TaskForceException("Не удалось открыть файл на чтение");
//        }
//        $header_data = $this->getHeaderData();
//        if ($header_data !== $this->csv_columns) {
//            throw new FileException("Исходный файл не содержит необходимых столбцов");
//        }
//        $this->convertFile();
//    }
//    private function convertFile(): void
//    {
//        $this->saveData('INSERT INTO `' . $this->table_name . '` ' . chr(13) . chr(10));
//        $this->saveData($this->formatValues($this->sql_columns, '`') . chr(13) . chr(10)
//            . ' VALUES');
//
//        $not_first_element = false;
//        foreach ($this->getNextLine() as $line) {
//            if ($line != null) {
//                if ($not_first_element) {
//                    $this->saveData(','. chr(13) . chr(10));
//                } else { $not_first_element = true;};
//                $this->saveData($this->formatValues($line));
//            }
//        }
//    }
//    private function formatValues(array $line_array, string $symbol = "type"): string
//    {
//        $sql = '('; $i = 0; $quotes = $symbol;
//        do {
//            if ($symbol == 'type') {
//                if ($this->data_types[$i] == 'string') {
//                    $quotes = "'";
//                } else {
//                    $quotes = "";
//                };
//            }
//            $sql .= $quotes . $line_array[$i] . $quotes . ",";
//            $i++;
//        } while ($i < count($line_array));
//
//        $sql = rtrim($sql,',');
//        $sql .= ')';
//        return $sql;
//    }
//    private function saveData(string $data): void
//    {
//        if ($data === null) {
//            throw new FileException('Can not write null to file.');
//            return;
//        }
//        $written = $this->sql_fo->fwrite($data);
//        if ($written === null) {
//            throw new FileException('Error write to file.');
//        }
//    }
//    private function getHeaderData(): ?array
//    {
//        rewind($this->fp);
//        $data =  fgetcsv($this->fp);
//        return $data;
//    }
//    private function getNextLine(): ?iterable
//    {
//        $result = null;
//        while (!feof($this->fp)) {
//            yield fgetcsv($this->fp);
//        }
//        return $result;
//    }
//    private function validateColumns(array $columns): bool
//    {
//        $result = true;
//        if (count($columns)) {
//            foreach ($columns as $column) {
//                if (!is_string($column)) {
//                    $result = false;
//                }
//            }
//        } else {
//            $result = false;
//        }
//        return $result;
//    }
//}
