<?php

namespace App\Traits;


trait HttpErrors {
    public function UNAUTHORIZED($message = "UNAUTHORIZED") {
        return response(["message" => $message], 401);
    }
    public function FORBIDDEN($message = "FORBIDDEN") {
        return response(["message" => $message], 403);
    }
    public function NOT_FOUND($message = "NOT FOUND") {
        return response(["message" => $message], 404);
    }
    public function BAD_REQUEST($message = "INVALID OPERATION") {
        return response(["message" => $message], 400);
    }
    public function SERVER_ERROR($message = "Something went wrong") {
        return response(["message" => $message], 500);
    }
}
