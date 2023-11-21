<?php


namespace Tests\Traits;


use Illuminate\Database\Eloquent\Model;

/**
 * Trait TestsEntityCrud
 * @package Tests\Traits
 */
trait TestsEntityCrudViews
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

    protected function assertEntitiesInListView(string $routeName, string $view, string $visibleField = 'title'): void
    {
        factory($this->className, 3)->create();
        $values = $this->className::all()->pluck($visibleField)->toArray();
        $this->get(route($routeName))
            ->assertViewIs($view)
            ->assertSeeInOrder($values);
    }

    protected function assertEntityShown(string $routeName, string $view, string $visibleField = 'title'): void
    {
        $this->setVariables();

        $this->get(route($routeName,$this->data['id']))
            ->assertViewIs($view)
            ->assertSee($this->entity->$visibleField);
    }

    protected function assertEmptyFormIsShown(string $routeName, string $view, string $checkableField = 'title'): void
    {
        $this->setVariables();

        $this->get(route($routeName))
            ->assertViewIs($view)
            ->assertSee('<form')
            ->assertSeeInOrder([$checkableField,'value=""']);
    }

    /**
     * @param $routeName
     * @param string $compareField
     */
    protected function assertEntityCreated($routeName, $compareField = 'name'): void
    {
        $this->setVariables(false);

        $this->post(route($routeName), $this->data)->assertStatus(201);
        $this->assertDatabaseHas($this->entity->getTable(), [$compareField => $this->data[$compareField]]);
    }

    protected function assertFilledFormIsShown(string $routeName, string $view, string $checkField = 'title'): void
    {
        $this->setVariables();

        $this->get(route($routeName,$this->data['id']))
            ->assertViewIs($view)
            ->assertSee('<form')
            ->assertSeeInOrder([$checkField,$this->entity->$checkField]);
    }

    /**
     * @param $routeName
     * @param string $updateField
     */
    protected function assertEntityUpdated($routeName, $updateField = 'name'): void
    {
        $this->setVariables();
        $updatedValue = $this->data[$updateField] . ' - updated';

        $this->patch(route($routeName, $this->data['id']), [$updateField => $updatedValue])->assertStatus(200);
        $this->assertDatabaseHas($this->entity->getTable(), ['id' => $this->data['id'], $updateField => $updatedValue]);
    }

    /**
     * @param $routeName
     */
    protected function assertEntityDeleted($routeName, $softDelete = false): void
    {
        $this->setVariables();
        $this->delete(route($routeName, $this->data['id']))->assertStatus(204);
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
