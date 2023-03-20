<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class FileConverter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $file, $filename;
    public function __construct($file, $name)
    {
        $this->file = 'public/' . $file;
        // $this->filename = pathinfo($file, PATHINFO_FILENAME);
        $this->filename = $name;
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
            $filename = 'public/' . $this->filename . '/' . $this->filename . '_' . $resolution . '.m3u8';
            $playlist_content .= "#EXT-X-STREAM-INF:BANDWIDTH=$bandwidth,RESOLUTION=$resolution\n";
            $playlist_content .= $this->filename . '_' . $resolution . '.m3u8' . "\n";
            $command = "ffmpeg -i $this->file -profile:v baseline -level 3.0 -s $resolution -start_number 0 -hls_time 10 -hls_list_size 0 -f hls $filename";
            shell_exec($command);
        }
        $playlist_content .= "#EXT-X-ENDLIST\n";
        $playlist = 'public/' . $this->filename . '/' . $this->filename . '.m3u8';
        file_put_contents($playlist, $playlist_content);
        File::delete($this->file);
    }
}
