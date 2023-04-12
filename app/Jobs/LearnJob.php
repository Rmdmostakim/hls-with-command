<?php

namespace App\Jobs;

use App\Models\Learn;
use App\Models\LearnDetail;
use App\Models\LearnLession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class LearnJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600 * 24;
    public $failOnTimeout = false;
    protected $file, $name, $uuid, $table;

    public function __construct($file, $uuid, $table)
    {
        $this->file = 'public/' . $file;
        $this->uuid = $uuid;
        $this->table = $table;
        $this->name = pathinfo($file, PATHINFO_FILENAME);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $resolution = "240x426";
        $filename = 'public/' . $this->name . '/' . $this->name . '_' . $resolution . '.m3u8';
        $command = "ffmpeg -i $this->file -profile:v baseline -level 3.0 -r 30 -s $resolution -start_number 0 -b:v 500k -maxrate 500k -bufsize 1000k -hls_time 10 -hls_list_size 0 -f hls $filename";
        shell_exec($command);

        $resolution = "480x854";
        $filename = 'public/' . $this->name . '/' . $this->name . '_' . $resolution . '.m3u8';
        $command = "ffmpeg -i $this->file -profile:v baseline -level 3.0 -r 30 -s $resolution -start_number 0 -b:v 500k -maxrate 500k -bufsize 1000k -hls_time 10 -hls_list_size 0 -f hls $filename";
        shell_exec($command);

        $resolution = "720x1280";
        $filename = 'public/' . $this->name . '/' . $this->name . '_' . $resolution . '.m3u8';
        $command = "ffmpeg -i $this->file -profile:v baseline -level 3.0 -r 30 -s $resolution -start_number 0 -b:v 500k -maxrate 500k -bufsize 1000k -hls_time 10 -hls_list_size 0 -f hls $filename";
        shell_exec($command);

        $resolutions = [
            "415800" => "240x426",
            "690800" => "480x854",
            "1240800" => "720x1280",
        ];
        $playlist_content = "#EXTM3U\n";
        foreach ($resolutions as $bandwidth => $resolution) {
            $playlist_content .= "#EXT-X-STREAM-INF:BANDWIDTH=$bandwidth,RESOLUTION=$resolution\n";
            $playlist_content .= $this->name . '_' . $resolution . '.m3u8' . "\n";
        }
        $playlist_content .= "#EXT-X-ENDLIST\n";
        $filename = 'public/' . $this->name . '/' . $this->name . '.m3u8';
        $myarray = env('APP_URL') . '/api/watch/' . $this->name . '.m3u8';
        if ($this->table == 'chapter') {
            LearnDetail::where('learn_uuid', $this->uuid)->update(['promo' => $myarray]);
        }
        if ($this->table == 'lesson') {
            LearnLession::where('session_uuid', $this->uuid)->update(['stream_path' => $myarray]);
        }
        file_put_contents($filename, $playlist_content);
        File::delete($this->file);
    }
}
