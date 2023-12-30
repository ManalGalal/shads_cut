<?php

namespace App\Models;

use App\Traits\BelongsToBranch;
use App\Traits\PhoneLogin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable {
    use HasFactory, HasApiTokens, PhoneLogin, BelongsToBranch, HasRoles;
    protected $fillable = [
        "name", "email", "phone",
        "role", "password", "monthly_salary",
        "branch_id", "profile_picture", "is_van",
        "lang"
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function admin_devices() {
        return $this->hasMany(AdminDevice::class);
    }
    public function isSuperAdmin() {
        return $this->role === "super" ? true : false;
    }
    /**
     * Refers to branch admins
     */
    public function isBranchAdmin() {
        return $this->role === "normal" ? true : false;
    }
    public function isWorker() {
        return false;
    }
}
