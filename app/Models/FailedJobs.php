<?php        
namespace App\Models;
use Haley\Collections\Model;       

class FailedJobs extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'failed_jobs';
    public static string|null $primary = 'id';
    public static array $columns = ['connection','exception','failed_at','id','payload','queue','uuid']; 
}