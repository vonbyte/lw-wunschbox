<?php


namespace Tests\Traits;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

/**
 * Trait TestsModelSpecification
 * @package Tests\Traits
 */
trait TestsModelSpecification
{
    /**
     * @var Model
     */
    protected $className;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var array
     */
    protected $fields;
    /**
     * @var array
     */
    protected $requiredFields;

    /**
     * Assert that all fields are model attributes.
     */
    protected function assertFields(): void
    {
        $entity = $this->factory->make();
        $this->assertCount(count($this->fields), array_intersect($this->fields, array_keys($entity->getAttributes())));
    }

    /**
     * Assert that the entity can be written to the database.
     *
     * @param $data
     */
    protected function assertIsInDatabase($data, $relationShips = []): void
    {

        $factory = $this->factory;
        foreach ($relationShips as $key => $relationShip) {
            $factory = $factory->for($relationShip, $key);
        }
        $entity = $factory->create($data);

        $this->assertDatabaseHas($entity->getTable(), $data);
    }

    /**
     * Assert that without the required fields the entity cannot be written to the database.
     */
    protected function assertRequiredFields($relationShips = []): void
    {
        $oldCount = $this->className::all()->count();
        $factory = $this->factory;
        foreach ($relationShips as $key => $relationShip) {
            $factory = $factory->for($relationShip, $key);
        }
        $entity = $this->factory->make();
        $fields = collect($this->requiredFields);
        $fields->each(function ($field) use (&$entity) {
            $entity->$field = null;
        });
        try {
            $entity->save();
        } catch (QueryException $queryException) {
            $this->assertStringContainsString('cannot be null', $queryException->getMessage());
        }
        $this->assertCount($oldCount, $this->className::all());
    }
}
