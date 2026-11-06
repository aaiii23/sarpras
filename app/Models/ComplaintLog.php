<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'user_id',
        'status_from',
        'status_to',
        'notes',
        'photo_resolved',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusToLabelAttribute(): string
    {
        return match ($this->status_to) {
            'pending' => 'Pending / Menunggu',
            'in_progress' => 'Sedang Diproses',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status_to),
        };
    }
}
