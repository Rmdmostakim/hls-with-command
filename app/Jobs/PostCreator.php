<?php

namespace App\Jobs;

use App\Models\Feed;
use App\Models\Post;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Throwable;

<<<<<<< HEAD
ini_set('memory_limit', '1G');
class PostCreator implements ShouldQueue
=======
class PostCreator implements ShouldQueue, ShouldBeUnique
>>>>>>> sohan
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
        Bus::batch(
            [
                $thumb, $low, $mid, $high, $master,
            ]
        )->then(function (Batch $batch) {
            $this->release();
            // All jobs completed successfully...
            // Feed::where('uuid', $this->uuid)->update(['src' =>   json_encode(env('APP_URL') . '/api/watch/' . $this->name . '.m3u8')]);
        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
            $this->release();
        })->finally(function (Batch $batch) {
            // The batch has finished executing...
            $this->release();
        })->dispatch();
        // Event::listen(JobProcessed::class, function (JobProcessed $event) use ($thumb, $low, $master) {
        //     if ($event->job->resolveName() === get_class($thumb)) {
        //         Feed::where('uuid', $this->uuid)->update(['thumbnail' =>   env('APP_URL') . '/' . $this->name . '/' . $this->name . '.jpg']);
        //     }
        //     if ($event->job->resolveName() === get_class($low)) {
        //         $resolution = '426x240';
        //         Feed::where('uuid', $this->uuid)->update(['src' =>  env('APP_URL') . '/api/watch/' . $this->name . '_' . $resolution . '.m3u8', 'is_active' => 1]);
        //     }
        //     if ($event->job->resolveName() === get_class($master)) {
        //         Feed::where('uuid', $this->uuid)->update(['src' =>   env('APP_URL') . '/api/watch/' . $this->name . '.m3u8']);
        //     }
        // });
    }
}
