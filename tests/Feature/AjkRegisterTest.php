<?php

namespace Tests\Feature;

use Tests\TestCase;

class AjkRegisterTest extends TestCase
{
    public function test_register_page_can_be_loaded()
    {
        $response = $this->get('/ajk/register');

        $response->assertStatus(200);
    }
}