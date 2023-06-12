<?php        
namespace App\Models;
use Haley\Collections\Model;       

class PasswordResetTokens extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'password_reset_tokens';
    public static string|null $primary = 'email';
    public static array $columns = ['email','token','created_at']; 
}