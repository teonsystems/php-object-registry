PHP Object Registry
===================

Store, search and retrieve object instances at one place, to avoid having
multiple entity instances in your application.



## Problem

```php
    class User {
        public static function factory ($id) {
            // Get from DB, by ID 1
            return new self($id);
        }
    }

    function f1 ()
    {
        $User1 = User::factory(1);     // Instantiates user from database
        echo $User1->getName();        // Outputs "Roger", correct (value from DB)

        // Change it
        $user1_id1->setName('NewName');
        echo $User1->getName();        // Outputs "NewName", correct

        // Call something else
        f2();
    }


    function f2 ()
    {
        $User1 = new User::factory(1);
        echo $User1->getName();        // Outputs "Roger" instead of "NewName", INCORRECT
    }
```



## Solution

```php
    class User {
        public static function factory ($id) {
            $ObjectRegistry = \Teon\ObjectRegistry\ObjectRegistry::getInstance();
            $User = $ObjectRegistry->find(self, $id);
            if (false === $User) {
                // Get from DB, by ID
                return new self($id);
            } else {
                return $User;
            }
        }
    }

    // ...rest of the code is exactly the same, and functions correctly
    // Third echo (in f2()) displays "NewName" as it should
```



## Installation as Zend Framework 2 module

First clone the repositories TO THE CORRECT LOCATION:
```
# Teon\Base is a prerequisite
git clone https://github.com/teonsystems/php-base            ./vendor/Teon/Base
git clone https://github.com/teonsystems/php-object-registry ./vendor/Teon/ObjectRegistry
```

Then edit ./config/application.config.php
```php
return array(
    'modules' => array(
        'Application',
        'Teon\Base',             // Add this
        'Teon\ObjectRegistry',   // Add this
    // ...
```
