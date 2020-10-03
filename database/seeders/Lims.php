<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Lims extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Lims = new \App\Models\Lims([
            'name'=>'Main',
            'url'=> 'http://165.22.27.138/lims',
            'apigetter'=> 'http://165.22.27.138/lims/v1/sample/tests'
        ]);
        $Lims->save();
    }
}
