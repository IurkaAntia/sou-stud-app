<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ExpoToken;
use App\Services\ExpoNotificationService;
use Illuminate\Support\Facades\Log;

class Subject extends Model
{
    protected $fillable = ["users_id", "title", "grade"];

    public function user()
    {
        return $this->belongsTo(User::class, "users_id", "id");
    }

    protected static function boot()
    {
        parent::boot();

        Log::info("Subject model boot method called.");

        static::updated(function ($subject) {
            \Log::info("Grade updated for subject: {$subject->title}");
            if ($subject->isDirty("grade")) {
                $expoService = new ExpoNotificationService();
                $tokens = ExpoToken::where("users_id", $subject->users_id)
                    ->pluck("expo_token")
                    ->toArray();

                $message = [
                    'title' => 'შეფასების ცვლილება',
                    'body' => "'{$subject->title}'-ში შეფასება განახლდა. თვენი ქულაა:  {$subject->grade}."
                ];

                $expoService->sendNotification($tokens, $message);
            }
        });
    }
}
