<?php

namespace App\Listeners;

use App\Events\LogEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogListener implements ShouldQueue
{
    use Queueable, SerializesModels, Dispatchable;

    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param LogEvent $event
     */
    public function handle(LogEvent $event)
    {
        (config('app.log_write'))? Log::info([$event->title,$event->link,$event->request]): false;
    }
}
