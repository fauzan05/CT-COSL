<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\WellstackItemModel;
use App\Models\WellstackTypeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WellstackItemModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_wellstack_item_can_be_created_with_factory()
    {
        $item = WellstackItemModel::factory()->create();

        $this->assertInstanceOf(WellstackItemModel::class, $item);
        $this->assertDatabaseHas('wellstack_items', [
            'id' => $item->id,
            'name' => $item->name,
        ]);
    }

    public function test_wellstack_item_has_fillable_attributes()
    {
        $fillable = [
            'wellstack_type_id',
            'name',
            'description',
            'serial_number',
            'image',
            'height',
            'height_unit',
            'weight',
            'weight_unit',
            'pressure_rating',
            'pressure_rating_unit',
            'owner',
            'shear_ram_dist_from_bottom',
            'shear_ram_dist_from_bottom_unit',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ];

        $item = new WellstackItemModel();
        $this->assertEquals($fillable, $item->getFillable());
    }

    public function test_wellstack_item_uses_uuid_as_primary_key()
    {
        $item = WellstackItemModel::factory()->create();

        $this->assertIsString($item->id);
        $this->assertEquals(36, strlen($item->id)); // UUID length
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $item->id);
    }

    public function test_wellstack_item_has_correct_table_name()
    {
        $item = new WellstackItemModel();
        $this->assertEquals('wellstack_items', $item->getTable());
    }

    public function test_wellstack_item_timestamps_are_disabled()
    {
        $item = new WellstackItemModel();
        $this->assertFalse($item->timestamps);
    }

    public function test_wellstack_item_uses_soft_deletes()
    {
        $item = WellstackItemModel::factory()->create();
        $itemId = $item->id;

        // Soft delete the item
        $item->delete();

        // Item should not be found in normal queries
        $this->assertNull(WellstackItemModel::find($itemId));

        // But should be found with trashed
        $this->assertNotNull(WellstackItemModel::withTrashed()->find($itemId));
    }

    public function test_wellstack_item_belongs_to_wellstack_type()
    {
        $type = WellstackTypeModel::factory()->create();
        $item = WellstackItemModel::factory()->create(['wellstack_type_id' => $type->id]);

        $this->assertInstanceOf(WellstackTypeModel::class, $item->wellstackType);
        $this->assertEquals($type->id, $item->wellstackType->id);
        $this->assertEquals($type->name, $item->wellstackType->name);
    }

    public function test_wellstack_item_belongs_to_updated_by_user()
    {
        $user = User::factory()->create();
        $item = WellstackItemModel::factory()->create(['updated_by' => $user->id]);

        $this->assertInstanceOf(User::class, $item->updatedByUser);
        $this->assertEquals($user->id, $item->updatedByUser->id);
        $this->assertEquals($user->fullname, $item->updatedByUser->fullname);
    }

    public function test_wellstack_item_attributes_can_be_set_and_retrieved()
    {
        $item = WellstackItemModel::factory()->create([
            'name' => 'Test Item',
            'description' => 'Test Description',
            'serial_number' => 'WS-1234-ABC',
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

        $this->assertEquals('Test Item', $item->name);
        $this->assertEquals('Test Description', $item->description);
        $this->assertEquals('WS-1234-ABC', $item->serial_number);
        $this->assertEquals(10.5, $item->height);
        $this->assertEquals('ft', $item->height_unit);
        $this->assertEquals(250.0, $item->weight);
        $this->assertEquals('lbs', $item->weight_unit);
        $this->assertEquals(1500.0, $item->pressure_rating);
        $this->assertEquals('psi', $item->pressure_rating_unit);
        $this->assertEquals('Test Company', $item->owner);
        $this->assertEquals(5.0, $item->shear_ram_dist_from_bottom);
        $this->assertEquals('ft', $item->shear_ram_dist_from_bottom_unit);
    }

    public function test_wellstack_item_casts_dates_correctly()
    {
        $item = WellstackItemModel::factory()->create();

        $this->assertInstanceOf(\Carbon\Carbon::class, $item->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $item->updated_at);
        // deleted_at is null for non-deleted items
        $this->assertNull($item->deleted_at);
    }

    public function test_wellstack_item_factory_creates_valid_data()
    {
        $item = WellstackItemModel::factory()->create();

        $this->assertNotEmpty($item->name);
        $this->assertNotEmpty($item->description);
        $this->assertNotEmpty($item->serial_number);
        $this->assertIsFloat($item->height);
        $this->assertIsFloat($item->weight);
        $this->assertIsFloat($item->pressure_rating);
        $this->assertIsFloat($item->shear_ram_dist_from_bottom);
        $this->assertNotEmpty($item->owner);
        $this->assertContains($item->height_unit, ['ft', 'm', 'in']);
        $this->assertContains($item->weight_unit, ['lbs', 'kg', 'tons']);
        $this->assertContains($item->pressure_rating_unit, ['psi', 'bar', 'kPa']);
        $this->assertContains($item->shear_ram_dist_from_bottom_unit, ['ft', 'm', 'in']);
    }

    public function test_wellstack_item_can_have_specific_image()
    {
        $item = WellstackItemModel::factory()->create(['image' => 'custom-image.jpg']);

        $this->assertEquals('custom-image.jpg', $item->image);
    }

    public function test_wellstack_item_factory_creates_unique_items()
    {
        $item1 = WellstackItemModel::factory()->create();
        $item2 = WellstackItemModel::factory()->create();

        $this->assertNotEquals($item1->name, $item2->name);
        $this->assertNotEquals($item1->serial_number, $item2->serial_number);
        $this->assertNotEquals($item1->description, $item2->description);
    }
}