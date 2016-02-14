<?php

namespace MacFJA\ValueProvider\Test;

use MacFJA\ValueProvider\ChainProvider;

class ChainProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This method is called before the first test of this test class is run.
     *
     * @since Method available since Release 3.4.0
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        ChainProvider::setProviders(array(
            'MacFJA\ValueProvider\MutatorProvider',
            'MacFJA\ValueProvider\PropertyProvider',
            'MacFJA\ValueProvider\ReflectorProvider'
        ));
    }

    public function testProviders()
    {
        ChainProvider::setProviders(array(
            'MacFJA\ValueProvider\MutatorProvider',
            'MacFJA\ValueProvider\ReflectorProvider'
        ));

        self::assertEquals(
            array(
                'MacFJA\ValueProvider\MutatorProvider',
                'MacFJA\ValueProvider\ReflectorProvider'
            ),
            ChainProvider::getProviders()
        );

        ChainProvider::setProviders(array(
            'MacFJA\ValueProvider\MutatorProvider',
            'MacFJA\ValueProvider\PropertyProvider',
            'MacFJA\ValueProvider\ReflectorProvider'
        ));

        self::assertEquals(
            array(
                'MacFJA\ValueProvider\MutatorProvider',
                'MacFJA\ValueProvider\PropertyProvider',
                'MacFJA\ValueProvider\ReflectorProvider'
            ),
            ChainProvider::getProviders()
        );
    }

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
        $this->assertEquals($expected, ChainProvider::getValue($object, $property));
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
        ChainProvider::getValue($object, $property);
    }

    //-- Setter Test
    /**
     * @dataProvider dataProvider
     */
    public function testSetValue($object, $property, $value)
    {
        ChainProvider::setValue($object, $property, $value);
        $this->assertEquals($value, ChainProvider::getValue($object, $property));
    }

    /**
     * @dataProvider dataProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetValueException($object, $property)
    {
        ChainProvider::setValue($object, $property, 'error');
    }

    //-- DataProvider
    public function dataProvider($name)
    {
        if ($name == 'testGetValue') {
            if (version_compare(PHP_VERSION, '5.3.0') <= 0) {
                return array(
                    array(new ChainProviderTestMockObject(), 'publicVar', 'test1'),
                    array(new ChainProviderTestMockObject(), 'magicGet', 'test4'),
                    array(new ChainProviderTestMockObject(), 'getterSetter', 'test5'),
                    array(new ChainProviderTestMockObject(), 'magicGetterSetter', 'test6'),
                );
            }
            return array(
                array(new ChainProviderTestMockObject(), 'publicVar', 'test1'),
                array(new ChainProviderTestMockObject(), 'protectedVar', 'test2'),
                array(new ChainProviderTestMockObject(), 'privateVar', 'test3'),
                array(new ChainProviderTestMockObject(), 'magicGet', 'test4'),
                array(new ChainProviderTestMockObject(), 'getterSetter', 'test5'),
                array(new ChainProviderTestMockObject(), 'magicGetterSetter', 'test6'),
            );
        }

        if ($name == 'testGetValueException') {
            if (version_compare(PHP_VERSION, '5.3.0') <= 0) {
                return array(
                    array(new ChainProviderTestMockObject(), 'privateVar'),
                    array(new ChainProviderTestMockObject(), 'protectedVar'),
                    array(new ChainProviderTestMockObject(), 'protectedGetter'),
                    array(new ChainProviderTestMockObject(), 'privateGetter'),
                    array(new ChainProviderTestMockObject(), 'notExistingVar'),
                );
            }
            return array(
                array(new ChainProviderTestMockObject(), 'protectedGetter'),
                array(new ChainProviderTestMockObject(), 'privateGetter'),
                array(new ChainProviderTestMockObject(), 'notExistingVar'),
            );
        }

        if ($name == 'testSetValue') {
            if (version_compare(PHP_VERSION, '5.3.0') <= 0) {
                return array(
                    array(new ChainProviderTestMockObject(), 'publicVar', 'set1'),
                    array(new ChainProviderTestMockObject(), 'magicSet', 'set4'),
                    array(new ChainProviderTestMockObject(), 'getterSetter', 'set5'),
                    array(new ChainProviderTestMockObject(), 'magicGetterSetter', 'set6'),
                );
            }
            return array(
                array(new ChainProviderTestMockObject(), 'publicVar', 'set1'),
                array(new ChainProviderTestMockObject(), 'protectedVar', 'set2'),
                array(new ChainProviderTestMockObject(), 'privateVar', 'set3'),
                array(new ChainProviderTestMockObject(), 'magicSet', 'set4'),
                array(new ChainProviderTestMockObject(), 'getterSetter', 'set5'),
                array(new ChainProviderTestMockObject(), 'magicGetterSetter', 'set6'),
            );
        }

        if ($name == 'testSetValueException') {
            if (version_compare(PHP_VERSION, '5.3.0') <= 0) {
                return array(
                    array(new ChainProviderTestMockObject(), 'privateVar'),
                    array(new ChainProviderTestMockObject(), 'protectedVar'),
                    array(new ChainProviderTestMockObject(), 'protectedSetter'),
                    array(new ChainProviderTestMockObject(), 'privateSetter'),
                    array(new ChainProviderTestMockObject(), 'notExistingVar'),
                );
            }
            return array(
                array(new ChainProviderTestMockObject(), 'protectedSetter'),
                array(new ChainProviderTestMockObject(), 'privateSetter'),
                array(new ChainProviderTestMockObject(), 'notExistingVar'),
            );
        }

        throw new \InvalidArgumentException;
    }
}

class ChainProviderTestMockObject
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
