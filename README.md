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
        echo $User1->getName();        // Outputs "Roger", as that is what is stored in database

        // Change it
        $user1_id1->setName('NewName');
        echo $User1->getName();        // Outputs "NewName", currect

        // Call something else
        f2();
    }


    function f2 ()
    {
        $User1 = new User::factory(1);
        echo $User1->getName();        // Outputs "Roger" instead of "NewName", incorrect
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