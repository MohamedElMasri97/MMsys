<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Lims;
use App\Models\Instrument;

class DymindCBC implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 1;

    protected $id;
    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id,$url)
    {
        $this->id = $id;
        $this->url = $url;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $inst = Instrument::find($this->id);
        $lims = Lims::find(1);
        exec('python ' . base_path('public\\') . $inst->Refinstrument->pythonpath . ' ' . $inst->ip . ' ' . $inst->netport . ' ' . $this->url . ' ' . $inst->id . ' ' . $lims->apigetter . '');
    }
}
