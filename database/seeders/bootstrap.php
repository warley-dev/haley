<?php

use App\Models\users;
use Haley\Collections\Password;

return new class
{
    public function run()
    {
        users::createOrIgnore([[
            'name' => 'Admin',
            'email' => 'admin@hotmail.com',
            'password' => Password::create('123456789'),
            'active' => 1
        ],            [
            'name' => 'Client',
            'email' => 'client@hotmail.com',
            'password' => Password::create('123456789'),
            'active' => 1
        ]]);
    }
};
