<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image',
        'link',
        'status',
        'expires_at',
        'user_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'baru')
                     ->where('expires_at', '>', now())
                     ->latest();
    }

    public static function autoArchiveExpired(): void
    {
        static::where('status', 'baru')
              ->where('expires_at', '<=', now())
              ->update(['status' => 'arsip']);
    }

    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }

    public function isArchived(): bool
    {
        return $this->status === 'arsip' || $this->isExpired();
    }

    public function getRemainingTimeAttribute(): string
    {
        if ($this->isExpired()) {
            return 'Sudah berakhir';
        }

        $diff = now()->diff($this->expires_at);

        if ($diff->m > 0) {
            return "{$diff->m} bulan lagi";
        }
        if ($diff->d > 0) {
            return "{$diff->d} hari lagi";
        }
        if ($diff->h > 0) {
            return "{$diff->h} jam lagi";
        }

        return "{$diff->i} menit lagi";
    }

    public function hasImage(): bool
    {
        return !empty($this->image);
    }

    public function hasLink(): bool
    {
        return !empty($this->link);
    }
}