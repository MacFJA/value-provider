<?php

namespace MacFJA\Tests\ValueProvider;

use MacFJA\ValueProvider\GuessProvider;

class GuessProviderTest extends \PHPUnit_Framework_TestCase
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
        $this->assertEquals($expected, GuessProvider::getValue($object, $property));
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
        GuessProvider::getValue($object, $property);
    }

    //-- Setter Test
    /**
     * @dataProvider dataProvider
     */
    public function testSetValue($object, $property, $value)
    {
        GuessProvider::setValue($object, $property, $value);
        $this->assertEquals($value, GuessProvider::getValue($object, $property));
    }

    /**
     * @dataProvider dataProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetValueException($object, $property)
    {
        GuessProvider::setValue($object, $property, 'error');
    }

    //-- DataProvider
    public function dataProvider($name)
    {
        if ($name == 'testGetValue') {
            if (version_compare(PHP_VERSION, '5.3.0') <= 0) {
                return array(
                    array(new GuessProviderTestMockObject(), 'publicVar', 'test1'),
                    array(new GuessProviderTestMockObject(), 'magicGet', 'test4'),
                    array(new GuessProviderTestMockObject(), 'getterSetter', 'test5'),
                    array(new GuessProviderTestMockObject(), 'magicGetterSetter', 'test6'),
                );
            }
            return array(
                array(new GuessProviderTestMockObject(), 'publicVar', 'test1'),
                array(new GuessProviderTestMockObject(), 'protectedVar', 'test2'),
                array(new GuessProviderTestMockObject(), 'privateVar', 'test3'),
                array(new GuessProviderTestMockObject(), 'magicGet', 'test4'),
                array(new GuessProviderTestMockObject(), 'getterSetter', 'test5'),
                array(new GuessProviderTestMockObject(), 'magicGetterSetter', 'test6'),
            );
        }

        if ($name == 'testGetValueException') {
            if (version_compare(PHP_VERSION, '5.3.0') <= 0) {
                return array(
                    array(new GuessProviderTestMockObject(), 'privateVar'),
                    array(new GuessProviderTestMockObject(), 'protectedVar'),
                    array(new GuessProviderTestMockObject(), 'protectedGetter'),
                    array(new GuessProviderTestMockObject(), 'privateGetter'),
                    array(new GuessProviderTestMockObject(), 'notExistingVar'),
                );
            }
            return array(
                array(new GuessProviderTestMockObject(), 'protectedGetter'),
                array(new GuessProviderTestMockObject(), 'privateGetter'),
                array(new GuessProviderTestMockObject(), 'notExistingVar'),
            );
        }

        if ($name == 'testSetValue') {
            if (version_compare(PHP_VERSION, '5.3.0') <= 0) {
                return array(
                    array(new GuessProviderTestMockObject(), 'publicVar', 'set1'),
                    array(new GuessProviderTestMockObject(), 'magicSet', 'set4'),
                    array(new GuessProviderTestMockObject(), 'getterSetter', 'set5'),
                    array(new GuessProviderTestMockObject(), 'magicGetterSetter', 'set6'),
                );
            }
            return array(
                array(new GuessProviderTestMockObject(), 'publicVar', 'set1'),
                array(new GuessProviderTestMockObject(), 'protectedVar', 'set2'),
                array(new GuessProviderTestMockObject(), 'privateVar', 'set3'),
                array(new GuessProviderTestMockObject(), 'magicSet', 'set4'),
                array(new GuessProviderTestMockObject(), 'getterSetter', 'set5'),
                array(new GuessProviderTestMockObject(), 'magicGetterSetter', 'set6'),
            );
        }

        if ($name == 'testSetValueException') {
            if (version_compare(PHP_VERSION, '5.3.0') <= 0) {
                return array(
                    array(new GuessProviderTestMockObject(), 'privateVar'),
                    array(new GuessProviderTestMockObject(), 'protectedVar'),
                    array(new GuessProviderTestMockObject(), 'protectedSetter'),
                    array(new GuessProviderTestMockObject(), 'privateSetter'),
                    array(new GuessProviderTestMockObject(), 'notExistingVar'),
                );
            }
            return array(
                array(new GuessProviderTestMockObject(), 'protectedSetter'),
                array(new GuessProviderTestMockObject(), 'privateSetter'),
                array(new GuessProviderTestMockObject(), 'notExistingVar'),
            );
        }

        throw new \InvalidArgumentException;
    }
}

class GuessProviderTestMockObject
{
    // Properties
    public $publicVar = 'test1';
    protected $protectedVar = 'test2';
    private $privateVar = 'test3';

    // Magic Properties
    private $_magicSet = false;

    function __get($name)
    {
        if ($name == 'magicGet') {
            return 'test4';
        } elseif ($name == 'magicSet') {
            return $this->_magicSet;
        }
        throw new \BadMethodCallException;
    }

    function __isset($name)
    {
        return ($name == 'magicGet' || $name == 'magicSet');
    }

    function __set($name, $value)
    {
        if ($name == 'magicSet') {
            $this->_magicSet = $value;
            return;
        }
        throw new \BadMethodCallException;
    }

    // Getter / Setter
    private $_getterSetter = 'test5';

    public function getGetterSetter()
    {
        return $this->_getterSetter;
    }

    protected function getProtectedGetter()
    {
        return 'error1';
    }

    private function getPrivateGetter()
    {
        return 'error2';
    }

    public function setGetterSetter($value)
    {
        $this->_getterSetter = $value;
    }

    protected function setProtectedSetter($value)
    {
        return 'error3';
    }

    private function setPrivateSetter($value)
    {
        return 'error4';
    }

    // Magic Getter / Setter
    private $_magicGetterSetter = 'test6';

    function __call($name, $arguments)
    {
        //--Getter
        if ($name == 'getMagicGetterSetter') {
            return $this->_magicGetterSetter;
        }
        //--Setter
        if ($name == 'setMagicGetterSetter') {
            $this->_magicGetterSetter = $arguments[0];
            return;
        }

        throw new \BadMethodCallException;
    }
}
