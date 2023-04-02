<?php

namespace App\Http\Controllers;

use Learn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LearnController extends Controller
{
    public function createLearn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dp_category' => 'bail|required|string|exists:dp_categories,uuid',
            'title' => 'bail|required|string|min:3',
            'overview' => 'bail|required|string|min:3',
            'slot' => 'bail|required|numeric',
            'type' => 'bail|required|numeric',
            'level' => 'bail|required|string',
            'language' => 'bail|required|string|min:3',
            'certification.*' => 'bail|required|mimes:jpg,jpeg,png,webp,pdf',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['dp_category', 'title', 'overview', 'slot', 'type', 'level', 'language', 'certification']);
        return Learn::createLearn($validated);
    }
    public function learnDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'learn_uuid' => 'bail|required|string|exists:learns,uuid',
            'price' => 'bail|required|string',
            'discount' => 'bail|required|string',
            'discount_type' => 'bail|required',
            'discount_duration' => 'bail|required',
            'type' => 'bail|required|string',
            'src.*' => 'bail|required',

        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['learn_uuid', 'price', 'discount', 'discount_type', 'discount_duration', 'type', 'src']);
        return Learn::learnDetails($validated);
    }
    public function learnSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'learn_uuid' => 'bail|required|string|exists:learns,uuid',
            'duration' => 'bail|required|string',
            'title' => 'bail|required|string',
            'schedule' => 'bail|required',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['learn_uuid', 'duration', 'title', 'schedule']);
        return Learn::learnSession($validated);
    }
    public function learnLession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_uuid' => 'bail|required|string|exists:learns,uuid',
            'title' => 'bail|required|string',
            'stream_path' => 'bail|required|string',
            'thumbnail' => 'bail|required',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['session_uuid', 'title', 'stream_path', 'thumbnail']);
        return Learn::learnLession($validated);
    }
}
