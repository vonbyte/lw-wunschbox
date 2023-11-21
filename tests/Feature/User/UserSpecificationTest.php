<?php

namespace Tests\Feature\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestsModelSpecification;

class UserSpecificationTest extends TestCase
{
    use TestsModelSpecification, RefreshDatabase;

    protected function setUp(): void
    {
        $this->factory = User::factory();
        $this->className = User::class;
        $this->fields = [
            'name',
            'password',
            'email',
            'remember_token'
        ];
        $this->requiredFields = [
            'name',
            'password',
            'email'
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
        $relations = $this->getRelations();
        $this->assertIsInDatabase(['name' => 'Tanja Bräther'],$relations);
    }

    /**
     * @test
     */
    public function it_cannot_be_written_to_the_db_without_required_fields()
    {
        $relations = $this->getRelations();
        $this->assertRequiredFields($relations);
    }

    /**
     * @test
     */
    public function it_has_a_connected_role()
    {
        $user = $this->factory
            ->forRole([
                'name' => 'admin'
            ])
            ->create();

        $this->assertEquals('admin', $user->role->name);
    }

    /**
     * @return array
     */
    protected function getRelations(): array
    {
        $role = Role::factory(['name' => 'user'])->create();
        $relations = [
            'role' => $role,
        ];
        return $relations;
    }
}
