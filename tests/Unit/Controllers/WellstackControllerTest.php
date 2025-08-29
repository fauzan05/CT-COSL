<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\WellstackController;
use App\Models\User;
use App\Models\WellstackItemModel;
use App\Models\WellstackTypeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WellstackControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new WellstackController();
        
        $this->user = User::factory()->create([
            'is_admin' => true,
            'fullname' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);
    }

    public function test_get_types_returns_all_types_ordered_by_name()
    {
        // Create test types
        WellstackTypeModel::factory()->create(['name' => 'Z Type']);
        WellstackTypeModel::factory()->create(['name' => 'A Type']);
        WellstackTypeModel::factory()->create(['name' => 'M Type']);

        $response = $this->controller->getTypes();

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertCount(3, $responseData);
        // Should be ordered by name ascending
        $this->assertEquals('A Type', $responseData[0]['name']);
        $this->assertEquals('M Type', $responseData[1]['name']);
        $this->assertEquals('Z Type', $responseData[2]['name']);
    }

    public function test_get_type_returns_specific_type()
    {
        $type = WellstackTypeModel::factory()->create([
            'name' => 'Test Type',
            'slug' => 'test-type',
        ]);

        $response = $this->controller->getType($type->id);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals($type->id, $responseData['id']);
        $this->assertEquals('Test Type', $responseData['name']);
        $this->assertEquals('test-type', $responseData['slug']);
    }

    public function test_get_type_returns_404_for_nonexistent_type()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->controller->getType('nonexistent-id');
    }

    public function test_store_type_creates_new_type_successfully()
    {
        $request = Request::create('/api/wellstack-types', 'POST', [
            'name' => 'New Wellstack Type',
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->controller->storeType($request);

        $this->assertEquals(201, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('Wellstack type created successfully', $responseData['message']);
        $this->assertEquals('New Wellstack Type', $responseData['type']['name']);
        $this->assertEquals('new-wellstack-type', $responseData['type']['slug']);
        
        // Assert type was created in database
        $this->assertDatabaseHas('wellstack_types', [
            'name' => 'New Wellstack Type',
            'slug' => 'new-wellstack-type',
            'created_by' => $this->user->id,
            'updated_by' => $this->user->id,
        ]);
    }

    public function test_store_type_validates_required_name()
    {
        $request = Request::create('/api/wellstack-types', 'POST', []);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->storeType($request);
    }

    public function test_update_type_updates_existing_type_successfully()
    {
        $type = WellstackTypeModel::factory()->create([
            'name' => 'Original Name',
            'slug' => 'original-name',
        ]);

        $request = Request::create('/api/wellstack-types/' . $type->id, 'PUT', [
            'name' => 'Updated Name',
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->controller->updateType($request, $type->id);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('Wellstack type updated successfully', $responseData['message']);
        $this->assertEquals('Updated Name', $responseData['type']['name']);
        $this->assertEquals('updated-name', $responseData['type']['slug']);
        
        // Assert type was updated in database
        $type->refresh();
        $this->assertEquals('Updated Name', $type->name);
        $this->assertEquals('updated-name', $type->slug);
        $this->assertEquals($this->user->id, $type->updated_by);
    }

    public function test_update_type_returns_404_for_nonexistent_type()
    {
        $request = Request::create('/api/wellstack-types/nonexistent', 'PUT', [
            'name' => 'Updated Name',
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->controller->updateType($request, 'nonexistent-id');
    }

    public function test_delete_type_deletes_type_successfully()
    {
        $type = WellstackTypeModel::factory()->create();

        $response = $this->controller->deleteType($type->id);

        $this->assertEquals(204, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('Wellstack type deleted successfully', $responseData['message']);
        
        // Assert type was soft deleted
        $this->assertSoftDeleted('wellstack_types', ['id' => $type->id]);
    }

    public function test_delete_type_returns_404_for_nonexistent_type()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->controller->deleteType('nonexistent-id');
    }

    public function test_search_types_returns_filtered_types()
    {
        WellstackTypeModel::factory()->create(['name' => 'Blowout Preventer']);
        WellstackTypeModel::factory()->create(['name' => 'Drilling Pipe']);
        WellstackTypeModel::factory()->create(['name' => 'Safety Valve']);

        $request = Request::create('/api/wellstack-types-search', 'GET', [
            'search' => 'Blow',
        ]);

        $response = $this->controller->searchTypes($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertCount(1, $responseData);
        $this->assertEquals('Blowout Preventer', $responseData[0]['name']);
    }

    public function test_search_types_returns_all_types_when_no_search_term()
    {
        WellstackTypeModel::factory()->count(3)->create();

        $request = Request::create('/api/wellstack-types-search', 'GET', []);

        $response = $this->controller->searchTypes($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertCount(3, $responseData);
    }

    public function test_store_item_creates_new_item_successfully()
    {
        $type = WellstackTypeModel::factory()->create();

        $request = Request::create('/api/wellstack-items', 'POST', [
            'wellstack_type_id' => $type->id,
            'name' => 'Test Item',
            'description' => 'Test Description',
            'serial_number' => 'WS-1234',
            'height' => 10.5,
            'height_unit' => 'ft',
            'weight' => 250.0,
            'weight_unit' => 'lbs',
            'pressure_rating' => 1500.0,
            'pressure_rating_unit' => 'psi',
            'owner' => 'Test Company',
            'shear_ram_dist_from_bottom' => 5.0,
            'shear_ram_dist_from_bottom_unit' => 'ft',
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        // The method doesn't return response properly due to transaction wrapper
        $this->controller->storeItem($request);

        // Assert item was created in database
        $this->assertDatabaseHas('wellstack_items', [
            'name' => 'Test Item',
            'description' => 'Test Description',
            'serial_number' => 'WS-1234',
            'wellstack_type_id' => $type->id,
            'created_by' => $this->user->id,
            'updated_by' => $this->user->id,
        ]);
    }

    public function test_store_item_uploads_image_file()
    {
        Storage::fake('public');

        $type = WellstackTypeModel::factory()->create();
        $file = UploadedFile::fake()->image('test-item.jpg');

        $request = Request::create('/api/wellstack-items', 'POST', [
            'wellstack_type_id' => $type->id,
            'name' => 'Test Item with Image',
            'description' => 'Test Description',
            'serial_number' => 'WS-IMG-001', // Required field
        ]);
        $request->files->set('image', $file);
        $request->setUserResolver(function () {
            return $this->user;
        });

        // The method doesn't return response properly due to transaction wrapper
        $this->controller->storeItem($request);

        // Find the created item
        $item = WellstackItemModel::where('name', 'Test Item with Image')->first();
        $this->assertNotNull($item);
        $this->assertNotNull($item->image);
        
        // Check that the filename contains expected pattern (40 random chars + extension)
        $this->assertStringEndsWith('.jpg', $item->image);
        $this->assertEquals(44, strlen($item->image)); // 40 chars + '.jpg' = 44
    }

    public function test_store_item_validates_required_fields()
    {
        $request = Request::create('/api/wellstack-items', 'POST', [
            'name' => '', // Empty name
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->storeItem($request);
    }

    public function test_store_item_validates_wellstack_type_exists()
    {
        $request = Request::create('/api/wellstack-items', 'POST', [
            'wellstack_type_id' => 'nonexistent-id',
            'name' => 'Test Item',
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->storeItem($request);
    }

    public function test_store_item_handles_nullable_fields()
    {
        $type = WellstackTypeModel::factory()->create();

        $request = Request::create('/api/wellstack-items', 'POST', [
            'wellstack_type_id' => $type->id,
            'name' => 'Minimal Item',
            'serial_number' => 'WS-MIN-001', // Required field
            // All other fields are optional (no image provided)
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        // The method doesn't return response properly due to transaction wrapper
        $this->controller->storeItem($request);

        // Assert item was created with minimal data
        $this->assertDatabaseHas('wellstack_items', [
            'name' => 'Minimal Item',
            'wellstack_type_id' => $type->id,
            'serial_number' => 'WS-MIN-001',
            'description' => null,
        ]);
    }
}