<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'signable_type',
        'signable_id',
        'signature_data',
        'signature_hash',
        'action',
        'comments',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function signable()
    {
        return $this->morphTo();
    }

    public function getActionLabelAttribute()
    {
        return match($this->action) {
            'approved' => 'اعتماد',
            'rejected' => 'رفض',
            'reviewed' => 'مراجعة',
            default => $this->action,
        };
    }

    public static function generateHash($signatureData, $userId, $signableType, $signableId)
    {
        $data = $signatureData . $userId . $signableType . $signableId . now()->timestamp;
        return hash('sha256', $data);
    }
}
