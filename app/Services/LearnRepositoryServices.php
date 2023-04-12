<?php

namespace App\Services;

use App\Models\Learn;
use App\Models\LearnDetail;
use App\Models\LearnLession;
use App\Models\LearnSession;
use App\Repositories\LearnRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Token;

class LearnRepositoryServices implements LearnRepositoryInterface
{
    public function createLearn($credentials, $token)
    {
        $tokenInfo = Token::decode($token);
        DB::beginTransaction();
        try {
            $learn = Learn::create([
                'uuid' => Str::uuid(),
                'instructor_uuid' => $tokenInfo['uuid'],
                'dp_category' => $credentials['category'],
                'title' => $credentials['title'],
                'overview' => $credentials['overview'],
                'slot' => $credentials['slot'],
                'type' => $credentials['type'],
                'level' => $credentials['level'],
                'language' => $credentials['language'],
                'certification' => $credentials['certification']
            ]);
            $details = LearnDetail::create([
                'uuid' => Str::uuid(),
                'learn_uuid' => $learn['uuid'],
                'price' => $credentials['price'],
                'discount' => $credentials['discount'],
                'discount_type' => $credentials['discountType'],
                'discount_duration' => $credentials['discountDuration'],
                'cover' => $credentials['cover'],
                'promo' => $credentials['promo'],

            ]);
            foreach ($credentials['chapter'] as $chapter) {
                $session = LearnSession::create([
                    'uuid' => Str::uuid(),
                    'learn_uuid' => $learn['uuid'],
                    'title' => $chapter['title'],
                    'duration' => $chapter['duration'],
                    'schedule' => $chapter['schedule'],
                ]);
                foreach ($chapter['lesson'] as $lesson) {
                    try {
                        $lesson = LearnLession::create([
                            'session_uuid' => $session['uuid'],
                            'title' => $lesson['title'],
                            'stream_path' => $lesson['streamPath'],
                        ]);
                    } catch (Exception $e) {
                        Log::error($e);
                        DB::rollBack();
                        return response(['error' => 'Failed to create lesson'], 406);
                    }
                }
            }
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response(['error' => 'Failed to create course'], 406);
        }
        return response(['message' => 'Course created successfully'], 201);
    }
    public function learnDetails($credentials)
    {
        try {
            $result = LearnDetail::create([
                'uuid' => Str::uuid(),
                'learn_uuid' => $credentials['learn_uuid'],
                'price' => $credentials['price'],
                'discount' => $credentials['discount'],
                'discount_type' => $credentials['discount_type'],
                'discount_duration' => $credentials['discount_duration'],
                'src' => $credentials['src'],
                'type' => $credentials['type'],
                'thumbnail' => $credentials['thumbnail'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
    }
    public function learnSession($credentials)
    {
        try {
            $result = LearnSession::create([
                'learn_uuid' => $credentials['learn_uuid'],
                'title' => $credentials['title'],
                'duration' => $credentials['duration'],
                'schedule' => $credentials['schedule'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
    }
    public function learnLession($credentials)
    {
        try {
            $result = LearnLession::create([
                'uuid' => Str::uuid(),
                'session_uuid' => $credentials['session_uuid'],
                'title' => $credentials['title'],
                'stream_path' => $credentials['stream_path'],
                'thumbnail' => $credentials['thumbnail'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
    }
}
