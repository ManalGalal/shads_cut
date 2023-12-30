<?php

namespace App\Models;

use App\Traits\AddUrlToImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model {
    use HasFactory, AddUrlToImage, SoftDeletes;
    protected $fillable = ["image", "link", "featured"];
}
