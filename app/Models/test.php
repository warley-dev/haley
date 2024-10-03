<?php

namespace App\Models;

use Haley\Collections\Model;

class test extends Model
{
    protected static string|null $connection = null;

    public static string $table = 'test';
    protected static string|null $id = 'id';
    protected static array $fillable = ['id', 'int', 'double', 'float', 'decimal', 'boolean', 'varchaaeres', 'text', 'json', 'timestamp', 'date', 'datetime', 'year', 'time', 'created_at', 'update_at', 'set', 'enum', 'nome', 'email'];
};