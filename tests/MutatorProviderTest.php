<?php

namespace MacFJA\ValueProvider\Test;

use MacFJA\ValueProvider\MutatorProvider;

class MutatorProviderTest extends \PHPUnit_Framework_TestCase {
    //-- Getter tests
    /**
     * @param $object
     * @param $property
     * @param $expected
     *
     * @dataProvider dataProvider
     */
    public function testGetValue($object, $property, $expected) {
        $this->assertEquals($expected, MutatorProvider::getValue($object, $property));
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
        MutatorProvider::getValue($object, $property);
    }

    //-- Setter Test
    /**
     * @dataProvider dataProvider
     */
    public function testSetValue($object, $property, $value) {
        MutatorProvider::setValue($object, $property, $value);
        $this->assertEquals($value, MutatorProvider::getValue($object, $property));
    }

    /**
     * @dataProvider dataProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetValueException($object, $property, $value) {
        MutatorProvider::setValue($object, $property, $value);
    }

    //-- DataProvider
    public function dataProvider($name) {
        if ($name == 'testGetValue') {
            return array(
                array(new MutatorProviderTestMockObject(), 'value1', 'value1'),
                array(new MutatorProviderTestMockObject(), 'value2', true),
                array(new MutatorProviderTestMockObject(), 'value3', 'value3'),
                array(new MutatorProviderTestMockObject(), 'xPosition', 7),
                array(new MutatorProviderTestMockObject(), 'xPositive', true),
            );
        }

        if ($name == 'testGetValueException') {
            return array(
                array(new MutatorProviderTestMockObject(), 'value4'),
                array(new MutatorProviderTestMockObject(), 'value5'),
                array(new MutatorProviderTestMockObject(), 'value6'),
            );
        }

        if ($name == 'testSetValue') {
            return array(
                array(new MutatorProviderTestMockObject(), 'value7', 'value7'),
                array(new MutatorProviderTestMockObject(), 'value8', 'value8'),
                array(new MutatorProviderTestMockObject(), 'xPosition', 3),
            );
        }

        if ($name == 'testSetValueException') {
            return array(
                array(new MutatorProviderTestMockObject(), 'value4', 'value1'),
                array(new MutatorProviderTestMockObject(), 'value4', 'value2'),
                array(new MutatorProviderTestMockObject(), 'value4', 'value3'),
                array(new MutatorProviderTestMockObject(), 'value4', 'value4'),
                array(new MutatorProviderTestMockObject(), 'value5', 'value5'),
            );
        }

        throw new \InvalidArgumentException;
    }
}

class MutatorProviderTestMockObject {
    private $value7 = '';
    private $value8 = '';

    private $xPosition = 7;
    private $xPositive = true;

    public function getValue7() {
        return $this->value7;
    }
    public function getValue8() {
        return $this->value8;
    }
    public function setValue7($value) {
        $this->value7 = $value;
    }

    public function getValue1() {
        return 'value1';
    }
    public function isValue2() {
        return true;
    }
    private function getValue4() {
        throw new \Exception('Shoudn\'t be here'.__FILE__.':'.__LINE__);
    }
    private function setValue4() {
        throw new \Exception('Shoudn\'t be here'.__FILE__.':'.__LINE__);
    }

    public function getxPosition()
    {
        return $this->xPosition;
    }
    public function setxPosition($value)
    {
        $this->xPosition = $value;
    }
    public function isxPositive()
    {
        return $this->xPosition >= 0;
    }

    function __call($name, $arguments)
    {
        //--Getter
        if($name == 'getValue3') {
            return 'value3';
        }
        //--Setter
        if($name == 'setValue8') {
            $this->value8 = $arguments[0];
            return;
        }

        throw new \BadMethodCallException;
    }

}
