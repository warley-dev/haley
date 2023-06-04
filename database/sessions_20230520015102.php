<?php
namespace Database;
use Haley\Database\Migrations\Builder\Seeder;
use Haley\Database\Migrations\Builder\Table;

/**
 * Created at 20/05/2023 - 01:51:02
 */ 
class sessions_20230520015102
{
    public bool $active = true;

    public function migrate(Table $table)
    {  
        $table->definitions('sessions');       
        $table->primary('id');  

        $table->int('id_usuario');
        $table->varchar('token');

        $table->boolean('ativo');
        $table->boolean('sucesso');

        $table->varchar('ip');
        $table->varchar('firebase');
        $table->varchar('latitude');
        $table->varchar('longitude');
        
        $table->date('online_at');        
        $table->updateDate();
        $table->createdDate();

        $table->index('token');
    }

    public function seeder(Seeder $seeder)
    {
        $seeder->definitions('sessions'); 

        $seeder->values([
            [
               'id' => 1
            ],

            [
                'id' => 2
            ]
        ]);  
    }
}