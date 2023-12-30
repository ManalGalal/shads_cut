<?php

use Illuminate\Support\Facades\Route;
use Mockery\Expectation;

// Add New Api Routes HERE
$routers = [
    "user", "auth", "verify", "admin", "worker", "category",
    "service", "product", "branch", "hidden", "paycut",
    "additive", "indoor", "outdoor", "city", "region", "address",
    "work-day", "coupon", "dayoff", "order", "cancel-reason", "stock",
    "expense-category", "expense", "home", "session", "banner", "app-setting",
    "milestone", "redeem", "support-reason", "support-form", "user-device",
    "notification", "referal", "role", "permission", "module", "branch-module",
    "worker-device", "admin-device", "brand", "payment", "analytic","worker-salary"
];
try {
    foreach ($routers as $router) {
        Route::prefix($router)
            ->name($router . ".")
            ->group(base_path("routes/{$router}.api.php"));
    }
} catch (Expectation $e) {
    return response(["error" => "something went wrong"], 400);
}

Route::get('product/image/{filename}', function($filename)
{
    $filePath = storage_path().'/app/product/image/'.$filename;
    
    if ( ! File::exists($filePath) or ( ! $mimeType = getImageContentType($filePath)))
    {
        return Response::make("File does not exist.", 404);
    }

    $fileContents = File::get($filePath);

    return Response::make($fileContents, 200, array('Content-Type' => $mimeType));
});

Route::get('worker/profile_pictures/{filename}', function($filename)
{
    $filePath = storage_path().'/app/worker/profile_pictures/'.$filename;
    
    if ( ! File::exists($filePath) or ( ! $mimeType = getImageContentType($filePath)))
    {
        return Response::make("File does not exist.", 404);
    }

    $fileContents = File::get($filePath);

    return Response::make($fileContents, 200, array('Content-Type' => $mimeType));
});

function getImageContentType($file)
{
    $mime = exif_imagetype($file);

    if ($mime === IMAGETYPE_JPEG) 
        $contentType = 'image/jpeg';

    elseif ($mime === IMAGETYPE_GIF)
        $contentType = 'image/gif';

    else if ($mime === IMAGETYPE_PNG)
        $contentType = 'image/png';

    else
        $contentType = false;

    return $contentType;
} 