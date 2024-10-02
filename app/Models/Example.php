<?php

namespace App\Models;

use Haley\Collections\Model;

class Example extends Model
{
    protected static string|null $connection = null;

    public static string $table = 'example';
    protected static string|null $id = null;
    protected static array $fillable = [];
};