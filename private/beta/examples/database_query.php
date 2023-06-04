<?php
use Haley\Database\Query\DB;

$select = DB::select('usuarios')
->where(['nome' => 'ppp'])
->orderBy('RAND()')
->coluns(['id','nome'])
->limit(5)
->execute();        
dd($select);

$select_one = DB::selectOne('usuarios')
->where(['nome' => 'ppp'])
->orderBy('RAND()')
->coluns(['id','nome'])       
->execute();        
dd($select_one);

$insert = DB::insert('usuarios')
->values(['nome' => 'ola'])       
->execute();        
dd($insert);

$delete = DB::delete('usuarios')
->where(['nome' => 'ppp'])
->limit(1) 
->execute();        
dd($delete);

$update = DB::update('usuarios')
->values(['nome' => 'novo nome'])
->where(['nome' => 'ppp'])
->limit(1) 
->execute();        
dd($update);

dd(DB::select('filmes')->coluns(['LOWER(titulo) AS titulo','id'])->orderBy('id ASC')->limit(50)->execute()); 


// ->coluns(['media_votos'])
// ->avg('id', 'total teste')
// ->groupBy(filmes::media_votos)

//->whereRaw('id IN (SELECT id FROM usuarios WHERE titulo != ? AND titulo != ?)' ,['Viagem à Lua','O Cozinheiro'])   
// ->whereNotIn('id',[1,3,4,5,6,7,8,9])


$filmes = DB::select('filmes')      
->coluns(['media_votos as media',
'CASE media_votos
    WHEN media_votos > 8 THEN "alto"
    WHEN media_votos < 5 THEN "baixo"
    ELSE "TESTE"
END as media'
])->limit(900)->orderBy('RAND()');     

return dd($filmes->execute());


// reaproveitando query
$query = DB::select('filmes')->where(['titulo' => '%vingadores%'],'LIKE'); 

$total = DB::selectOne('filmes');      
$total->query = $query->query;
$total->count('id','total');

$query->coluns(['titulo']);
$query->limit(5,2);

dd($total->execute());
dd($query->execute());



// multiplos insert
$filmes = DB::select('filmes')->columns('titulo','descricao')->limit(30000)->execute();   
$insert = DB::insert('json')
->columns('teste' ,'teste_d');

foreach ($filmes as $value) {
    $insert->values([$value['titulo'],$value['descricao']]);           
} 

dd($insert->execute());
// ou
// $insert = DB::insert('json')->columns('teste' ,'teste_d');    
// $insert->raw(DB::select('filmes')->columns('titulo','descricao')->getQuery());
// dd($insert->getQuery());

