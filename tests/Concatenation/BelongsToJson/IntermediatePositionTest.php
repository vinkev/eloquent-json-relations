<?php

namespace Tests\Concatenation\BelongsToJson;

use Tests\Models\Country;
use Tests\TestCase;

class IntermediatePositionTest extends TestCase
{
    public function testLazyLoading()
    {
        $permissions = Country::find(71)->permissions;

        $this->assertEquals([81, 82, 83], $permissions->pluck('id')->all());
    }

    public function testLazyLoadingWithObjects()
    {
        if (in_array($this->connection, ['sqlite', 'sqlsrv'])) {
            $this->markTestSkipped();
        }

        $permissions = Country::find(71)->permissions2;

        $this->assertEquals([81, 82, 83], $permissions->pluck('id')->all());
    }

    public function testEagerLoading()
    {
        $countries = Country::with('permissions')->get();

        $this->assertEquals([81, 82, 83], $countries[0]->permissions->pluck('id')->all());
        $this->assertEquals([], $countries[1]->permissions->pluck('id')->all());
        $this->assertEquals([83, 84], $countries[2]->permissions->pluck('id')->all());
    }

    public function testEagerLoadingWithObjects()
    {
        if (in_array($this->connection, ['sqlite', 'sqlsrv'])) {
            $this->markTestSkipped();
        }

        $countries = Country::with('permissions2')->get();

        $this->assertEquals([81, 82, 83], $countries[0]->permissions2->pluck('id')->all());
        $this->assertEquals([], $countries[1]->permissions2->pluck('id')->all());
        $this->assertEquals([83, 84], $countries[2]->permissions2->pluck('id')->all());
    }

    public function testExistenceQuery()
    {
        $countries = Country::has('permissions')->get();

        $this->assertEquals([71, 73], $countries->pluck('id')->all());
    }

    public function testExistenceQueryWithObjects()
    {
        if (in_array($this->connection, ['sqlite', 'sqlsrv'])) {
            $this->markTestSkipped();
        }

        $countries = Country::has('permissions2')->get();

        $this->assertEquals([71, 73], $countries->pluck('id')->all());
    }
}
