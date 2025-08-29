<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created_with_factory()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public function test_user_has_fillable_attributes()
    {
        $fillable = [
            'fullname',
            'username',
            'email',
            'password',
            'is_admin',
            'download_access',
            'modification_job_tracker_master_access',
            'profile_image',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ];

        $user = new User();
        $this->assertEquals($fillable, $user->getFillable());
    }

    public function test_user_password_is_hidden()
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret123'),
        ]);

        $this->assertArrayNotHasKey('password', $user->toArray());
        $this->assertArrayHasKey('password', $user->getAttributes());
    }

    public function test_user_remember_token_is_hidden()
    {
        $user = User::factory()->create();

        $this->assertArrayNotHasKey('remember_token', $user->toArray());
    }

    public function test_user_uses_uuid_as_primary_key()
    {
        $user = User::factory()->create();

        $this->assertIsString($user->id);
        $this->assertEquals(36, strlen($user->id)); // UUID length
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $user->id);
    }

    public function test_user_can_have_relationships_with_other_users()
    {
        $creator = User::factory()->create();
        $updater = User::factory()->create();
        
        $user = User::factory()->create([
            'created_by' => $creator->id,
            'updated_by' => $updater->id,
        ]);

        $this->assertInstanceOf(User::class, $user->createdBy);
        $this->assertInstanceOf(User::class, $user->updatedBy);
        $this->assertEquals($creator->id, $user->createdBy->id);
        $this->assertEquals($updater->id, $user->updatedBy->id);
    }

    public function test_get_profile_image_url_returns_storage_url_when_image_exists()
    {
        Storage::fake('public');
        
        $user = User::factory()->create([
            'profile_image' => 'test-image.jpg',
        ]);

        $url = $user->getProfileImageUrl();
        
        $this->assertStringContainsString('assets/images/profile_images/test-image.jpg', $url);
    }

    public function test_get_profile_image_url_returns_empty_string_when_no_image()
    {
        $user = User::factory()->create([
            'profile_image' => null,
        ]);

        $url = $user->getProfileImageUrl();
        
        $this->assertEquals('', $url);
    }

    public function test_send_email_create_user_notification_sends_mail()
    {
        Mail::fake();

        $user = User::factory()->create([
            'fullname' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        $user->sendEmailCreateUserNotification('password123');

        Mail::assertSent(\App\Mail\UserMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_send_email_update_user_notification_sends_mail()
    {
        Mail::fake();

        $user = User::factory()->create([
            'fullname' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        $user->sendEmailUpdateUserNotification(
            'newpassword123',
            [],
            'old@example.com',
            'new@example.com',
            'Old Name',
            'New Name'
        );

        Mail::assertSent(\App\Mail\UserMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_send_reset_password_notification_sends_mail()
    {
        Mail::fake();

        $user = User::factory()->create([
            'fullname' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        $resetLink = 'http://example.com/reset?token=abc123';
        $user->sendResetPasswordNotification($resetLink);

        Mail::assertSent(\App\Mail\UserMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_user_attributes_can_be_set_and_retrieved()
    {
        $user = User::factory()->create([
            'fullname' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'is_admin' => true,
            'download_access' => false,
            'modification_job_tracker_master_access' => true,
        ]);

        $this->assertEquals('John Doe', $user->fullname);
        $this->assertEquals('johndoe', $user->username);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue($user->is_admin);
        $this->assertFalse($user->download_access);
        $this->assertTrue($user->modification_job_tracker_master_access);
    }

    public function test_user_timestamps_are_disabled()
    {
        $user = new User();
        $this->assertFalse($user->timestamps);
    }

    public function test_user_primary_key_is_not_incrementing()
    {
        $user = new User();
        $this->assertFalse($user->incrementing);
        $this->assertEquals('string', $user->getKeyType());
        $this->assertEquals('id', $user->getKeyName());
    }

    public function test_user_password_is_hashed_when_cast()
    {
        $user = User::factory()->create([
            'password' => 'plaintext',
        ]);

        // The password should be hashed automatically
        $this->assertNotEquals('plaintext', $user->getAuthPassword());
        $this->assertTrue(Hash::check('plaintext', $user->getAuthPassword()));
    }

    public function test_user_soft_deletes_are_enabled()
    {
        $user = User::factory()->create();
        $userId = $user->id;

        // Soft delete the user
        $user->delete();

        // User should not be found in normal queries
        $this->assertNull(User::find($userId));

        // But should be found with trashed
        $this->assertNotNull(User::withTrashed()->find($userId));
    }

    public function test_user_factory_creates_unique_usernames_and_emails()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->assertNotEquals($user1->username, $user2->username);
        $this->assertNotEquals($user1->email, $user2->email);
    }

    public function test_user_new_unique_id_generates_valid_uuid()
    {
        $user = new User();
        $uuid = $user->newUniqueId();

        $this->assertIsString($uuid);
        $this->assertEquals(36, strlen($uuid));
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid);
    }
}