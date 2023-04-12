<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Learn;

class LearnController extends Controller
{
    public function createLearn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'bail|required|string|min:3',
            'category' => 'bail|required|string|exists:dp_categories,uuid',
            'certification' => 'bail|required|numeric|min:0|max:1',
            'language' => 'bail|required|string|min:3',
            'type' => 'bail|required|numeric|min:0|max:1',
            'level' => 'bail|required|string|min:3',
            'overview' => 'bail|required|string|min:3',
            'slot' => 'bail|nullable|numeric',

            'price' => 'bail|required|numeric|min:0',
            'discount' => 'bail|nullable|numeric|min:0',
            'discountType' => 'bail|nullable|numeric|max:1|min:0',
            'discountDuration' => 'bail|nullable|string',
            'cover' => 'bail|required|string',
            'promo' => 'bail|required|string',

            'chapter' => 'bail|required|array|min:1',


        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['title', 'category', 'certification', 'language', 'type', 'level', 'overview', 'slot', 'price', 'discount', 'discountType', 'discountDuration', 'cover', 'promo', 'chapter']);

        foreach ($validated['chapter'] as $chapter) {
            if (!$chapter['title']) {
                $response = ['message' => array((int)$validated['type'] == 1 ? 'sesson title field required' : 'chapter title field required')];
                return response($response, 422);
            }
            if (!$chapter['duration']) {
                $response = ['message' => array((int)$validated['type'] == 1 ? 'sesson duration field required' : 'chapter duration field required')];
                return response($response, 422);
            }
            if ((int)$validated['type'] == 1 && !$chapter['schedule']) {
                $response = ['message' => array('sesson schedule field required')];
                return response($response, 422);
            }
            if (empty($validated['cover'])) {
                $response = ['cover' => array('cover is not uploaded')];
                return response($response, 422);
            } else {
                $filePath = Str::replace(env('APP_URL') . '/', '', $validated['cover']);
                $exists = File::exists($filePath);
                if (!$exists) {
                    $response = ['cover' => array('invalid cover url')];
                    return response($response, 422);
                }
            }
            if (empty($validated['promo'])) {
                $response = ['promo' => array('promo is not uploaded')];
                return response($response, 422);
            } else {
                $filePath = Str::replace(env('APP_URL') . '/', '', $validated['promo']);
                $exists = File::exists($filePath);
                if (!$exists) {
                    $response = ['promo' => array('invalid promo url')];
                    return response($response, 422);
                }
            }

            foreach ($chapter['lesson'] as $lesson) {
                if (!$lesson['title']) {
                    $response = ['title' => array('lesson title  required')];
                    return response($response, 422);
                }
                if ((int)$validated['type'] == 0 && empty($lesson['streamPath'])) {
                    $response = ['lesson video' => array('lesson video is not uploaded')];
                    return response($response, 422);
                } else {
                    $filePath = Str::replace(env('APP_URL') . '/', '', $lesson['streamPath']);
                    $exists = File::exists($filePath);
                    if (!$exists) {
                        $response = ['lesson video' => array('invalid lesson video url')];
                        return response($response, 422);
                    }
                }
            }
        }


        return Learn::createLearn($validated, $token);
    }
}
