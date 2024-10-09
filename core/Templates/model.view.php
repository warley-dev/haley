<?php


namespace App\Models{{ $namespace ? "\\$namespace" : '' }};

use Haley\Collections\Model;

class {{$name}} extends Model
{
    protected static string|null $connection = {{$connection ? "'" . $connection . "'" : 'null'}};

    public static string $table = '{{strtolower($name)}}';
    protected static string|null $id = {{$primary ? "'" . $primary . "'" : 'null'}};
    protected static array $fillable = [{{ implode(', ', $columns) }}];
};
