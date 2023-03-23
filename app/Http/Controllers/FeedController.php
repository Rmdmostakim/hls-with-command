<?php

namespace App\Http\Controllers;

use Token;
use Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'images.*' => 'bail|nullable|image|mimes:jpg,jpeg,webp,png',
            'video' => 'bail|nullable|file|mimes:mp4,mov,mkv',
            'type' => 'bail|required|max:2',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $images = $request->file('images');
        $video = $request->file('video');
        $token = $request->header('token');
        $validated = $request->only(['caption', 'p_category_uuid', 'products', 'type']);
        return Feed::createFeed($validated, $token, $images, $video, $request);
    }
}
