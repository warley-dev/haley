<?php        
namespace App\Models;
use Haley\Collections\Model;       

class Migrations extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'migrations';
    public static string|null $primary = 'id';
    public static array $columns = ['id','migration','batch']; 
}