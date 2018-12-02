<?php

namespace App\Jobs;

class StatLinkJob extends Job
{

    protected $link;
    protected $stat;

    /**
     * StatLinkJob constructor.
     *
     * @param $link
     * @param $stat - инкремент статистики сокращения (0), получения (1)
     */
    public function __construct($link, $stat)
    {
        $this->link = $link;
        $this->stat= $stat;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ($this->stat==0)? $this->link->increment('stat_in'): $this->link->increment('stat_out');
    }
}
