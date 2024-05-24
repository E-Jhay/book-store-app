<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Book::with('author')
                ->get()
                ->map(function($book) {
                    return [
                        'name' => $book->name,
                        'author_id' => $book->author_id,
                        'cover' => "{$book->cover}",
                    ];
                });
    }

    public function headings(): array
    {
        return [
            'name',
            'author_id',
            'cover',
        ];
    }
}
