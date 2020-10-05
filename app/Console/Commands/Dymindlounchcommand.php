<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Instrument;
use App\Models\Lims;

class DymindLounchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DymindLounchCommand {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lounches dymind file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        $inst = Instrument::find($id);
        if($inst){
            $lims = Lims::find(1);
            exec ('python ' . base_path('public\\') . $inst->Refinstrument->pythonpath . ' ' . $inst->ip . ' ' . $inst->netport . ' ' . asset('api') . ' ' . $inst->id . ' ' . $lims->apigetter . '');
        }else{
            $this->info('the id of the instrument is not valid');
        }
    }
}
