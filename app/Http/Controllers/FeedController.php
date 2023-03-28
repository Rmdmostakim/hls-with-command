<?php

namespace App\Http\Controllers;

use Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Token;

class FeedController extends Controller
{
    // store grand category for feed
    public function storeGcat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|unique:feed_g_categories,name',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['name']);
        return Feed::storeGcat($validated);
    }
    // store parent category for feed
    public function storePcat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3',
            'g_category_uuid' => 'bail|required|string|exists:feed_g_categories,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['name', 'g_category_uuid']);

        return Feed::storePcat($validated);
    }
    // create feed
    public function createFeed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'caption' => 'bail|required|string|min:3',
            'p_category_uuid' => 'bail|required|string|exists:feed_p_categories,uuid',
            'products.*' => 'bail|required|string|exists:products,uuid',
            'images.*' => 'bail|nullable|string',
            'video' => 'bail|nullable|string',
            'type' => 'bail|required|max:2',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $token = $request->header('token');
        $validated = $request->only(['caption', 'p_category_uuid', 'products', 'images', 'video', 'type']);
        return Feed::createFeed($validated, $token);
    }
    // get all feed
    public function getAllFeed()
    {
        return Feed::getAllFeed();
    }
    // get all grand category
    public function getAllGcat()
    {
        return Feed::getAllGcat();
    }
    // get all  category
    public function getAllPcat()
    {
        return Feed::getAllPcat();
    }
    // get all search product of merchant
    public function getAllSearchItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'bail|required|string',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['key']);
        return Feed::getAllSearchItems($validated, $token);
    }
    // get all merchant feed
    public function getAllMerchantFeed(Request $request)
    {
        $token = $request->header('token');
        return Feed::getAllFeed($token);
    }
}
