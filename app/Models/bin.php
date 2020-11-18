<?php

namespace App\Models;

use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bin extends Model
{
    use HasFactory;
    use UsesUuid;

    protected $table = "garbage_bin";

    protected $fillable = [
        "name",
        "token",
        "temperature",
        "distance"
    ];
}
