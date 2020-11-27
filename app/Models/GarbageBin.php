<?php

namespace App\Models;

use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarbageBin extends Model
{
    use HasFactory;

    protected $table = "garbage_bins";

    protected $fillable = [
        "token",
        "lat",
        "lon",
        "last_active_at",
        "name"
    ];

    protected $casts = [
        'last_active_at' => 'datetime'
    ];

    public function values() {
        return $this->hasMany('App\Models\Value');
    }

    public function latestValue()
    {
        return $this->hasOne(Value::class)->latest();
    }

}
