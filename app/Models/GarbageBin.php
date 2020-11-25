<?php

namespace App\Models;

use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarbageBin extends Model
{
    use HasFactory;
    use UsesUuid;

    protected $table = "garbage_bin";

    protected $fillable = [
        "token",
        "lat",
        "lon",
        'percentage_full',
        "on_fire",
        "last_active_at"
    ];

    protected $casts = [
        'last_active_at' => 'datetime'
    ];
}
