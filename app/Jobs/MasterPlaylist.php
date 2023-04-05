<?php

namespace App\Jobs;

use App\Models\Feed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class MasterPlaylist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 120;
    public $failOnTimeout = true;
    protected $file, $name, $uuid;
    public function __construct($file, $uuid)
    {
        $this->file = 'public/' . $file;
        $this->uuid = $uuid;
        $this->name =  pathinfo($file, PATHINFO_FILENAME);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $resolutions = [
            "415800" => "426x240",
            "690800" => "854x480",
            "1240800" => "1280x720",
        ];
        $playlist_content = "#EXTM3U\n";
        foreach ($resolutions as $bandwidth => $resolution) {
            $playlist_content .= "#EXT-X-STREAM-INF:BANDWIDTH=$bandwidth,RESOLUTION=$resolution\n";
            $playlist_content .= $this->name . '_' . $resolution . '.m3u8' . "\n";
        }
        $playlist_content .= "#EXT-X-ENDLIST\n";
        $filename = 'public/' . $this->name . '/' . $this->name . '.m3u8';
        Feed::where('uuid', $this->uuid)->update(['src' =>   env('APP_URL') . '/api/watch/' . $this->name . '.m3u8']);
        file_put_contents($filename, $playlist_content);
        File::delete($this->file);
    }
}
