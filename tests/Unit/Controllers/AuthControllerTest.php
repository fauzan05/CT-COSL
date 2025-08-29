<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\AuthController;
use App\Models\PasswordResetTokenModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AuthController();
    }

    public function test_post_login_with_valid_credentials_returns_success()
    {
        // Create a test user
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
        ]);

        // Mock the request
        $request = Request::create('/api/login', 'POST', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        // Add session mock
        $request->setLaravelSession($this->app['session.store']);

        // Call the controller method
        $response = $this->controller->postLogin($request);

        // Assert the response
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Login successful', $responseData['message']);
    }

    public function test_post_login_with_invalid_credentials_returns_error()
    {
        // Create a test user
        User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
        ]);

        // Mock the request with wrong password
        $request = Request::create('/api/login', 'POST', [
            'username' => 'testuser',
            'password' => 'wrongpassword',
        ]);

        // Add session mock
        $request->setLaravelSession($this->app['session.store']);

        // Call the controller method
        $response = $this->controller->postLogin($request);

        // Assert the response
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Incorrect username or password, please try again.', $responseData['message']);
    }

    public function test_post_login_with_missing_fields_returns_validation_error()
    {
        // Mock the request without username
        $request = Request::create('/api/login', 'POST', [
            'password' => 'password123',
        ]);

        // Expect validation exception
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        // Call the controller method
        $this->controller->postLogin($request);
    }

    public function test_current_user_with_authenticated_user_returns_user_data()
    {
        // Create and authenticate a test user
        $user = User::factory()->create([
            'fullname' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'is_admin' => true,
            'download_access' => true,
            'modification_job_tracker_master_access' => false,
            'profile_image' => null,
        ]);

        Auth::login($user);

        // Mock Storage facade
        Storage::shouldReceive('url')->andReturn('http://example.com/storage/image.jpg');

        // Mock the request
        $request = Request::create('/api/current-user', 'GET');

        // Call the controller method
        $response = $this->controller->currentUser($request);

        // Assert the response
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals($user->id, $responseData['id']);
        $this->assertEquals('Test User', $responseData['fullname']);
        $this->assertEquals('testuser', $responseData['username']);
        $this->assertEquals('test@example.com', $responseData['email']);
        $this->assertTrue($responseData['is_admin']);
        $this->assertTrue($responseData['download_access']);
        $this->assertFalse($responseData['modification_job_tracker_master_access']);
    }

    public function test_current_user_without_authentication_returns_unauthorized()
    {
        // Ensure no user is authenticated
        Auth::logout();

        // Mock the request
        $request = Request::create('/api/current-user', 'GET');

        // Call the controller method
        $response = $this->controller->currentUser($request);

        // Assert the response
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Unauthorized', $responseData['message']);
    }

    public function test_logout_clears_session_and_returns_success()
    {
        // Create and authenticate a test user
        $user = User::factory()->create();
        Auth::login($user);

        // Mock the request
        $request = Request::create('/api/logout', 'POST');
        $request->setLaravelSession($this->app['session.store']);

        // Call the controller method
        $response = $this->controller->logout($request);

        // Assert the response
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Logout successful', $responseData['message']);

        // Assert user is logged out
        $this->assertNull(Auth::user());
    }

    public function test_forgot_password_with_valid_email_sends_reset_link()
    {
        // Mock mail sending
        Mail::fake();

        // Create a test user
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        // Use a real password reset token approach for simplicity
        // Mock the password broker app instance
        $this->app->bind('auth.password.broker', function () {
            return new class {
                public function createToken($user) {
                    return 'mock-token-12345';
                }
            };
        });

        // Mock the request
        $request = Request::create('/api/forgot-password', 'POST', [
            'email' => 'test@example.com',
        ]);

        // Call the controller method
        $response = $this->controller->forgotPassword($request);

        // Assert the response
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Password reset link sent to your email', $responseData['message']);
    }

    public function test_forgot_password_with_invalid_email_returns_error()
    {
        // Mock the request with non-existent email
        $request = Request::create('/api/forgot-password', 'POST', [
            'email' => 'nonexistent@example.com',
        ]);

        // Call the controller method
        $response = $this->controller->forgotPassword($request);

        // Assert the response
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Email not found', $responseData['message']);
    }

    public function test_validate_password_reset_token_with_valid_token_returns_true()
    {
        // Create a password reset token record
        PasswordResetTokenModel::create([
            'email' => 'test@example.com',
            'token' => Hash::make('valid-token'),
            'created_at' => now(),
        ]);

        // Mock the request
        $request = Request::create('/test', 'POST', [
            'email' => 'test@example.com',
            'token' => 'valid-token',
        ]);

        // Call the controller method
        $result = $this->controller->validatePasswordResetToken($request);

        // Assert the result
        $this->assertTrue($result);
    }

    public function test_validate_password_reset_token_with_invalid_token_returns_false()
    {
        // Create a password reset token record
        PasswordResetTokenModel::create([
            'email' => 'test@example.com',
            'token' => Hash::make('valid-token'),
            'created_at' => now(),
        ]);

        // Mock the request with invalid token
        $request = Request::create('/test', 'POST', [
            'email' => 'test@example.com',
            'token' => 'invalid-token',
        ]);

        // Call the controller method
        $result = $this->controller->validatePasswordResetToken($request);

        // Assert the result
        $this->assertFalse($result);
    }

    public function test_validate_password_reset_token_with_expired_token_returns_false()
    {
        // Create an expired password reset token record
        PasswordResetTokenModel::create([
            'email' => 'test@example.com',
            'token' => Hash::make('valid-token'),
            'created_at' => now()->subHours(2), // 2 hours ago (expired)
        ]);

        // Mock the request
        $request = Request::create('/test', 'POST', [
            'email' => 'test@example.com',
            'token' => 'valid-token',
        ]);

        // Call the controller method
        $result = $this->controller->validatePasswordResetToken($request);

        // Assert the result
        $this->assertFalse($result);
    }

    public function test_reset_password_with_valid_token_updates_password()
    {
        // Create a test user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        // Create a password reset token record
        PasswordResetTokenModel::create([
            'email' => 'test@example.com',
            'token' => Hash::make('valid-token'),
            'created_at' => now(),
        ]);

        // Mock the request
        $request = Request::create('/api/reset-password', 'POST', [
            'email' => 'test@example.com',
            'token' => 'valid-token',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        // Call the controller method
        $response = $this->controller->resetPassword($request);

        // Assert the response
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Password has been reset successfully', $responseData['message']);

        // Assert password was updated
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));

        // Assert token was deleted
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_reset_password_with_invalid_token_returns_error()
    {
        // Create a test user
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        // Mock the request with invalid token
        $request = Request::create('/api/reset-password', 'POST', [
            'email' => 'test@example.com',
            'token' => 'invalid-token',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        // Call the controller method
        $response = $this->controller->resetPassword($request);

        // Assert the response
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Invalid or expired password reset token', $responseData['message']);
    }

    public function test_reset_password_with_nonexistent_user_returns_error()
    {
        // Create a password reset token record without user
        PasswordResetTokenModel::create([
            'email' => 'nonexistent@example.com',
            'token' => Hash::make('valid-token'),
            'created_at' => now(),
        ]);

        // Mock the request
        $request = Request::create('/api/reset-password', 'POST', [
            'email' => 'nonexistent@example.com',
            'token' => 'valid-token',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        // Call the controller method
        $response = $this->controller->resetPassword($request);

        // Assert the response
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Email not found', $responseData['message']);
    }
}