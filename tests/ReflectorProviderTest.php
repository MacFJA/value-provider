<?php

namespace MacFJA\ValueProvider\Test;

use MacFJA\ValueProvider\ReflectorProvider;

class ReflectorProviderTest extends \PHPUnit_Framework_TestCase
{
    //-- Getter tests
    /**
     * @param $object
     * @param $property
     * @param $expected
     *
     * @dataProvider dataProvider
     */
    public function testGetValue($object, $property, $expected)
    {
        $this->assertEquals($expected, ReflectorProvider::getValue($object, $property));
    }

    /**
     * @param $object
     * @param $property
     * @param $expected
     *
     * @dataProvider dataProvider
     * @requires PHP 5.3
     */
    public function testGetValueAccessible($object, $property, $expected)
    {
        $this->assertEquals($expected, ReflectorProvider::getValue($object, $property));
    }

    /**
     * @param $object
     * @param $property
     *
     * @dataProvider dataProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testGetValueException($object, $property)
    {
        ReflectorProvider::getValue($object, $property);
    }

    //-- Setter Test
    /**
     * @dataProvider dataProvider
     */
    public function testSetValue($object, $property, $value)
    {
        ReflectorProvider::setValue($object, $property, $value);
        $this->assertEquals($value, ReflectorProvider::getValue($object, $property));
    }

    /**
     * @dataProvider dataProvider
     * @requires PHP 5.3
     */
    public function testSetValueAccessible($object, $property, $value)
    {
        ReflectorProvider::setValue($object, $property, $value);
        $this->assertEquals($value, ReflectorProvider::getValue($object, $property));
    }

    /**
     * @dataProvider dataProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetValueException($object, $property, $value)
    {
        ReflectorProvider::setValue($object, $property, $value);
    }

    //-- DataProvider
    public function dataProvider($name)
    {
        if ($name == 'testGetValueAccessible') {
            return array(
                array(new ReflectorProviderTestMockObject(), 'value1', 'my first value'),
                array(new ReflectorProviderTestMockObject(), 'value2', 'the second value'),
                array(new ReflectorProviderTestMockObject(), 'value3', 'the third one'),
            );
        }

        if ($name == 'testGetValue') {
            return array(
                array(new ReflectorProviderTestMockObject(), 'value1', 'my first value'),
            );
        }

        if ($name == 'testGetValueException') {
            if (version_compare(PHP_VERSION, '5.3.0') <= 0) {
                return array(
                    array(new ReflectorProviderTestMockObject(), 'value2'),
                    array(new ReflectorProviderTestMockObject(), 'value3'),
                    array(new ReflectorProviderTestMockObject(), 'value4'),
                    array(new ReflectorProviderTestMockObject(), 'value5'),
                );
            }
            return array(
                array(new ReflectorProviderTestMockObject(), 'value4'),
                array(new ReflectorProviderTestMockObject(), 'value5'),
            );
        }

        if ($name == 'testSetValue') {
            return array(
                array(new ReflectorProviderTestMockObject(), 'set_value1', 'value7'),
            );
        }

        if ($name == 'testSetValueAccessible') {
            return array(
                array(new ReflectorProviderTestMockObject(), 'set_value1', 'value7'),
                array(new ReflectorProviderTestMockObject(), 'set_value2', 'value8'),
                array(new ReflectorProviderTestMockObject(), 'set_value3', 'hello world'),
            );
        }

        if ($name == 'testSetValueException') {
            if (version_compare(PHP_VERSION, '5.3.0') <= 0) {
                return array(
                    array(new ReflectorProviderTestMockObject(), 'set_value2', 'value8'),
                    array(new ReflectorProviderTestMockObject(), 'set_value3', 'hello world'),
                    array(new ReflectorProviderTestMockObject(), 'value4', 'value4'),
                    array(new ReflectorProviderTestMockObject(), 'value5', 'value5'),
                );
            }

            return array(
                array(new ReflectorProviderTestMockObject(), 'value4', 'value4'),
                array(new ReflectorProviderTestMockObject(), 'value5', 'value5'),
            );
        }

        throw new \InvalidArgumentException;
    }
}

class ReflectorProviderTestMockObject
{
    public $value1 = 'my first value';
    protected $value2 = 'the second value';
    private $value3 = 'the third one';
    public $set_value1 = false;
    protected $set_value2 = 'not yet';
    private $set_value3 = null;
}
