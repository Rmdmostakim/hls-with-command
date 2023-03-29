<?php

namespace App\Services;

use Token;
use Exception;
use FileSystem;
use App\Models\Feed;
use App\Models\Product;
use App\Jobs\PostCreator;
use App\Models\FeedComment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FeedGCategory;
use App\Models\FeedLike;
use App\Models\FeedPCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
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
        } else {
            foreach ($credentials['images'] as $image) {
                $filePath = Str::replace(env('APP_URL') . '/', '', $image);
                $exists = File::exists($filePath);
                if (!$exists) {
                    $response = ['images' => array('invalid images url')];
                    return response($response, 422);
                }
            }
            $store = $this->storeFeed($credentials, $token);
            if ($store) {
                return response('success', 201);
            }
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
                'product_uuid' => $productIds,
                'type' => $credentials['type'],
                'src' => $credentials['type'] == 0 ? $credentials['video'] : json_encode($credentials['images']),
                'is_active' => $credentials['type'] == 0 ? 0 : 1,
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
            'like',

            'comment.userInfo:user_uuid,user_name',

            'comment.profile:user_uuid,path',

            'comment.reply.userInfo:user_uuid,user_name',

            'comment.reply.profile:user_uuid,path',
        )->orderBy('id', 'DESC')->paginate(10);
    }
    // get all g cat 
    public function getAllGcat()
    {
        return FeedGCategory::with('category')->get();
    }
    // get all  category
    public function getAllPcat()
    {
        return FeedPCategory::all();
    }
    // get all merchant feed
    public function  getAllMerchantFeed($token)
    {
        $tokenInfo = Token::decode($token);
        return Feed::where('user_uuid', $tokenInfo['uuid'])->with(
            'product:id,uuid,name',
            'product.details:product_uuid,price,cover,stock,discount,discount_type,discount_duration',
            'product.details.cover',
        )->orderBy('id', 'DESC')->paginate(10);
    }
    // get all search product of merchant
    public function  getAllSearchItems($credentials, $token)
    {
        $tokenInfo = Token::decode($token);
        $products = Product::where('merchant_uuid', $tokenInfo['uuid'])->where('name', 'like', '%' . $credentials['key'] . '%')->select('name', 'uuid')->with('details:product_uuid,price,cover,stock,discount,discount_type,discount_duration', 'details.cover')->orderBy('id', 'DESC')->get();
        return response($products, 200);
    }
    // increase feed view
    public function increaseView($validated)
    {

        $exists = Feed::where('uuid', $validated)->first();

        if ($exists) {

            $views = $exists->views;

            $exists->views = $views + 1;

            $result =  $exists->update();

            if ($result) {

                return response(['message' => 'success'], 202);
            }
        }

        return response(['message' => 'not found'], 406);
    }
    // increase feed share
    public function increaseShare($credentials)
    {

        $exists = Feed::where('uuid', $credentials['uuid'])->first();

        if ($exists) {

            $share = $exists->share;

            $exists->share = $share + 1;

            $result =  $exists->update();

            if ($result) {

                return response(['message' => 'updated'], 202);
            }
        }

        return response(['message' => 'not found'], 406);
    }
    // store feed like
    public function storeFeedLike($credentials, $token)
    {
        $tokenInfo = Token::decode($token);
        $exists = FeedLike::where('feed_uuid', $credentials['feed_uuid'])->where('user_uuid', $tokenInfo['uuid'])->first();
        if ($exists) {
            $result = FeedLike::where('feed_uuid', $credentials['feed_uuid'])->where('user_uuid', $tokenInfo['uuid'])->delete();
        } else {
            $result = FeedLike::create([
                'uuid' => Str::uuid(),
                'feed_uuid' => $credentials['feed_uuid'],
                'user_uuid' => $tokenInfo['uuid'],
            ]);
        }
        if ($result) {
            return response($result, 201);
        }
        return response('failed', 406);
    }
    // store feed comment
    public function storeFeedComment($credentials, $token, $file)
    {

        $tokenInfo = Token::decode($token);

        $path = null;

        if ($file) {

            $path = FileSystem::storeFile($file, 'comment/attachtment');
        }

        try {

            $result = FeedComment::create([

                'uuid' => Str::uuid(),

                'feed_uuid' => $credentials['feed_uuid'],

                'user_uuid' => $tokenInfo['uuid'],

                'parent_uuid' => $credentials['parent_uuid'],

                'comment' => $credentials['comment'],

                'attachment' => $path,

            ]);

            $result = FeedComment::where('uuid', $result->uuid)->with('userInfo:user_uuid,user_name', 'profile:user_uuid,path')->first();
        } catch (Exception $e) {

            return $e;

            Log::error($e);

            $result = false;
        }

        if ($result) {

            return response($result, 201);
        }

        $deleteFile = FileSystem::deleteFile($path);

        return response(['message' => 'not acceptable'], 406);
    }
    // update feed comment
    public function updateFeedComment($credentials, $token)
    {

        $tokenInfo = Token::decode($token);

        try {

            $result = FeedComment::where('uuid', $credentials['uuid'])->where('user_uuid', $tokenInfo['uuid'])->update([

                'comment' => $credentials['comment'],

            ]);
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            $updateData = FeedComment::where('uuid', $credentials['uuid'])->where('user_uuid', $tokenInfo['uuid'])->first();

            return response(
                $updateData,
                201
            );
        }

        // $deleteFile = FileSystem::deleteFile($path);

        return response(['message' => 'not acceptable'], 406);
    }
    // delete feed comment
    public function deleteFeedComment($credentials, $token)
    {

        $tokenInfo = Token::decode($token);

        try {

            $result = FeedComment::where('user_uuid', $tokenInfo['uuid'])->where('uuid', $credentials['uuid'])->orWhere('parent_uuid', $credentials['uuid'])->delete();
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            return response(['message' => 'deleted'], 410);
        }


        return response(['message' => 'not acceptable'], 406);
    }
}
