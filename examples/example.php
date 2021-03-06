<?php

include __DIR__ . '/lib/Teon/Base/Loader.php';
include __DIR__ . '/lib/Teon/Base/Singleton/AbstractSingleton.php';
include __DIR__ . '/../autoload_register.php';



/*
 * Define some generic class to demonstrate the case
 */
class User {
    protected $_id   = false;
    protected $_name = '';

    public function __construct ($id) {
        $this->_id = $id;
    }

    public function getId () {
        return $this->_id;
    }

    public function getName () {
        return $this->_name;
    }
    public function setName ($name) {
        $this->_name = $name;
    }
}

class Car {
    protected $_id   = false;
    protected $_name = '';

    public function __construct ($id) {
        $this->_id = $id;
    }

    public function getId () {
        return $this->_id;
    }

    public function getName () {
        return $this->_name;
    }
    public function setName ($name) {
        $this->_name = $name;
    }
}



$ObjectRegistry = \Teon\ObjectRegistry\ObjectRegistry::getInstance();



// Create two cars, correct behaviour
$Car1_id1 = new Car(1);
$Car1_id1->setName('Tesla');
$ObjectRegistry->store($Car1_id1);
echo "Object Car 1 name: ". $Car1_id1->getName() ."\n";

$Car2_id2 = new Car(2);
$Car2_id2->setName('Tata');
$ObjectRegistry->store($Car2_id2);
echo "Object Car 2 name: ". $Car2_id2->getName() ."\n";



// Create two users, correct behaviour
$User1_id1 = new User(1);
$User1_id1->setName('Alice');
$ObjectRegistry->store($User1_id1);
echo "Object User 1 name: ". $User1_id1->getName() ."\n";

$User2_id2 = new User(2);
$User2_id2->setName('Bob');
$ObjectRegistry->store($User2_id2);
echo "Object User 2 name: ". $User2_id2->getName() ."\n";

// Find User with ID1, store in User11
echo "Does user with ID 1 exists in the object registry?   ";
//if ($ObjectRegistry->exists('User', '1')) {
if ($ObjectRegistry->exists('User', '1')) {
    echo "Yes\n";
} else {
    echo "No\n";
}

$User1_clone = $ObjectRegistry->find('User', '1');
echo "Object 1 clone name: ". $User1_clone->getName() ."\n";

// Show internal data
$ObjectRegistry->dump();

// This one must return error, as it has the same ID as $User1, but is actually a different instance
echo "\n\nINFO: THE FOLLOWING ERROR IS A RESULT OF EXPECTED BEHAVIOUR!\n\n";
$User3_id1 = new User(1);
$User3_id1->setName('Charlie');
$ObjectRegistry->store($User3_id1);
