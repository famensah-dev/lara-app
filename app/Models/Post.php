<?php

namespace App\Models;

use App\Events\PostCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content'];


    protected $appends = ['plain_content'];


    protected $dispatchesEvents = [
        'create' => PostCreated::class,
    ];


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            $post->attachments()->delete();

            $post->attachments->each(function ($attachment) {
                Storage::disk('public')->delete($attachment->filepath);
            });
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }


    public function getPlainContentAttribute()
    {
        return strip_tags($this->content);
    }
}
