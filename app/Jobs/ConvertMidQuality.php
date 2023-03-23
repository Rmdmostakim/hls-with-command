<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertMidQuality implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 3600 * 2;
    public $failOnTimeout = true;
    protected $file, $name;
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
        $resolution = '854x480';
        $filename = 'public/' . $this->name . '/' . $this->name . '_' . $resolution . '.m3u8';
        $command = "ffmpeg -i $this->file -profile:v baseline -level 3.0 -r 30 -s $resolution -start_number 0 -b:v 1000k -maxrate 1000k -bufsize 2000k -hls_time 10 -hls_list_size 0 -f hls $filename";
        shell_exec($command);
    }
}
