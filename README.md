PHP Object Registry
===================

Store, search and retrieve object instances at one place, to avoid having
multiple entity instances in your application.

Example:


In file1.php:

    function f1 ()
    {
        $user1_id1 = new User(1);
        echo $user1_id1->getName();   // Outputs "Roger", as that is what is stored in database

        // Change it
        $user1_id1->setName('NewName');
        # ...
        # Call some function from file2.php, f2 for example
    }



In file2.php:

    function f2 ()
    {
        $user2_id1 = new User(1);
        echo $user2_id1->getName();
        # outputs "Roger" instead of "NewName", because another user object was
        # created from DB
    }
