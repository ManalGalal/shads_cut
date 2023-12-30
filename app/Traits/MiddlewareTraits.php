<?php

namespace App\Traits;


trait MiddlewareTraits {
    public function isUserSuperAdmin($user) {
        return get_class($user) === "App\Models\Admin" && $user->role === "super";
    }
    public function isWorker($user) {
        return get_class($user) === "App\Models\Worker";
    }
}
