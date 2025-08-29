<?php

namespace Tests\Unit\Models;

use App\Models\DocumentListModel;
use App\Models\DocumentModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_document_can_be_created_with_factory()
    {
        $document = DocumentModel::factory()->create();

        $this->assertInstanceOf(DocumentModel::class, $document);
        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'name' => $document->name,
        ]);
    }

    public function test_document_has_fillable_attributes()
    {
        $fillable = [
            'name',
            'description',
            'menu',
            'created_by',
            'updated_by',
        ];

        $document = new DocumentModel();
        $this->assertEquals($fillable, $document->getFillable());
    }

    public function test_document_uses_uuid_as_primary_key()
    {
        $document = DocumentModel::factory()->create();

        $this->assertIsString($document->id);
        $this->assertEquals(36, strlen($document->id)); // UUID length
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $document->id);
    }

    public function test_document_has_correct_table_name()
    {
        $document = new DocumentModel();
        $this->assertEquals('documents', $document->getTable());
    }

    public function test_document_uses_soft_deletes()
    {
        $document = DocumentModel::factory()->create();
        $documentId = $document->id;

        // Soft delete the document
        $document->delete();

        // Document should not be found in normal queries
        $this->assertNull(DocumentModel::find($documentId));

        // But should be found with trashed
        $this->assertNotNull(DocumentModel::withTrashed()->find($documentId));
    }

    public function test_document_belongs_to_created_by_user()
    {
        $user = User::factory()->create();
        $document = DocumentModel::factory()->create(['created_by' => $user->id]);

        $this->assertInstanceOf(User::class, $document->createdBy);
        $this->assertEquals($user->id, $document->createdBy->id);
        $this->assertEquals($user->fullname, $document->createdBy->fullname);
    }

    public function test_document_belongs_to_updated_by_user()
    {
        $user = User::factory()->create();
        $document = DocumentModel::factory()->create(['updated_by' => $user->id]);

        $this->assertInstanceOf(User::class, $document->updatedBy);
        $this->assertEquals($user->id, $document->updatedBy->id);
        $this->assertEquals($user->fullname, $document->updatedBy->fullname);
    }

    public function test_document_has_many_document_lists()
    {
        $document = DocumentModel::factory()->create();
        $docList1 = DocumentListModel::factory()->create(['document_id' => $document->id]);
        $docList2 = DocumentListModel::factory()->create(['document_id' => $document->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $document->documents);
        $this->assertCount(2, $document->documents);
        $this->assertTrue($document->documents->contains($docList1));
        $this->assertTrue($document->documents->contains($docList2));
    }

    public function test_document_attributes_can_be_set_and_retrieved()
    {
        $document = DocumentModel::factory()->create([
            'name' => 'Test Document',
            'description' => 'Test Description',
            'menu' => 'coiled_tubing',
        ]);

        $this->assertEquals('Test Document', $document->name);
        $this->assertEquals('Test Description', $document->description);
        $this->assertEquals('coiled_tubing', $document->menu);
    }

    public function test_document_casts_dates_correctly()
    {
        $document = DocumentModel::factory()->create();

        $this->assertInstanceOf(\Carbon\Carbon::class, $document->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $document->updated_at);
        $this->assertNull($document->deleted_at); // Should be null for non-deleted documents
    }

    public function test_document_factory_creates_valid_menu_types()
    {
        $document = DocumentModel::factory()->create();

        $this->assertContains($document->menu, ['coiled_tubing', 'nitrogen']);
    }

    public function test_document_factory_creates_unique_documents()
    {
        $doc1 = DocumentModel::factory()->create();
        $doc2 = DocumentModel::factory()->create();

        $this->assertNotEquals($doc1->name, $doc2->name);
        $this->assertNotEquals($doc1->description, $doc2->description);
    }
}