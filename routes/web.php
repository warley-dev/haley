<?php


use App\Controllers\Web\HomeController;
use Haley\Collections\Password;
use Haley\Database\DB;
use Haley\Database\Migration\Builder\BuilderMemory;
// use Haley\Database\DB;
use Haley\Router\Route;
use Haley\Storage\FTP;

// codextudio.com
// wrmcodex.com
// codexcontroller.com
// shellcodex.com
// srcquery.com
// phpcontroller.com
// lockcodex.com
// requirescode.com
// slincode.com
// detecode.com
// iacodex
// softworke.com
// codetecker.com
// detecsoft.com
// linecodex
// sudocodex

// https://www.namecheap.com/domains/registration/results/?domain=softcode.php warley.dev

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web')->name('web')->group(function () {
    Route::view('view', 'test');

    Route::get('query', function () {
        $query = DB::table('filmes', 'f')->select([
            'f.id',
            'f.titulo',
            'f.descricao',
            'f.elenco',
            'f.img',
            'f.media_votos as votos',
            'f.trailer',
            'f.genero',
            'f.lancamento'
        ]);

        // dd(DatabaseDB::query($query)->fetchAll());

        return $query->count() . '<br>';



        // function phrases(string $value, string $start = '`', string $end = '`')
        // {
        //     // Substitui todas as ocorrências de uma palavra (exceto "as" com ou sem variações de maiúsculas/minúsculas) seguida por um espaço em branco por essa palavra entre crases
        //     $value = preg_replace('/\b(?!as\b)(\w+)\b/i', $start . '$1' . $end, $value);
        //     // Substitui todas as ocorrências de crases seguidas por um espaço em branco por essa crase seguida por um espaço em branco
        //     $value = preg_replace('/(' . preg_quote($start) . ')\s/', '$1 ', $value);
        //     return $value;
        // }

        // dd(phrases('as rock.com asdsfsadf sad.fgsdf As'));
    });

    Route::get('get', function () {
        // return dd(DB::table('filmes')->first());
    });

    Route::get('a', function () {
        $fiber = new Fiber(function (): void {
            $value = Fiber::suspend('fiber');
            echo "Value used to resume fiber: ", $value, PHP_EOL;
        });

        response();

        $value = $fiber->start();

        echo "Value from fiber suspending: ", $value, PHP_EOL;

        $fiber->resume('test');

        // dd(Password::create('@NaoSeiaSenha123'));
    });

    Route::view('chat', 'chat');
    Route::view('streaming', 'streaming');
    Route::view('webrtc', 'webrtc');

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('ftp', function () {
        $ftp = new FTP;

        // $ftp->test();

        $ftp->mysqlBackup();
    });
    // Route::get('/', 'HomeController@index')->name('home');

    Route::get('helo', function () {
        dd([

            get_current_user(),

            request()->url('test/1/2'),
            request()->urlFull('word'),
            request()->urlPath(),
            request()->urlQueryReplace(request()->url(), ['helo' => 51]),
            request()->urlFullQuery(),
            request()->ip(),
            request()->userAgent(),
            request()->method(),
            request()->all(),
            request()->https(),
            request()->input('helo'),
            request()->domain(),
            request()->origin(),
            request()->mobile(),
            request()->session()
        ]);
    })->name('home');




    // Route::post('/method', function () {
    //     dd(request()->all());

    //     // dd(request()->upload('file')->save(directoryjijiuhjPrivate()));

    // })->name('method');

    // Route::get('teste/{helo?}', function ($helo) {
    //     dd(DB::table('filmes')->limit(2)->get());

    //     // dd(array_map(strtolower(...), ['AAA', 'BBB']));
    // })->name('home');
});


// Route::prefix('test')->group(function () {

//     Route::get('/', function () {
//         $migration_up = require directoryRoot('database/migrations/test.php');
//         $migration_up->up();

//         $connection = BuilderMemory::$connection;
//         $helper = DB::helper($connection);

//         BuilderMemory::compileForeigns();
//         $build_columns = BuilderMemory::getColumns();

//         $build_id = BuilderMemory::$id;
//         $build_primary = BuilderMemory::$primary;
//         $build_table = BuilderMemory::$table;
//         $build_rename = BuilderMemory::$rename;
//         $build_constraint = BuilderMemory::$constraints;

//         $columns = [];
//         $columns_names = [];

//         // create table
//         if (!$helper->table()->has($build_table)) {
//             foreach ($build_columns as $value) {
//                 $columns[$value['name']] = str_replace(['[CL:NAME]', '[CL:TYPE]'], [$value['name'], $value['type']], $value['query']);
//             }

//             $helper->table()->create($build_table, $columns);
//         }

//         // modifi columns
//         else {
//             // rename columns
//             foreach ($build_rename as $column => $to) {
//                 if ($helper->column()->has($build_table, $column) and !$helper->column()->has($build_table, $to)) {
//                     $helper->column()->rename($build_table, $column, $to);
//                 }
//             }

//             // change or create columns
//             foreach ($build_columns as $value) {
//                 $type = trim(str_replace(['[CL:NAME]', '[CL:TYPE]'], ['', $value['type']], $value['query']));

//                 if ($helper->column()->has($build_table, $value['name'])) {
//                     $helper->column()->change($build_table, $value['name'], $type);
//                 } else {
//                     $helper->column()->create($build_table, $value['name'], $type);
//                 }

//                 $columns_names[] = $value['name'];
//             }
//         }

//         // column id primary key
//         if (count($build_id)) $helper->Constraint()->setId($build_table, $build_id['name'], $build_id['comment']);

//         // column primary key
//         else {
//             $atual_primary = $helper->Constraint()->getPrimaryKey($build_table);

//             if ($build_primary !== null) {
//                 if ($atual_primary !== $build_primary) {
//                     $helper->Constraint()->dropPrimaryKey($build_table);
//                     $helper->Constraint()->setPrimaryKey($build_table, $build_primary);
//                 }
//             }
//         }

//         // set constraints
//         $constraints_active = [];

//         foreach ($build_constraint as $value) {
//             $constraints_active[] = $value['name'];

//             if (!$helper->constraint()->has($build_table, $value['name'])) {
//                 $helper->constraint()->create($build_table, $value['name'], $value['type'], $value['value']);
//             } else {
//                 // change only foreign
//                 if ($value['type'] == 'FOREIGN KEY') $helper->constraint()->change($build_table, $value['name'], $value['type'], $value['value']);
//             }
//         }

//         // drop constraints if not in the build
//         // colocar if para apenas modo altomatico
//         foreach ($columns_names as $name) {
//             $constraints_check = $helper->constraint()->getNamesByColumn($build_table, $name);

//             if ($constraints_check !== null) {
//                 foreach ($constraints_check as $value) {
//                     if (!in_array($value, $constraints_active)) {
//                         // dd($value);
//                         $helper->constraint()->drop($build_table, $value);
//                     }
//                 }
//             }
//         }
//     });
// });
