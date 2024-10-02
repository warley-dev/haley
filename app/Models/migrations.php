<?php

namespace App\Models;

use Haley\Collections\Model;

class migrations extends Model
{
    protected static string|null $connection = null;

    public static string $table = 'migrations';
    protected static string|null $id = 'id';
    protected static array $fillable = ['id', 'migration', 'count', 'created_at'];
};