<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_start',
        'leave_end',
        'type',
        'reason',
        'status',
    ];

    protected $casts = [
        'leave_start' => 'datetime',
        'leave_end' => 'datetime',
    ];

    /**
     * Get the user that owns the Leave
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
