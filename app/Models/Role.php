<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole {
    use HasFactory, Localizable;
    protected $hidden = ["pivot"];
    protected $localizable = [];
}
