<?php

declare(strict_types=1);

test('example', function () {
    $this->get('/')
        ->assertStatus(200);
});
