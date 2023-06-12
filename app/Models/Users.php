<?php        
namespace App\Models;
use Haley\Collections\Model;       

class Users extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'users';
    public static string|null $primary = 'id';
    public static array $columns = ['id','name','email','email_verified_at','password','remember_token','created_at','updated_at']; 
}