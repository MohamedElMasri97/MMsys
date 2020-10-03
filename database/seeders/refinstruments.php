<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class refinstruments extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $instrument = new \App\Models\Refinstrument([
            'name' => 'CBC-DF50CRP',
            'company' => 'Dymind',
            'command'=> 'Dymind',
            'protocol' => 'HL7',
            'commtype' => 'NET',
            'pythonpath' => 'python/dymind.py',
            'imagepath' => 'images/dymind.png',
            'defport' =>'5112',
            'information'=> "DF50
                            5-Part Auto Hematology Analyzer
                            Mini 5-Part Auto Hematology Analyzer is applicable to the customers who hâve less daily sample size and préparé to upgrade from 3-part into 5-part analyzer. This ananlyzer is in compact size and is equipped with 10.4 inch color LCD touch screen, and can be operated without extemal printer, thereby saving the laboratory space, greatly reducing the instrument's cost of use, and meeting the application requirements of the target customers.
                            Accurate and compact, your first choice for upgrade
                            The différentiation results are more précisé due to double optical channels
                            Low sample volume, saving up the reagents
                            Low blood consumption, and less varieties of reagents, ensuring the accuracy of test results while saving the cost of reagents. Ultra-low blood consumption, particularly suitable for pédiatrie and gériatrie samples.
                            Touch screen, compact design
                            The instrument with 10.4 inch color LCD touch screen is in small size with simple design and easy operation, and can be operated without any extemal computer, thereby greatly saving the laboratory space.
                            High quality, easy maintenance
                            Ail key components are imported, high qualily, longer life and easy maintenance."
        ]);
        $instrument->save();
    }
}
