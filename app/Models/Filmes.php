<?php        
namespace App\Models;
use Haley\Collections\Model;       

class Filmes extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'filmes';
    public static string|null $primary = 'id';
    public static array $columns = ['id','titulo','descricao','elenco','img','trailer','genero','lancamento','media_votos','popular','imdb','tmdb','uauflix']; 
}