<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchModule extends Model {
    use HasFactory;
    protected $fillable = ["name"];

    public function original_module() {
        return Module::where("name", $this->name)->first();
    }
}
