<?php        
namespace App\Models;
use Haley\Collections\Model;       

class Test1 extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'test_1';
    public static string|null $primary = 'ighd';
    public static array $columns = ['ighd','id_2','id_3','update_at','created_at']; 
}