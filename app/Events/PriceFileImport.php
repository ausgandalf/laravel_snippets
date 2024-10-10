<?php
 
namespace App\Events;
 
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\general\BackgroundJob;

class PriceFileImport
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * The background job instance.
     *
     * @var \App\Models\general\BackgroundJob
     */
    public $background_job;
    /**
     * Create a new event instance.
     *
     * @param  App\Models\general\BackgroundJob
     * @return void
     */
    public function __construct(BackgroundJob $background_job)
    {
        $this->background_job = $background_job;
    }
}