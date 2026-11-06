<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'category_id',
        'location_id',
        'title',
        'description',
        'urgency',
        'photo_evidence',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ComplaintLog::class)->latest();
    }

    public static function generateTicketNumber(): string
    {
        $date = date('Ymd');
        $prefix = "TKT-{$date}-";
        $lastComplaint = self::where('ticket_number', 'LIKE', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastComplaint) {
            $lastNumber = (int) substr($lastComplaint->ticket_number, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return "{$prefix}{$nextNumber}";
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending / Menunggu Verifikasi',
            'in_progress' => 'Sedang Diproses',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'in_progress' => 'bg-blue-100 text-blue-800 border-blue-300',
            'resolved' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'rejected' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    public function getUrgencyLabelAttribute(): string
    {
        if (!$this->urgency) {
            return 'Menunggu Validasi Admin';
        }

        return match ($this->urgency) {
            'rendah' => 'Rusak Ringan',
            'sedang' => 'Rusak Sedang',
            'tinggi' => 'Rusak Berat',
            'darurat' => 'Sangat Berat / Darurat',
            default => ucfirst($this->urgency),
        };
    }

    public function getUrgencyBadgeClassAttribute(): string
    {
        if (!$this->urgency) {
            return 'bg-slate-100 text-slate-600 border-slate-300';
        }

        return match ($this->urgency) {
            'rendah' => 'bg-emerald-100 text-emerald-800',
            'sedang' => 'bg-amber-100 text-amber-800',
            'tinggi' => 'bg-orange-100 text-orange-800',
            'darurat' => 'bg-rose-100 text-rose-800 animate-pulse',
            default => 'bg-slate-100 text-slate-700',
        };
    }
}
