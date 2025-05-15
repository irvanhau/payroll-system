<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DataImport implements ToCollection
{
    private $data = [];
    private $headers = [];

    public function collection(Collection $rows)
    {
        $this->headers = $rows->first()->toArray();

        foreach ($rows->slice(1) as $row) {
            $this->data[] = array_combine($this->headers, $row->toArray());
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
