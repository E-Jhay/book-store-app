<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BooksImport implements ToModel, WithHeadingRow, WithChunkReading, SkipsOnFailure
{
    use SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Book([
            'name' => $row['name'],
            'author_id' => $row['author_id'],
            'cover' => $row['cover'],
        ]);
    }

    /**
     * Define the chunk size
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 500;
    }
}
