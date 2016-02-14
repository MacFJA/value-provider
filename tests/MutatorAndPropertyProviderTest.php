<?php

namespace MacFJA\ValueProvider\Test;

use MacFJA\ValueProvider\MutatorAndPropertyProvider;

class MutatorAndPropertyProviderTest extends \PHPUnit_Framework_TestCase {
    //-- Getter tests
    /**
     * @param $object
     * @param $property
     * @param $expected
     *
     * @dataProvider dataProvider
     */
    public function testGetValue($object, $property, $expected) {
        $this->assertEquals($expected, MutatorAndPropertyProvider::getValue($object, $property));
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
        MutatorAndPropertyProvider::getValue($object, $property);
    }

    //-- Setter Test
    /**
     * @dataProvider dataProvider
     */
    public function testSetValue($object, $property, $value) {
        MutatorAndPropertyProvider::setValue($object, $property, $value);
        $this->assertEquals($value, MutatorAndPropertyProvider::getValue($object, $property));
    }

    /**
     * @dataProvider dataProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetValueException($object, $property, $value) {
        MutatorAndPropertyProvider::setValue($object, $property, $value);
    }

    //-- DataProvider
    public function dataProvider($name) {
        if ($name == 'testGetValue') {
            return array(
                array(new MutatorAndPropertyProviderTestMockObject(), 'value1', 'value1'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'value2', true),
                array(new MutatorAndPropertyProviderTestMockObject(), 'value3', 'value3'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'value4', 'value4'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'value5', 'value5'),
            );
        }

        if ($name == 'testGetValueException') {
            return array(
                array(new MutatorAndPropertyProviderTestMockObject(), 'privateVar'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'privateGet'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'privateIs'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'notExistingVar'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'notExistingGet'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'notExistingIs'),
            );
        }

        if ($name == 'testSetValue') {
            return array(
                array(new MutatorAndPropertyProviderTestMockObject(), 'setter', 'value7'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'magicSetter', 'value8'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'setVar', 'value9'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'magicSetVar', 'value10'),
            );
        }

        if ($name == 'testSetValueException') {
            return array(
                array(new MutatorAndPropertyProviderTestMockObject(), 'privateVar', 'value11'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'privateSet', 'value12'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'notExistingVar', 'value13'),
                array(new MutatorAndPropertyProviderTestMockObject(), 'notExistingSet', 'value14'),
            );
        }

        throw new \InvalidArgumentException;
    }
}

class MutatorAndPropertyProviderTestMockObject {
    private $privateVar = '';
    private function getPrivateGet() { throw new \Exception; }
    private function isPrivateIs() { throw new \Exception; }
    //Getter property
    public $value1 = 'value1';
    public function isValue2() { return true; }
    public function getValue3() { return 'value3'; }
    function __call($name, $arguments) {
        if($name == 'getValue4') { return 'value4'; }
        if($name == 'setMagicSetter') { $this->_magicSetter = $arguments[0]; return; }

        throw new \BadFunctionCallException;
    }
    function __get($name) {
        if($name == 'value5') { return 'value5'; }

        throw new \BadFunctionCallException;
    }
    function __set($name, $value) {
        if($name == 'magicSetVar') { $this->_setMagicVar = $value; return; }

        throw new \BadFunctionCallException;
    }
    function __isset($name) {
        return in_array($name, array('value5', 'magicSetVar'));
    }

    private $_setter;
    private $_magicSetter;
    public $setVar;
    private $_setMagicVar;
    public function setSetter($value) { $this->_setter = $value; }
    public function getSetter() { return $this->_setter; }
    public function getMagicSetter() { return $this->_magicSetter; }
    public function getMagicSetVar() { return $this->_setMagicVar; }
    private function setPrivateSet() { return $this->_setMagicVar; }
}
