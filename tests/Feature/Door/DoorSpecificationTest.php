<?php

namespace Tests\Feature\Door;

use App\Models\Door;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestsModelSpecification;

class DoorSpecificationTest extends TestCase
{
    use TestsModelSpecification, RefreshDatabase;

    protected function setUp(): void
    {
        $this->className = Door::class;
        $this->factory = Door::factory();

        $this->fields = [
            'intro_text',
            'content',
            'media',
            'media_type',
            'question',
            'answer',
            'sortnr'
        ];
        $this->requiredFields = [
            'content',
            'question',
            'sortnr'
        ];
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_has_the_required_attributes()
    {
        $this->assertFields();
    }

    /**
     * @test
     */
    public function it_can_be_written_to_the_db()
    {
        $data = ['question' => 'Is this a test?'];
        $this->assertIsInDatabase($data);

    }

    /**
     * @test
     */
    public function it_cannot_be_written_to_the_db_without_required_fields()
    {
        $this->assertRequiredFields();
    }
}
