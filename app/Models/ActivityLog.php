<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional jika namanya sudah sesuai jamak Plural)
    protected $table = 'activity_logs';

    // 🌿 Menjaga kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'user_id',
        'activity',
        'table_name',
        'record_id',
        'details',
        'ip_address'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}