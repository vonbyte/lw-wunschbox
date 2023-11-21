<?php


namespace Tests\Traits;


use Illuminate\Database\Eloquent\Model;

/**
 * Trait TestsEntityCrud
 * @package Tests\Traits
 */
trait TestsEntityCrud
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var Model
     */
    private $entity;
    /**
     * @var array
     */
    private $data;

    protected function assertEntitiesListed($routeName): void
    {
        $oldCount = $this->className::all()->count();
        factory($this->className, 5)->create();

        $this->json('GET', route($routeName))
            ->assertStatus(200)
            ->assertJsonCount($oldCount + 5);
    }

    protected function assertEntityShown($routeName): void
    {
        $this->setVariables();

        $this->json('GET', route($routeName,$this->data['id']))
            ->assertStatus(200)
            ->assertJson($this->data);
    }

    /**
     * @param $routeName
     * @param string $compareField
     */
    protected function assertEntityCreated($routeName, $compareField = 'name'): void
    {
        $this->setVariables(false);

        $this->json('post',route($routeName), $this->data)->assertStatus(201);
        $this->assertDatabaseHas($this->entity->getTable(), [$compareField => $this->data[$compareField]]);
    }

    /**
     * @param $routeName
     * @param string $updateField
     */
    protected function assertEntityUpdated($routeName, $updateField = 'name'): void
    {
        $this->setVariables();
        $updatedValue = $this->data[$updateField] . ' - updated';

        $this->json('patch',route($routeName, $this->data['id']), [$updateField => $updatedValue])->assertStatus(200);
        $this->assertDatabaseHas($this->entity->getTable(), ['id' => $this->data['id'], $updateField => $updatedValue]);
    }

    /**
     * @param $routeName
     */
    protected function assertEntityDeleted($routeName, $softDelete = false): void
    {
        $this->setVariables();
        $this->setVariables();
        $this->json('delete',route($routeName, $this->data['id']))->assertStatus(204);
        if ($softDelete) {
            $this->assertSoftDeleted($this->entity->getTable(), ['id' => $this->data['id']]);
        } else {
            $this->assertDatabaseMissing($this->entity->getTable(), ['id' => $this->data['id']]);
        }
    }

    /**
     * @param bool $create
     */
    private function setVariables($create = true): void
    {
        $this->entity = $create ? factory($this->className)->create() : factory($this->className)->make();
        $this->data = $this->entity->toArray();
    }


}
