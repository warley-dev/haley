<?php        
namespace App\Models;
use Haley\Collections\Model;       

class Users extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'users';
    public static string|null $primary = 'id';
    public static array $columns = ['created_at','email','email_verified_at','id','name','password','remember_token','updated_at']; 
}