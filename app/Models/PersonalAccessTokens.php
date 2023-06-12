<?php        
namespace App\Models;
use Haley\Collections\Model;       

class PersonalAccessTokens extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'personal_access_tokens';
    public static string|null $primary = 'id';
    public static array $columns = ['id','tokenable_type','tokenable_id','name','token','abilities','last_used_at','expires_at','created_at','updated_at']; 
}