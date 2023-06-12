<?php        
namespace App\Models;
use Haley\Collections\Model;       

class Test1 extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'test_1';
    public static string|null $primary = null;
    public static array $columns = ['id_2','id_3','update_at','created_at']; 
}