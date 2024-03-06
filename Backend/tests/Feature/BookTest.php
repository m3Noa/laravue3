<?php

namespace Tests\Feature;

use Tests\TestCase;

class BookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSearchBook()
    {
        $response = $this->get('/api/v1/book/search?q=officia');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'publisher',
                    'title',
                    'summary',
                    'authors'
                ]
            ]
        ]);
    }

    public function testSearchBookWithoutQuery()
    {
        $response = $this->get('/api/v1/book/search');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);
    }
}
