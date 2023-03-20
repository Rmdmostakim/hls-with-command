<?php

namespace App\Http\Controllers;

use App\Jobs\FileConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FilesystemController extends Controller
{
    public function store(Request $request)
    {
        $file = $request->file('video');
        $dir = Str::random(32);
        $filename = $dir . '.' . $file->extension();
        $filepath = $file->move($dir, $filename);
        $filepath = Str::replace('\\', '/', $filepath);

        $coverter = new FileConverter($filepath, $dir);
        \dispatch($coverter);
        return response('success', 200);
    }

    public function watch($playlist)
    {
        $headers = [
            'Content-Type' => 'audio/x-mpegurl',
        ];
        $filepath =  pathinfo($playlist, PATHINFO_FILENAME);
        if (strpos($filepath, "_") == true) {
            $filepath = strstr($filepath, '_', true);
        }
        $path = public_path($filepath . '/' . $playlist);
        return response()->download($path, $playlist, $headers);
    }

    public function accessFile($file)
    {
        $headers = [
            'Content-Type' => 'audio/x-mpegurl',
        ];
        return response()->download($file, $file, $headers);
    }
}
