<?php

namespace App\Models;

use Haley\Collections\Model;

class usuarios extends Model
{
    protected static string|null $connection = null;

    public static string $table = 'usuarios';
    protected static string|null $id = 'id';
    protected static array $fillable = ['id', 'id_acesso', 'nome', 'email', 'ativo', 'created_at', 'update_at'];
};
