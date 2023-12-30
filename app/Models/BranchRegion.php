<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchRegion extends Model {
    use HasFactory;
    protected $fillable = ["branch_id", "region_id"];
    protected $hidden = ["created_at", "updated_at"];
}
