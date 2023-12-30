<?php

namespace App\Models;

use App\Traits\HasProfilePicture;
use App\Traits\PhoneLogin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, PhoneLogin, HasProfilePicture;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected static function booted() {
        /**
         * @api on model creation.. add permissions to that module instead of writting a migration
         */
        static::retrieved(function ($user) {
            $user->membership();
        });
    }
    protected $fillable = [
        'name',
        'email',
        "phone",
        "birth_date",
        'password',
        "profile_picture",
        "wallet",
        "fb_id",
        "google_id",
        "apple_id",
        "points",
        "shads",
        "status",
        "source",
        "membership",
        "lang"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        "fb_id",
        "google_id",
        "apple_id"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function profile() {
        return [
            "name" => $this->name, "email" => $this->email, "wallet" => $this->wallet,
            "phone" => $this->phone, "profile_picture" => $this->profile_picture,
            "points" => $this->points, "birth_date" => $this->birth_date,
            "points_from_milestone" => Milestone::where("user_id", $this->id)->sum("points"),
            "membership" => $this->membership(), "membership_coupons" => $this->membership_coupons(),
            "shads" => $this->shads, "shads_discount" => $this->shads_discount(),
            "lang" => $this->lang
        ];
    }
    public function orders() {
        return $this->hasMany(Order::class, "user_id");
    }
    public function milestones() {
        return $this->hasMany(Milestone::class, "user_id");
    }
    public function user_devices() {
        return $this->hasMany(UserDevice::class, "user_id");
    }
    public function notifications() {
        return $this->hasMany(Notification::class, "user_id");
    }
    public function addresses() {
        return $this->hasMany(Address::class);
    }
    public function referal_codes() {
        return $this->hasMany(ReferalCode::class);
    }
    public function redeem_history() {
        return $this->hasMany(RedeemHistory::class);
    }
    public function membership() {
        $total_points = $this->milestones()->sum("points");
        $memberships = [
            "PLAT",
            "GOLD",
            "SILVER"
        ];
        foreach ($memberships as $membership) {
            $points_to_membership = AppSetting::where("name", "POINTS_TO_REACH_$membership")
                ->first();
            if ($points_to_membership && $points_to_membership->value <= $total_points) {
                $this->membership = $membership;
                $this->saveQuietly();
                return $membership;
            }
        }
        $this->membership = "BASIC";
        $this->saveQuietly();
        return "BASIC";
    }
    public function membership_coupons() {
        return Coupon::select(["code", "expires_at", "usages_per_user"])
            ->where("membership", $this->membership())
            ->orWhere("membership", "ALL")
            ->active()
            ->get();
    }
    public function shads_discount() {
        if ($this->shads) {
            return Coupon::where("code", "SHADS")
                ->select(["type", "value"])
                ->first();
        }
    }
}
