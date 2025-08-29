<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $adminUser;
    protected $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new UserController();
        
        $this->adminUser = User::factory()->create([
            'is_admin' => true,
            'fullname' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $this->regularUser = User::factory()->create([
            'is_admin' => false,
            'fullname' => 'Regular User',
            'username' => 'regular',
            'email' => 'regular@example.com',
        ]);
    }

    public function test_get_users_returns_paginated_non_admin_users()
    {
        // Create additional users
        User::factory()->count(5)->create(['is_admin' => false]);

        $request = Request::create('/api/users', 'GET', [
            'per_page' => 10,
            'page' => 1,
            'sort_by' => 'fullname',
            'sort_direction' => 'asc',
        ]);

        $response = $this->controller->getUsers($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        // Should not include admin users
        $this->assertGreaterThan(0, count($responseData['data']));
        foreach ($responseData['data'] as $user) {
            $this->assertArrayNotHasKey('is_admin', $user);
        }
    }

    public function test_get_users_filters_by_search_term()
    {
        User::factory()->create([
            'fullname' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'is_admin' => false,
        ]);

        $request = Request::create('/api/users', 'GET', [
            'search' => 'John',
        ]);

        $response = $this->controller->getUsers($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertGreaterThan(0, count($responseData['data']));
        $this->assertStringContainsString('John', $responseData['data'][0]['fullname']);
    }

    public function test_check_username_returns_available_for_new_username()
    {
        $request = Request::create('/api/check-username', 'POST', [
            'username' => 'newuser',
        ]);

        $response = $this->controller->checkUsername($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertTrue($responseData['available']);
        $this->assertEquals('Username is available', $responseData['message']);
    }

    public function test_check_username_returns_unavailable_for_existing_username()
    {
        $request = Request::create('/api/check-username', 'POST', [
            'username' => $this->regularUser->username,
        ]);

        $response = $this->controller->checkUsername($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertFalse($responseData['available']);
        $this->assertEquals('Username is not available', $responseData['message']);
    }

    public function test_check_username_returns_unavailable_for_reserved_username()
    {
        $request = Request::create('/api/check-username', 'POST', [
            'username' => 'admin',
        ]);

        $response = $this->controller->checkUsername($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertFalse($responseData['available']);
        $this->assertEquals('Username is not available', $responseData['message']);
    }

    public function test_check_username_returns_error_when_username_missing()
    {
        $request = Request::create('/api/check-username', 'POST', []);

        $response = $this->controller->checkUsername($request);

        $this->assertEquals(400, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('Username is required', $responseData['message']);
    }

    public function test_check_email_returns_available_for_new_email()
    {
        $request = Request::create('/api/check-email', 'POST', [
            'email' => 'newemail@example.com',
        ]);

        $response = $this->controller->checkEmail($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertTrue($responseData['available']);
        $this->assertEquals('Email is available', $responseData['message']);
    }

    public function test_check_email_returns_unavailable_for_existing_email()
    {
        $request = Request::create('/api/check-email', 'POST', [
            'email' => $this->regularUser->email,
        ]);

        $response = $this->controller->checkEmail($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertFalse($responseData['available']);
        $this->assertEquals('Email is already in use', $responseData['message']);
    }

    public function test_check_email_returns_error_when_email_missing()
    {
        $request = Request::create('/api/check-email', 'POST', []);

        $response = $this->controller->checkEmail($request);

        $this->assertEquals(400, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('Email is required', $responseData['message']);
    }

    public function test_store_user_creates_new_user_successfully()
    {
        Mail::fake();

        $request = Request::create('/api/users', 'POST', [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'fullname' => 'New User',
            'download_access' => true,
            'modification_job_tracker_master_access' => false,
        ]);
        $request->setUserResolver(function () {
            return $this->adminUser;
        });

        $response = $this->controller->storeUser($request);

        $this->assertEquals(201, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('User created successfully and email sent', $responseData['message']);
        
        // Assert user was created in database
        $this->assertDatabaseHas('users', [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'fullname' => 'New User',
            'is_admin' => false,
        ]);

        // Assert email was sent
        Mail::assertSent(\App\Mail\UserMail::class);
    }

    public function test_store_user_validates_required_fields()
    {
        $request = Request::create('/api/users', 'POST', [
            'username' => '', // Empty username
        ]);
        $request->setUserResolver(function () {
            return $this->adminUser;
        });

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->storeUser($request);
    }

    public function test_update_download_permission_updates_successfully()
    {
        $request = Request::create('/api/users/' . $this->regularUser->id . '/update-download-permission', 'POST', [
            'download_access' => false,
        ]);
        $request->setUserResolver(function () {
            return $this->adminUser;
        });

        $response = $this->controller->updateDownloadPermission($request, $this->regularUser->id);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('Download access updated successfully', $responseData['message']);
        
        // Assert user was updated
        $this->regularUser->refresh();
        $this->assertEquals(0, $this->regularUser->download_access); // Database stores as 0/1
    }

    public function test_update_download_permission_requires_admin()
    {
        $request = Request::create('/api/users/' . $this->regularUser->id . '/update-download-permission', 'POST', [
            'download_access' => false,
        ]);
        $request->setUserResolver(function () {
            return $this->regularUser; // Non-admin user
        });

        $response = $this->controller->updateDownloadPermission($request, $this->regularUser->id);

        $this->assertEquals(403, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('Unauthorized', $responseData['message']);
    }

    public function test_update_download_permission_returns_error_for_nonexistent_user()
    {
        $request = Request::create('/api/users/nonexistent/update-download-permission', 'POST', [
            'download_access' => false,
        ]);
        $request->setUserResolver(function () {
            return $this->adminUser;
        });

        $response = $this->controller->updateDownloadPermission($request, 'nonexistent');

        $this->assertEquals(404, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('User not found', $responseData['message']);
    }

    public function test_update_modification_job_tracker_master_permission_updates_successfully()
    {
        $request = Request::create('/api/users/' . $this->regularUser->id . '/update-modification-job-tracker-master-permission', 'POST', [
            'modification_job_tracker_master_access' => true,
        ]);
        $request->setUserResolver(function () {
            return $this->adminUser;
        });

        $response = $this->controller->updateModificationJobTrackerMasterPermission($request, $this->regularUser->id);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('Modification job tracker master access updated successfully', $responseData['message']);
        
        // Assert user was updated
        $this->regularUser->refresh();
        $this->assertEquals(1, $this->regularUser->modification_job_tracker_master_access); // Database stores as 0/1
    }

    public function test_update_user_updates_user_successfully()
    {
        Mail::fake();

        $request = Request::create('/api/users/' . $this->regularUser->id, 'PUT', [
            'email' => 'updated@example.com',
            'fullname' => 'Updated Name',
            'download_access' => false,
            'modification_job_tracker_master_access' => true,
            'is_update_password' => false,
        ]);
        $request->setUserResolver(function () {
            return $this->adminUser;
        });

        $response = $this->controller->updateUser($request, $this->regularUser->id);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('User updated successfully', $responseData['message']);
        
        // Assert user was updated
        $this->regularUser->refresh();
        $this->assertEquals('updated@example.com', $this->regularUser->email);
        $this->assertEquals('Updated Name', $this->regularUser->fullname);
        $this->assertEquals(0, $this->regularUser->download_access); // Database stores as 0/1
        $this->assertEquals(1, $this->regularUser->modification_job_tracker_master_access); // Database stores as 0/1
    }

    public function test_update_user_updates_password_when_requested()
    {
        Mail::fake();

        $request = Request::create('/api/users/' . $this->regularUser->id, 'PUT', [
            'email' => $this->regularUser->email,
            'fullname' => $this->regularUser->fullname,
            'password' => 'newpassword123',
            'is_update_password' => true,
        ]);
        $request->setUserResolver(function () {
            return $this->adminUser;
        });

        $oldPassword = $this->regularUser->password;
        
        $response = $this->controller->updateUser($request, $this->regularUser->id);

        $this->assertEquals(200, $response->getStatusCode());
        
        // Assert password was updated
        $this->regularUser->refresh();
        $this->assertNotEquals($oldPassword, $this->regularUser->password);
        $this->assertTrue(Hash::check('newpassword123', $this->regularUser->password));
    }

    public function test_delete_user_deletes_users_successfully()
    {
        $userToDelete = User::factory()->create(['is_admin' => false]);

        $request = Request::create('/api/users', 'DELETE', [
            'ids' => [$userToDelete->id],
        ]);
        $request->setUserResolver(function () {
            return $this->adminUser;
        });

        $response = $this->controller->deleteUser($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('User deleted successfully', $responseData['message']);
        
        // Assert user was soft deleted
        $this->assertSoftDeleted('users', ['id' => $userToDelete->id]);
    }

    public function test_delete_user_returns_error_when_no_ids_provided()
    {
        $request = Request::create('/api/users', 'DELETE', [
            'ids' => [],
        ]);
        $request->setUserResolver(function () {
            return $this->adminUser;
        });

        $response = $this->controller->deleteUser($request);

        $this->assertEquals(400, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('No user IDs provided', $responseData['message']);
    }

    public function test_update_current_user_updates_profile_successfully()
    {
        Storage::fake('public');

        $request = Request::create('/api/users-profile', 'PUT', [
            'fullname' => 'Updated Current User',
        ]);
        $request->setUserResolver(function () {
            return $this->regularUser;
        });

        $response = $this->controller->updateCurrentUser($request);

        $this->assertNull($response); // Method doesn't explicitly return response
        
        // Assert user was updated
        $this->regularUser->refresh();
        $this->assertEquals('Updated Current User', $this->regularUser->fullname);
    }

    public function test_update_current_user_uploads_profile_image()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('profile.jpg');

        $request = Request::create('/api/users-profile', 'PUT', [
            'fullname' => 'User with Image',
        ]);
        $request->files->set('profile_image', $file);
        $request->setUserResolver(function () {
            return $this->regularUser;
        });

        $response = $this->controller->updateCurrentUser($request);

        // Assert user was updated with image
        $this->regularUser->refresh();
        $this->assertEquals('User with Image', $this->regularUser->fullname);
        $this->assertNotNull($this->regularUser->profile_image);
        
        // Check that the filename contains expected pattern (time prefix + original name)
        $this->assertStringContainsString('profile.jpg', $this->regularUser->profile_image);
    }

    public function test_change_current_user_password_changes_password_successfully()
    {
        $this->regularUser->password = Hash::make('currentpassword');
        $this->regularUser->save();

        $request = Request::create('/api/users-profile-change-password', 'PUT', [
            'current_password' => 'currentpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);
        $request->setUserResolver(function () {
            return $this->regularUser;
        });

        $response = $this->controller->changeCurrentUserPassword($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('Password changed successfully', $responseData['message']);
        
        // Assert password was changed
        $this->regularUser->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->regularUser->password));
    }

    public function test_change_current_user_password_returns_error_for_wrong_current_password()
    {
        $this->regularUser->password = Hash::make('currentpassword');
        $this->regularUser->save();

        $request = Request::create('/api/users-profile-change-password', 'PUT', [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);
        $request->setUserResolver(function () {
            return $this->regularUser;
        });

        $response = $this->controller->changeCurrentUserPassword($request);

        $this->assertEquals(422, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('Current password is incorrect', $responseData['message']);
    }
}