<?php        
namespace App\Models;
use Haley\Collections\Model;       

class PersonalAccessTokens extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'personal_access_tokens';
    public static string|null $primary = 'id';
    public static array $columns = ['abilities','created_at','expires_at','id','last_used_at','name','token','tokenable_id','tokenable_type','updated_at']; 
}