<?php        
namespace App\Models;
use Haley\Collections\Model;       

class Teste extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'teste';
    public static string|null $primary = 'id';
    public static array $columns = ['id','new_helo']; 
}