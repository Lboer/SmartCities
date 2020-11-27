<?php

namespace App\Models;

use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;
    use UsesUuid;

    protected $table = "location";

    protected $fillable = [
        "garbage_bin_id",
        "x",
        "y"
    ];

    public function bin() {
        return $this->hasOne('App\Models\GarbageBin', 'id', 'garbage_bin_id');
    }
}
