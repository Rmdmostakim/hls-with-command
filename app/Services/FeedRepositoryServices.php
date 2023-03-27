<?php

namespace App\Services;

use App\Jobs\PostCreator;
use App\Models\Feed;
use App\Models\FeedGCategory;
use App\Models\FeedPCategory;
use App\Models\Product;
use App\Repositories\FeedRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Token;

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
    public function createFeed($credentials, $token)
    {
        if (empty($credentials['products'])) {
            $response = ['products' => array('selected products list empty')];
            return response($response, 422);
        }
        if ($credentials['type'] == 0) {
            if (empty($credentials['video'])) {
                $response = ['video' => array('video is not uploaded')];
                return response($response, 422);
            } else {
                $filePath = Str::replace(env('APP_URL') . '/', '', $credentials['video']);
                $exists = File::exists($filePath);
                if (!$exists) {
                    $response = ['video' => array('invalid video url')];
                    return response($response, 422);
                }
            }

            $store = $this->storeFeed($credentials, $token);
            if ($store) {
                $postJob = new PostCreator(Str::replace(env('APP_URL') . '/', '', $credentials['video']), $store->uuid);
                \dispatch($postJob);
                return response('success', 201);
            }
            return response('failed', 406);
        } else {
            return 'image type';
        }
        return response(['message' => 'not accepted'], 406);
    }

    protected function storeFeed($credentials, $token)
    {
        $productIds = Product::whereIn('uuid', $credentials['products'])->pluck('id');
        $tokenInfo = Token::decode($token);
        try {
            $result = Feed::create([
                'uuid' => Str::uuid(),
                'caption' => $credentials['caption'],
                'user_uuid' => $tokenInfo['uuid'],
                'user_type' => 0,
                'feed_p_category_uuid' => $credentials['p_category_uuid'],
                'product_uuid' => json_encode($productIds),
                'type' => $credentials['type'],
                'src' => $credentials['video']
            ]);
            return $result;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }
    // get all feed
    public function getAllFeed()
    {
        return Feed::with(

            'merchant.info:merchant_uuid,company_logo',

            'product:id,uuid,name',

            'product.details:product_uuid,price,cover,stock,discount,discount_type,discount_duration',

            'product.details.cover',

            'gallery',

            'like',

            'comment.userInfo:user_uuid,user_name',

            'comment.profile:user_uuid,path',

            'comment.reply.userInfo:user_uuid,user_name',

            'comment.reply.profile:user_uuid,path',

        )->orderBy('id', 'DESC')->paginate(30);
    }
}
