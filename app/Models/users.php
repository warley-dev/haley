<?php

namespace App\Models;

use Haley\Collections\Model;

class users extends Model
{
    protected static string|null $connection = null;

    public static string $table = 'users';
    protected static string|null $id = 'id';
    protected static array $fillable = ['id', 'name', 'email', 'password', 'active', 'created_at', 'update_at'];
};