<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\WellstackItemModel;
use App\Models\WellstackTypeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WellstackTypeModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_wellstack_type_can_be_created_with_factory()
    {
        $type = WellstackTypeModel::factory()->create();

        $this->assertInstanceOf(WellstackTypeModel::class, $type);
        $this->assertDatabaseHas('wellstack_types', [
            'id' => $type->id,
            'name' => $type->name,
        ]);
    }

    public function test_wellstack_type_has_fillable_attributes()
    {
        $fillable = [
            'name',
            'slug',
            'created_by',
            'updated_by',
        ];

        $type = new WellstackTypeModel();
        $this->assertEquals($fillable, $type->getFillable());
    }

    public function test_wellstack_type_uses_uuid_as_primary_key()
    {
        $type = WellstackTypeModel::factory()->create();

        $this->assertIsString($type->id);
        $this->assertEquals(36, strlen($type->id)); // UUID length
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $type->id);
    }

    public function test_wellstack_type_has_correct_table_name()
    {
        $type = new WellstackTypeModel();
        $this->assertEquals('wellstack_types', $type->getTable());
    }

    public function test_wellstack_type_timestamps_are_disabled()
    {
        $type = new WellstackTypeModel();
        $this->assertFalse($type->timestamps);
    }

    public function test_wellstack_type_uses_soft_deletes()
    {
        $type = WellstackTypeModel::factory()->create();
        $typeId = $type->id;

        // Soft delete the type
        $type->delete();

        // Type should not be found in normal queries
        $this->assertNull(WellstackTypeModel::find($typeId));

        // But should be found with trashed
        $this->assertNotNull(WellstackTypeModel::withTrashed()->find($typeId));
    }

    public function test_wellstack_type_has_many_wellstack_items()
    {
        $type = WellstackTypeModel::factory()->create();
        $item1 = WellstackItemModel::factory()->create(['wellstack_type_id' => $type->id]);
        $item2 = WellstackItemModel::factory()->create(['wellstack_type_id' => $type->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $type->wellstackItems);
        $this->assertCount(2, $type->wellstackItems);
        $this->assertTrue($type->wellstackItems->contains($item1));
        $this->assertTrue($type->wellstackItems->contains($item2));
    }

    public function test_wellstack_type_attributes_can_be_set_and_retrieved()
    {
        $type = WellstackTypeModel::factory()->create([
            'name' => 'Test Type',
            'slug' => 'test-type',
        ]);

        $this->assertEquals('Test Type', $type->name);
        $this->assertEquals('test-type', $type->slug);
    }

    public function test_wellstack_type_casts_dates_correctly()
    {
        $type = WellstackTypeModel::factory()->create();

        $this->assertInstanceOf(\Carbon\Carbon::class, $type->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $type->updated_at);
    }

    public function test_wellstack_type_factory_creates_unique_names()
    {
        $type1 = WellstackTypeModel::factory()->create();
        $type2 = WellstackTypeModel::factory()->create();

        $this->assertNotEquals($type1->name, $type2->name);
        $this->assertNotEquals($type1->slug, $type2->slug);
    }
}