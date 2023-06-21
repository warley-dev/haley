<?php        
namespace App\Models;
use Haley\Collections\Model;       

class Haley extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'haley';
    public static string|null $primary = 'id';
    public static array $columns = ['fore','id','mudou']; 
}