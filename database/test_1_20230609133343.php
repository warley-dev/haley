<?php
namespace Database;
use Haley\Database\Migrations\Builder\Seeder;
use Haley\Database\Migrations\Builder\Table;

/**
 * Created at 09/06/2023 - 13:33:43
 */ 
class test_1_20230609133343
{
    public bool $active = true;

    public function migrate(Table $table)
    {  
        $table->definitions('test_1');       
        $table->primary('id');  

        $table->int('id_2')->comment('foreing');
        $table->int('id_3')->comment('foreing');

        // $table->foreign('id_2','test_2','id');
        // $table->foreign('id_3','test_2','id')->onDelete()->onUpdate();

        // $table->index('id_2');
    
        
        // $table->dropColumn('id');

        $table->updateDate();
        $table->createdDate();
    }

    public function seeder(Seeder $seeder)
    {
        $seeder->definitions('test_1'); 

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