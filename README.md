# ValueProvider #

**ValueProvider** allow you to access to your object's property value without knowing the way the object have defined to do so.
Public property, getter/setter? Let's `MutatorAndPropertyProvider` take care of this.

## Features ##

Can access to object's properties values:

- With direct property access (like `$myObject->myProperty`)
    - Works with magic `__get` and `__set` (see below for examples and restriction)
- With mutator (getter/setter) (like `$myObject->getMyProperty()` and `$myObject->setMyProperty('new value')`)
    - Works with magic `__call` (see below for examples and restriction)
- With **doctrine** **`Metadata`** (see below for examples)

## Examples ##

### Property Access ###

Class Person

    class Person {
        public $firstName = '';
        public $lastName = '';
    }

Somewhere in your code

    $jdoe = new Person();
    $provider = new PropertyProvider();

    // ...

    $provider->setValue($jdoe, 'firstName', 'John');
    $provider->setValue($jdoe, 'lastName', 'Doe');

    // ...

    echo 'Hello ' . $provider->getValue($jdoe, 'firstName') . ' ' . $provider->getValue($jdoe, 'lastName');
    //Output : "Hello John Doe"

#### Notice ####

The properties have to be public to be accessed.

### Magic `__get` and `__set` properties access ###

Class Person

    class Person {
        private $_firstName = 'John';
        private $_lastName = 'Doe';

        function __get($name) {
            if (in_array($name, array('firstName', 'lastName')) {
                $propertyName = '_' . $name;
                return $this->$propertyName;
            }

            throw new \BadFunctionCallException;
        }

        function __set($name, $value) {
            if (in_array($name, array('firstName', 'lastName')) {
                $propertyName = '_' . $name;
                $this->$propertyName = $value;
                return;
            }

            throw new \BadFunctionCallException;
        }

        function __isset($name) {
            return in_array($name, array('firstName', 'lastName');
        }
    }

Somewhere in your code

    $jdoe = new Person();
    $provider = new PropertyProvider();

    // ...

    $provider->setValue($jdoe, 'firstName', 'John');
    $provider->setValue($jdoe, 'lastName', 'Doe');

    // ...

    echo 'Hello ' . $provider->getValue($jdoe, 'firstName') . ' ' . $provider->getValue($jdoe, 'lastName');
    //Output : "Hello John Doe"

#### Notice ####

The class rely on the `__isset` function to know if the property can be used with `__get` and `__set`

### Mutator Access (getter/setter) ###

Class Person

    class Person {
        private $_firstName = '';
        private $_lastName = '';
        private $_known = true;

        public function getFirstName() {
            return $this->_firstName;
        }
        public function setFirstName($value) {
            $this->_firstName = $value;
        }
        public function getLastName() {
            return $this->_lastName;
        }
        public function setLastName($value) {
            $this->_firstName = $value;
        }
        public function isKnown() {
            return $this->_known;
        }
        public function setKnown($flag) {
            $this->_known = $flag;
        }
    }

Somewhere in your code

    $jdoe = new Person();
    $provider = new MutatorProvider();

    // ...

    $provider->setValue($jdoe, 'firstName', 'John');
    $provider->setValue($jdoe, 'lastName', 'Doe');
    $provider->setValue($jdoe, 'known', false);

    // ...

    echo 'Hello ' . $provider->getValue($jdoe, 'firstName') . ' ' . $provider->getValue($jdoe, 'lastName');
    echo ', you are ' . ($provider->getValue($jdoe, 'known') ? '' : 'un') . 'known';
    //Output : "Hello John Doe, you are unknown"

#### Notice ####

The mutator have to be public to be accessed.
Getter are search in this order

- getMyProperty
- isMyProperty

### Magic `__call` mutator access ###

Class Person

    class Person {
        private $_firstName = 'John';
        private $_lastName = 'Doe';
        private $_known = true;

        function __call($name, $arguments) {
            switch ($name) {
                case 'getFirstName':
                    return $this->_firstName;
                case 'getLastName':
                    return $this->_lastName;
                case 'isKnown':
                    return $this->_known;
                case 'setFirstName':
                    $this->_firstName = $argument[0];
                    return;
                case 'setLastName':
                    $this->_lastName = $argument[0];
                    return;
                case 'setKnown':
                    $this->_known = $argument[0];
                    return;
            }

            throw new \BadFunctionCallException;
        }
    }

Somewhere in your code

    $jdoe = new Person();
    $provider = new MutatorProvider();

    // ...

    $provider->setValue($jdoe, 'firstName', 'John');
    $provider->setValue($jdoe, 'lastName', 'Doe');
    $provider->setValue($jdoe, 'known', false);

    // ...

    echo 'Hello ' . $provider->getValue($jdoe, 'firstName') . ' ' . $provider->getValue($jdoe, 'lastName');
    echo ', you are ' . ($provider->getValue($jdoe, 'known') ? '' : 'un') . 'known';
    //Output : "Hello John Doe, you are unknown"

#### Notice ####

The class rely on the implementation of the `__call` function:
the function MUST throw either `\BadFunctionCallException` or `\InvalidArgumentException` if the getter/setter doesn't exist

### Doctrine Metadata ###

Somewhere in your code

    /** @type EntityManager $entityManager */
    $class = 'MyClass';
    $id = 1234;

    $provider = new MetadataProvider();
    MetadataProvider::setEntityManager($entityManager);

    jdoe = $entityManager->find($class, $id);


    // ...

    $provider->setValue($jdoe, 'firstName', 'John');
    $provider->setValue($jdoe, 'lastName', 'Doe');
    $provider->setValue($jdoe, 'known', false);

    // ...

    echo 'Hello ' . $provider->getValue($jdoe, 'firstName') . ' ' . $provider->getValue($jdoe, 'lastName');
    echo ', you are ' . ($provider->getValue($jdoe, 'known') ? '' : 'un') . 'known';
    //Output : "Hello John Doe, you are unknown"

#### Notice ####

You have to set the Doctrine `EntityManager` to the `MetadataProvider

### MutatorAndPropertyProvider ###

This class try to access to the value with mutator or property.
It first try the mutator, if not success then try the property.