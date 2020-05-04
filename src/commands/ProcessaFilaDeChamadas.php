<?php

namespace unasp\Console\Commands;

use \unasp\Integrator;
use \unasp\Models\Queue;
use Illuminate\Console\Command;

class ProcessaFilaDeChamadas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integrator:ProcessaFilaDeChamadas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
     * @return mixed
     */
    public function handle()
    {
        Queue::unlocked()
            ->orderBy('date')
            ->get()
            ->each(function($item_queue, $key) {
                $return = Integrator::send($item_queue->action, json_decode($item_queue->data, true));
                
                $item_queue->delete();
                echo $item_queue->id . " - " . $return['status'] . "\n";

                sleep(1);
            });
    }
}
