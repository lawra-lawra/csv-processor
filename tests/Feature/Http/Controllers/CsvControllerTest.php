<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class CsvControllerTest extends TestCase
{

    public function testIndexExists()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('csv');
        $response->assertSeeText('Upload a CSV');
    }

    public function testErrorsWithoutAFile()
    {
        $response = $this->post('/');

        $response->assertSessionHas('error', 'Please select a file to upload');
    }

    public function testCanUploadCsv()
    {
        Storage::fake('uploads');

        $content = file_get_contents(public_path('/example.csv'));

        $response = $this->post('/', [
            'file' => UploadedFile::fake()->createWithContent('test.csv', $content)
        ]);

        $response->assertOk();
    }
}
