<?php

namespace App\Models;

use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    use HasFactory;

    protected $table = "values";

    protected $fillable = [
        "garbage_bin_id",
        'percentage_full',
        "on_fire"
    ];

    public function bin() {
        return $this->hasOne('App\Models\GarbageBin', 'id', 'garbage_bin_id');
    }
}
