<?php

use PHPUnit\Framework\TestCase;
use ChinaDivisions\Division;

class DivisionTest extends TestCase
{
    public function testGetSelf(): Division
    {
        $division = new Division('110101');

        $self = $division->self();

        $this->assertEquals('东城区', $self['divisionName']);

        return $division;
    }

    /**
     * @depends testGetSelf
     */
    public function testGetChildren(Division $division)
    {
        $children = $division->children();

        $this->assertEquals(17, count($children));

        return $division;
    }

    /**
     * @depends testGetChildren
     */
    public function testGetParent(Division $division)
    {
        $self = $division->parent()->self();

        $this->assertEquals('北京市', $self['divisionName']);

        return $division;
    }

    /**
     * @depends testGetParent
     */
    public function testGetAncestors(Division $division)
    {
        $ancestors = $division->ancestors();

        $this->assertEquals(3, count($ancestors));

        return $division;
    }

    /**
     * @depends testGetAncestors
     */
    public function testGetBreadcrumb(Division $division)
    {
        $address = $division->breadcrumb();

        $this->assertEquals('北京北京市东城区', $address);

        return $address;
    }

    /**
     * @depends testGetAncestors
     * @depends testGetBreadcrumb
     */
    public function testGuessAddress(Division $division, $address)
    {
        $this->markTestSkipped('Skipped due to unstable data source.');

        $result = $division->guess('万达', $address);

        $this->assertTrue(count($result) > 0);
    }
}
