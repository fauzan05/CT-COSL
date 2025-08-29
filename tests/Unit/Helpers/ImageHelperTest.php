<?php

namespace Tests\Unit\Helpers;

use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ImageHelperTest extends TestCase
{
    public function test_get_image_as_base64_returns_base64_string_for_valid_image()
    {
        // Create a temporary test image file
        $testImagePath = public_path('test-image.png');
        
        // Create a simple 1x1 PNG image
        $imageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAHUAfJ4SQAAAABJRU5ErkJggg==');
        file_put_contents($testImagePath, $imageData);

        $result = ImageHelper::getImageAsBase64('test-image.png');

        // Clean up test file
        if (file_exists($testImagePath)) {
            unlink($testImagePath);
        }

        $this->assertNotNull($result);
        $this->assertStringStartsWith('data:image/png;base64,', $result);
        $this->assertStringContainsString('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAY', $result);
    }

    public function test_get_image_as_base64_returns_null_for_nonexistent_file()
    {
        $result = ImageHelper::getImageAsBase64('nonexistent-image.png');

        $this->assertNull($result);
    }

    public function test_get_image_as_base64_handles_different_image_types()
    {
        // Test with JPEG
        $testImagePath = public_path('test-image.jpg');
        
        // Create a simple JPEG image (this is a minimal valid JPEG)
        $jpegData = hex2bin('FFD8FFE000104A46494600010101006000600000FFDB004300080606070605080707070909080A0C140D0C0B0B0C1912130F141D1A1F1E1D1A1C1C20242E2720222C231C1C2853223C2237342C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2C2CFFC000110800010001000002110103021101FFD9');
        file_put_contents($testImagePath, $jpegData);

        $result = ImageHelper::getImageAsBase64('test-image.jpg');

        // Clean up test file
        if (file_exists($testImagePath)) {
            unlink($testImagePath);
        }

        $this->assertNotNull($result);
        $this->assertStringStartsWith('data:image/jpeg;base64,', $result);
    }

    public function test_get_image_as_base64_logs_error_on_exception()
    {
        // Mock the Log facade to verify error logging
        Log::shouldReceive('error')
            ->once()
            ->with(\Mockery::type('string'));

        // Create a directory with same name as file to trigger an exception
        $testPath = public_path('test-directory');
        mkdir($testPath);

        $result = ImageHelper::getImageAsBase64('test-directory');

        // Clean up test directory
        if (is_dir($testPath)) {
            rmdir($testPath);
        }

        $this->assertNull($result);
    }

    public function test_get_image_as_base64_with_subdirectory_path()
    {
        // Create subdirectory and test image
        $subDir = public_path('images');
        if (!is_dir($subDir)) {
            mkdir($subDir, 0755, true);
        }
        
        $testImagePath = public_path('images/test-image.png');
        
        // Create a simple 1x1 PNG image
        $imageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAHUAfJ4SQAAAABJRU5ErkJggg==');
        file_put_contents($testImagePath, $imageData);

        $result = ImageHelper::getImageAsBase64('images/test-image.png');

        // Clean up test files
        if (file_exists($testImagePath)) {
            unlink($testImagePath);
        }
        if (is_dir($subDir)) {
            rmdir($subDir);
        }

        $this->assertNotNull($result);
        $this->assertStringStartsWith('data:image/png;base64,', $result);
    }

    public function test_get_image_as_base64_handles_empty_path()
    {
        $result = ImageHelper::getImageAsBase64('');

        $this->assertNull($result);
    }

    public function test_get_image_as_base64_handles_null_path()
    {
        $result = ImageHelper::getImageAsBase64(null);

        $this->assertNull($result);
    }

    public function test_get_image_as_base64_includes_correct_mime_type()
    {
        // Create a test GIF image
        $testImagePath = public_path('test-image.gif');
        
        // Create a simple 1x1 GIF image
        $gifData = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        file_put_contents($testImagePath, $gifData);

        $result = ImageHelper::getImageAsBase64('test-image.gif');

        // Clean up test file
        if (file_exists($testImagePath)) {
            unlink($testImagePath);
        }

        $this->assertNotNull($result);
        $this->assertStringStartsWith('data:image/gif;base64,', $result);
    }

    public function test_get_image_as_base64_returns_valid_base64_data()
    {
        // Create a test image
        $testImagePath = public_path('test-base64.png');
        
        // Create a simple 1x1 PNG image
        $imageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAHUAfJ4SQAAAABJRU5ErkJggg==');
        file_put_contents($testImagePath, $imageData);

        $result = ImageHelper::getImageAsBase64('test-base64.png');

        // Clean up test file
        if (file_exists($testImagePath)) {
            unlink($testImagePath);
        }

        $this->assertNotNull($result);
        
        // Extract the base64 part
        $base64Part = substr($result, strpos($result, ',') + 1);
        
        // Verify it's valid base64
        $decoded = base64_decode($base64Part, true);
        $this->assertNotFalse($decoded);
        
        // Verify the decoded data matches original
        $this->assertEquals($imageData, $decoded);
    }
}