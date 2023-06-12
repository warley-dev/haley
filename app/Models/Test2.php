<?php        
namespace App\Models;
use Haley\Collections\Model;       

class Test2 extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'test_2';
    public static string|null $primary = 'id';
    public static array $columns = ['id','test','update_at','created_at']; 
}