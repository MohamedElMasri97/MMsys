<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Instrument;
use App\Models\Lims;

class Lounch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Dymind {id}';

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
        $lims = Lims::find(1);
        exec ('python ' . base_path('public\\') . $inst->Refinstrument->pythonpath . ' ' . $inst->ip . ' ' . $inst->port . ' ' . asset('api') . ' ' . $inst->id . ' ' . $lims->apigetter . '');
    }
}
