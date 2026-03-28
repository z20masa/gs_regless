<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regret extends Model
{
    use HasFactory;

    // ここが命です。1つでも綴りが違うと、その項目は捨てられます。
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'category',
        'cognitive',
        'emotional',
        'temporal',
        'social',
        'behavioral',
        'existential',
        'ai_letter',      // これ
        'is_burned',      // これ
        'image_url_1',    // これ（5まで）
        'image_url_2',
        'image_url_3',
        'image_url_4',
        'image_url_5',
        'message_to_others',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}