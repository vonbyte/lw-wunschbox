<?php

namespace Tests\Feature\User;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestsModelSpecification;

class RoleSpecificationTest extends TestCase
{
    use TestsModelSpecification, RefreshDatabase;

    protected function setUp(): void
    {
        $this->factory = Role::factory();
        $this->className = Role::class;
        $this->fields = [
            'name'
        ];
        $this->requiredFields = [
            'name',
        ];
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_has_the_needed_attributes()
    {
        $this->assertFields();
    }

    /**
     * @test
     */
    public function it_is_written_to_the_database()
    {
        $this->assertIsInDatabase(['name' => 'admin']);
    }

    /**
     * @test
     */
    public function it_cannot_be_written_to_the_db_without_required_fields()
    {
        $this->assertRequiredFields();
    }

}
