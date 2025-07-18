<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ImageHelper
{
    public static function getImageAsBase64($path)
    {
        try {
            // Check if file exists in public path
            $fullPath = public_path($path);

            if (!file_exists($fullPath)) {
                return null;
            }

            // Get file content and mime type
            $imageContent = file_get_contents($fullPath);
            $mimeType = mime_content_type($fullPath);

            // Convert to base64
            $base64 = base64_encode($imageContent);

            return "data:{$mimeType};base64,{$base64}";
        } catch (\Exception $e) {
            Log::error('Error converting image to base64: ' . $e->getMessage());
            return null;
        }
    }
}
