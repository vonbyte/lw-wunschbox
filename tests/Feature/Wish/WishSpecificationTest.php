<?php

namespace Tests\Feature\Wish;

use App\Models\User;
use App\Models\Wish;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestsModelSpecification;

class WishSpecificationTest extends TestCase
{
    use TestsModelSpecification, RefreshDatabase;

    protected function setUp(): void
    {
        $this->fields = [
            'name',
            'description',
            'image',
            'links',
            'admin_notes',
            'sortnr',
            'state',
            'shipping_state',
            'shipping_company',
            'trackingnumber',
            'delivery_date'
        ];
        $this->requiredFields = [
            'name',
            'description',
            'state',
        ];
        $this->className = Wish::class;
        $this->factory = Wish::factory();

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
        $data = ['name' => 'MyWish'];
        $relations = $this->getRelations();
        $this->assertIsInDatabase($data,$relations);

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
     * @return array
     */
    protected function getRelations(): array
    {
        $user = User::factory(['name' => 'User'])->forRole(['name' => 'user'])->create();
        $presentee = User::factory(['name' => 'Presentee'])->forRole(['name' => 'user'])->create();
        $relations = [
            'user' => $user,
            'presentee' => $presentee,
        ];
        return $relations;
    }

    /**
     * @test
     */
    public function it_comes_from_a_user()
    {
        $wish = Wish::factory()
            ->for(User::factory()->forRole(['name' => 'user']),'user')
            ->for(User::factory()->forRole(['name' => 'user']),'presentee')
            ->create();

        $this->assertInstanceOf(User::class, $wish->user);
    }

    /**
     * @test
     */
    public function it_has_a_presentee()
    {
        $wish = Wish::factory()
            ->for(User::factory()->forRole(['name' => 'user']),'user')
            ->for(User::factory()->forRole(['name' => 'user']),'presentee')
            ->create();

        $this->assertInstanceOf(User::class, $wish->presentee);
    }
}
