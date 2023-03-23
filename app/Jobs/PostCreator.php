<?php

namespace App\Jobs;

use App\Models\Feed;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;

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
    protected $file, $uuid, $name;
    public function __construct($file, $uuid)
    {
        $this->file = $file;
        $this->uuid = $uuid;
        $this->name = pathinfo($file, PATHINFO_FILENAME);
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
        Event::listen(JobProcessed::class, function (JobProcessed $event) use ($thumb, $low, $master) {
            if ($event->job->resolveName() === get_class($thumb)) {
                Feed::where('uuid', $this->uuid)->update(['thumbnail' =>   env('APP_URL') . '/' . $this->name . '/' . $this->name . '.jpg']);
            }
            if ($event->job->resolveName() === get_class($low)) {
                $resolution = '426x240';
                Feed::where('uuid', $this->uuid)->update(['src' =>  env('APP_URL') . '/api/watch/' . $this->name . '_' . $resolution . '.m3u8']);
            }
            if ($event->job->resolveName() === get_class($master)) {
                Feed::where('uuid', $this->uuid)->update(['src' =>   env('APP_URL') . '/api/watch/' . $this->name . '.m3u8']);
            }
        });
    }
}
