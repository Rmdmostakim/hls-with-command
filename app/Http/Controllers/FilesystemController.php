<?php

namespace App\Http\Controllers;

use App\Jobs\ChainConverter;
use App\Jobs\FileConverter;
use App\Jobs\ThumbCreator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;

use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class FilesystemController extends Controller
{
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


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'bail|required|file'
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        // create the file receiver
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        // receive the file
        $fileReceived = $receiver->receive();
        if ($fileReceived->isFinished()) {
            // get file
            $file = $fileReceived->getFile();
            $dir = Str::random(32);
            $filename = $dir . '.' . $file->extension();
            $filepath = $file->move($dir, $filename);
            $filepath = Str::replace('\\', '/', $filepath);

            // $coverter = new FileConverter($filepath, $dir);
            // $thumb = new ThumbCreator($filepath, $dir);
            $converter = new ChainConverter($filepath);
            \dispatch($converter);
            return response('success', 200);
        }

        $handler = $fileReceived->handler();

        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }




    protected function fileStorage()
    {
    }
}
