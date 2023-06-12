<?php

use Haley\Database\Migration\Builder\Builder;
use Haley\Database\Migration\Migration;

return new class
{
    public function up()
    {       
        (new Migration)->up('haley', function (Builder $build) {
            $build->id('id','dfg');
            $build->varchar('mudou')->comment('helo word')->notNull()->unique();
            // $build->varchar('teste');

            $build->rename('test','mudou');
          
            // $build->varchar('varcfghar')->comment('test fdgsdfg')->default('sdgfsdfg sdfgsdfg')->notNull()->unique();
            // $build->varchar('varcfgr')->comment('vc')->default('v dfgdfc');
        });
    }
};
