<?php

namespace App\Services;

use Token;
use Exception;
use App\Models\Feed;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FeedGCategory;
use App\Models\FeedPCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Repositories\FeedRepositoryInterface;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

class FeedRepositoryServices implements FeedRepositoryInterface
{
    // store grand category for feed
    public function storeGcat($credentials)
    {
        try {
            $result = FeedGCategory::create([
                'uuid' => Str::uuid(),
                'name' => $credentials['name'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 201);
        }
        return response(['message' => 'not accepted'], 406);
    }
    // store parent category for feed
    public function storePcat($credentials)
    {

        try {
            $result = FeedPCategory::create([
                'uuid' => Str::uuid(),
                'name' => $credentials['name'],
                'g_category_uuid' => $credentials['g_category_uuid'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success'], 201);
        }
        return response(['message' => 'not accepted'], 406);
    }
    // create feed
    public function createFeed($credentials, $token, $images, $video, Request $request)
    {
        if (empty($credentials['products'])) {
            $response = ['products' => array('selected products list empty')];
            return response($response, 422);
        }
        if ($credentials['type'] == 0) {
            // create the file receiver
            $receiver = new FileReceiver("video", $request, HandlerFactory::classFromRequest($request));
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

                // $converter = new PostCreator($filepath);
                // \dispatch($converter);
                return response('success', 200);
            }

            $handler = $fileReceived->handler();

            return response()->json([
                "done" => $handler->getPercentageDone(),
                'status' => true
            ]);
        } else {
            // to do code
        }


        $tokenInfo = Token::decode($token);


        // $src = array();
        // foreach ($images as $attachment) {
        //     $path = FileSystem::storeFile($attachment, 'feed/attachment');
        //     array_push($src, $path);
        // }
        // $thumb = FileSystem::storeFile($thumbnail, 'feed/thumbnail');
        try {
            $result = Feed::create([
                'uuid' => Str::uuid(),
                'name' => $validated['name'],
                'user_uuid' => $tokenInfo['uuid'],
                'user_type' => $tokenInfo['type'],
                'feed_p_category_uuid' => $validated['p_category_uuid'],
                'type' => $validated['type'],
                'src' => json_encode($src),
                'thumbnail' => json_encode($thumb),
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'created'], 201);
        }
        return response(['message' => 'not accepted'], 406);
    }
}
