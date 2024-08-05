<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'youtube_feature',
        'youtube_api',
        'rss_feature',
        'youtube_feature_free_tier',
        'rss_feature_free_tier',
        'gpt_4o_mini_credits',
        'languages',
        'default_language',
    ];
}
