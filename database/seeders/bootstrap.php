<?php

use App\Models\users;
use Haley\Collections\Hash;

return new class
{
    public function run()
    {
        users::createOrIgnore([[
            'name' => 'Admin',
            'email' => 'admin@hotmail.com',
            'password' => Hash::createPassword('123456789'),
            'active' => 1
        ],            [
            'name' => 'Client',
            'email' => 'client@hotmail.com',
            'password' => Hash::createPassword('123456789'),
            'active' => 1
        ]]);
    }
};
