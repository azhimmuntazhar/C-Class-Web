<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'user_id', 'course_key', 'title', 'description', 'category',
        'material_link', 'submission_link', 'starts_at', 'deadline_at'
    ];

    protected $casts = [
        'starts_at'   => 'datetime',
        'deadline_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // AUTO STATUS: Expired jika sekarang > deadline
    public function getIsExpiredAttribute(): bool
    {
        return now()->greaterThan($this->deadline_at);
    }

    // Ambil nama mata kuliah dari config
    public function getCourseNameAttribute(): string
    {
        return config("roles.courses.{$this->course_key}") ?? ucfirst(str_replace('_', ' ', $this->course_key));
    }
}