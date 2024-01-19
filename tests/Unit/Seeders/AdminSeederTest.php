<?php

use App\Models\User;

test('There will be one default admin', function () {
    $this->assertDatabaseCount(User::class,0);

    $this->seed();

    $this->assertDatabaseCount(User::class,1);

    $this->assertDatabaseHas(User::class, [
        'email' => 'admin@email.com',
        'is_admin' => true,
    ]);
});
