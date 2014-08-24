<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dev
 * Date: 23/08/2014
 * Time: 18:58
 * To change this template use File | Settings | File Templates.
 */

namespace MacFJA\Tests\ValueProvider;

use MacFJA\ValueProvider\PropertyProvider;

class PropertyProviderTest extends \PHPUnit_Framework_TestCase {
    //-- Getter tests
    /**
     * @param $object
     * @param $property
     * @param $expected
     *
     * @dataProvider dataProvider
     */
    public function testGetValue($object, $property, $expected) {
        $this->assertEquals($expected, PropertyProvider::getValue($object, $property));
    }

    /**
     * @param $object
     * @param $property
     *
     * @dataProvider dataProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testGetValueException($object, $property) {
        PropertyProvider::getValue($object, $property);
    }

    //-- Setter Test
    /**
     * @dataProvider dataProvider
     */
    public function testSetValue($object, $property, $value) {
        PropertyProvider::setValue($object, $property, $value);
        $this->assertEquals($value, PropertyProvider::getValue($object, $property));
    }

    /**
     * @dataProvider dataProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetValueException($object, $property, $value) {
        PropertyProvider::setValue($object, $property, $value);
    }

    //-- DataProvider
    public function dataProvider($name) {
        if ($name == 'testGetValue') {
            return array(
                array(new PropertyProviderTestMockObject(), 'value1', 'value1'),
                array(new PropertyProviderTestMockObject(), 'value2', true),
                array(new PropertyProviderTestMockObject(), 'value3', 'value3'),
            );
        }

        if ($name == 'testGetValueException') {
            return array(
                array(new PropertyProviderTestMockObject(), 'value4'),
                array(new PropertyProviderTestMockObject(), 'value5'),
                array(new PropertyProviderTestMockObject(), 'value6'),
            );
        }

        if ($name == 'testSetValue') {
            return array(
                array(new PropertyProviderTestMockObject(), 'value7', 'value7'),
                array(new PropertyProviderTestMockObject(), 'value8', 'value8'),
            );
        }

        if ($name == 'testSetValueException') {
            return array(
                array(new PropertyProviderTestMockObject(), 'value4', 'value1'),
                array(new PropertyProviderTestMockObject(), 'value4', 'value2'),
                array(new PropertyProviderTestMockObject(), 'value4', 'value3'),
                array(new PropertyProviderTestMockObject(), 'value4', 'value4'),
                array(new PropertyProviderTestMockObject(), 'value5', 'value5'),
            );
        }

        throw new \InvalidArgumentException;
    }
}

class PropertyProviderTestMockObject {
    public $value1 = 'value1';
    public $value2 = true;
    private $value4 = 'value4';
    public  $value7 = '';
    private $_value8 = '';

    function __get($name)
    {
        if($name == 'value3') {
            return 'value3';
        }
        if($name == 'value8') {
            return $this->_value8;
        }

        throw new \BadMethodCallException;
    }

    function __isset($name) {
        return ($name == 'value3' || $name == 'value8');
    }

    function __set($name, $value) {
        if($name == 'value8') {
            $this->_value8 = $value;
            return;
        }

        throw new \BadMethodCallException;
    }

}
