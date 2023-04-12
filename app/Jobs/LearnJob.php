<?php

namespace App\Jobs;

use App\Models\Learn;
use App\Models\LearnDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LearnJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600 * 24;
    public $failOnTimeout = false;
    protected $file, $name, $uuid;

    public function __construct($file, $uuid)
    {
        $this->file = 'public/' . $file;
        $this->uuid = $uuid;
        $this->name = pathinfo($file, PATHINFO_FILENAME);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FilesystemAdapter $filesystem)
    {
        $resolutions = [
            "240x426",
            "480x854",
            "720x1280",
        ];

        foreach ($resolutions as $resolution) {
            $this->transcodeVideo($filesystem, $resolution);
        }

        $playlist_content = "#EXTM3U\n";
        foreach ($resolutions as $i => $resolution) {
            $bandwidth = 500 * ($i + 1) . "000";
            $playlist_content .= "#EXT-X-STREAM-INF:BANDWIDTH=$bandwidth,RESOLUTION=$resolution\n";
            $playlist_content .= $this->name . "_$resolution.m3u8" . "\n";
        }
        $playlist_content .= "#EXT-X-ENDLIST\n";

        $filename = 'public/' . $this->name . '/' . $this->name . '.m3u8';
        $myarray = env('APP_URL') . '/api/watch/' . $this->name . '.m3u8';

        Learn::where('uuid', $this->uuid)->update(['approved' => 1]);
        LearnDetail::where('learn_uuid', $this->uuid)->update(['src' => $myarray]);

        $filesystem->put($filename, $playlist_content);
        $filesystem->delete($this->file);
    }

    private function transcodeVideo(FilesystemAdapter $filesystem, $resolution)
    {
        $filename = "public/{$this->name}/{$this->name}_{$resolution}.m3u8";
        $command = "ffmpeg -i {$this->file} -profile:v baseline -level 3.0 -r 30 -s {$resolution} -start_number 0 -b:v 500k -maxrate 500k -bufsize 1000k -hls_time 10 -hls_list_size 0 -f hls $filename";
        exec($command);
    }
}
