<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'category',
        'status',
        'reporter_name',
        'reporter_email',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isBug(): bool
    {
        return $this->category === 'bug';
    }

    public function isSaran(): bool
    {
        return $this->category === 'saran';
    }
}