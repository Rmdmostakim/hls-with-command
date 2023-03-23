<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PostCreator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 3600 * 5;
    public $failOnTimeout = true;
    protected $file;
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()

    {
        $thumb =  new ThumbCreator($this->file);
        $low =  new ConvertLowQuality($this->file);
        $mid =  new ConvertMidQuality($this->file);
        $high =  new ConvertHighQuality($this->file);
        $master = new MasterPlaylist($this->file);
        Bus::chain(
            [
                $thumb, $low, $mid, $high, $master,
            ]
        )->dispatch();
        Event::listen(JobProcessed::class, function (JobProcessed $event) use ($low, $master) {
            if ($event->job->resolveName() === get_class($low)) {
                Post::where('id', 1)->update(['playlist' => 'low']);
            }
            if ($event->job->resolveName() === get_class($master)) {
                Post::where('id', 1)->update(['playlist' => 'master']);
            }
        });
    }
}
