<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission {
    use HasFactory, Localizable;
    protected $hidden = ["pivot"];
    protected $localizable = [];
}
