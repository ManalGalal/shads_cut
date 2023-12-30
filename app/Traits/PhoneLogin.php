<?php

namespace App\Traits;


trait PhoneLogin { 
    public function findForPassport($phone) {
        return $this->where('phone', $phone)->first();
    }
}