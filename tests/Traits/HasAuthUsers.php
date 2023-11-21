<?php


namespace Tests\Traits;


use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

/**
 * Trait TestsModelSpecification
 * @package Tests\Traits
 */
trait HasAuthUsers
{
    /**
     * @var User
     */
    protected $admin;

    /**
     * @var User
     */
    protected $user;
    /**
     * @var User
     */
    protected $guest;

    public function setup(): void
    {
        parent::setUp();

        $this->runUserCreation();
    }

    protected function runUserCreation(): void
    {
        Role::create(['name' => 'guest']);
        $this->admin = factory(User::class)->create();
        $this->admin->roles()->sync([Role::create(['name' => 'admin'])->id, Role::create(['name' => 'user'])->id]);
        $this->guest = factory(User::class)->create();
        $this->user = factory(User::class)->create();
        $this->user->roles()->sync(Role::where('name', 'user')->first());
        $this->admin->save();
        $this->user->save();
    }
}
