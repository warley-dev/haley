<?php        
namespace App\Models;
use Haley\Collections\Model;       

class Filmes extends Model
{   
    public static string $connection = 'mysql';
    public static string $table = 'filmes';
    public static string|null $primary = 'id';
    public static array $columns = ['descricao','elenco','genero','id','imdb','img','lancamento','media_votos','popular','titulo','tmdb','trailer','uauflix']; 
}