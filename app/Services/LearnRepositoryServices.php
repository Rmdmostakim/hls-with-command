<?php

namespace App\Services;

use Token;
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
    public function createLearn($credentials, $token)
    {
        $tokenInfo = Token::decode($token);
        try {
            $result = Learn::create([
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
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            try {
                $result = LearnDetail::create([
                    'uuid' => Str::uuid(),
                    'learn_uuid' => $result['uuid'],
                    'price' => $credentials['price'],
                    'discount' => $credentials['discount'],
                    'discount_type' => $credentials['discountType'],
                    'discount_duration' => $credentials['discountDuration'],
                    'cover' => $credentials['cover'],
                    'promo' => $credentials['promo'],

                ]);
            } catch (Exception $e) {
                Log::error($e);
                $result = false;
                Learn::where('uuid', $result['uuid'])->delete();
            }
        }

        if ($result) {
            foreach ($credentials['chapter'] as $chapter) {
                try {
                    $result = LearnSession::create([
                        'uuid' => Str::uuid(),
                        'learn_uuid' => $result['learn_uuid'],
                        'title' => $chapter['title'],
                        'duration' => $chapter['duration'],
                        'schedule' => $chapter['schedule'],
                    ]);
                } catch (Exception $e) {
                    Log::error($e);
                    Learn::where('uuid', $result['learn_uuid'])->delete();
                    LearnDetail::where('learn_uuid', $result['learn_uuid'])->delete();
                    $result = false;
                    break;
                }
            }
        }
        if ($result) {
            return $result;
        }
        // if ($result) {
        //     foreach ($credentials['chapter'] as $chapter) {
        //         try {
        //             $result = LearnSession::create([
        //                 'learn_uuid' => $result['learn_uuid'],
        //                 'title' => $chapter['title'],
        //                 'duration' => $chapter['duration'],
        //                 'schedule' => $chapter['schedule'],
        //             ]);
        //             foreach ($chapter['lesson'] as $lesson) {
        //                 $result = LearnLession::create([
        //                     'uuid' => Str::uuid(),
        //                     'session_uuid' => $result['uuid'],
        //                     'title' => $lesson['title'],
        //                     'stream_path' => $lesson['streamPath'],
        //                 ]);
        //             }
        //         } catch (Exception $e) {
        //             Log::error($e);
        //             $result = false;
        //             Learn::where('uuid', $result['learn_uuid'])->delete();
        //             LearnDetail::where('learn_uuid', $result['learn_uuid'])->delete();
        //             return response('not acceptable', 406);
        //         }
        //     }
        // }
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
