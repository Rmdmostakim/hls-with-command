<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ThumbCreator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 300;
    public $failOnTimeout = true;

    protected $file, $name, $postUuid;
    public function __construct($file)
    {
        $this->file = 'public/' . $file;
        $this->name =  pathinfo($file, PATHINFO_FILENAME);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filename = 'public/' . $this->name . '/' . $this->name . '.jpg';
        $command = "ffmpeg -i $this->file -ss 00:00:05 -s 720x1280 -vframes 1 $filename";
        shell_exec($command);
    }
}
