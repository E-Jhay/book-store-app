<?php

namespace Tests\Unit;

use App\Exports\BooksExport;
use App\Imports\BooksImport;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    public function test_index_page()
    {
        Author::factory()->count(3)->create();
        Book::factory()->count(10)->create();

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Book/Index')
            ->has('books')
            ->has('filters'));
    }

    public function test_create_page()
    {
        Author::factory()->count(3)->create();

        $response = $this->get(route('books.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Book/Create')
            ->has('authors'));
    }

    public function test_store()
    {
        Storage::fake('public');
        $author = Author::factory()->create();
        $file = UploadedFile::fake()->image('cover.jpg');

        $response = $this->post(route('books.store'), [
            'name' => 'Test Book',
            'author_id' => $author->id,
            'cover' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Book added successfully.');
        $this->assertDatabaseHas('books', [
            'name' => 'Test Book',
            'author_id' => $author->id,
        ]);
    }

    public function test_edit_page()
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->get(route('books.edit', $book));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Book/Edit')
            ->has('authors')
            ->has('book'));
    }

    public function test_update()
    {
        Storage::fake('public');
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);
        $file = UploadedFile::fake()->image('new_cover.jpg');

        $response = $this->put(route('books.update', $book), [
            'name' => 'Updated Book',
            'author_id' => $author->id,
            'cover' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Book updated successfully.');
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'name' => 'Updated Book',
            'author_id' => $author->id,
        ]);
    }

    public function test_destroy()
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->delete(route('books.destroy', $book));

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('success', 'Book Deleted Successfully.');
        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }

    public function test_export()
    {
        Excel::fake();

        $response = $this->get(route('books.export'));

        $response->assertStatus(200);
        Excel::assertDownloaded('books.csv', function (BooksExport $export) {
            return true;
        });
    }

    public function test_import()
    {
        Storage::fake('public');
        Excel::fake();

        $file = UploadedFile::fake()->create('books.csv', 1024, 'text/csv');

        $response = $this->post(route('books.import'), [
            'books' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Books imported successfully.');
        Excel::assertImported('books.csv', function (BooksImport $import) {
            return true;
        });
    }
}
