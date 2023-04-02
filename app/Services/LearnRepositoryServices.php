<?php

namespace App\Services;

use Exception;
use App\Models\Learn;
use App\Models\LearnDetail;
use App\Models\LearnLession;
use App\Models\LearnSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Repositories\LearnRepositoryInterface;

class LearnRepositoryServices implements LearnRepositoryInterface
{
    public function createLearn($credentials)
    {
        try {
            $result = Learn::create([
                'uuid' => Str::uuid(),
                'instructor_uuid' => $credentials['instuctor_uuid'],
                'dp_category' => $credentials['dp_category'],
                'title' => $credentials['title'],
                'overview' => $credentials['overview'],
                'slot' => $credentials['slot'],
                'type' => $credentials['type'],
                'level' => $credentials['level'],
                'language' => $credentials['language'],
                'certification' => $credentials['certification']
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
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
