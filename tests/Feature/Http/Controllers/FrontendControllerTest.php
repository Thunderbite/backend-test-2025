<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

final class FrontendControllerTest extends TestCase
{
    public function test_home()
    {
        $this->get('/')->assertStatus(200);
    }
}
