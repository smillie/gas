<?php

require_once(dirname(__FILE__) . '/../functions.php');
require_once(dirname(__FILE__) . '/../user.php');

class GenerateUsernameTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this -> user = new User();
    }
    
    public function testOneWordEach()
    {
        $this -> user -> setName('John', 'Smith');
        $uid = $this -> user -> username();
        $this -> assertEquals($uid, 'jsmith');
    }
    
    public function testTwoWordSurname()
    {
        $this -> user -> setName('james', 'Van der Beek');
        $uid = $this -> user -> username();
        $this -> assertEquals($uid, 'jvanderbeek');
    }
    
    public function testDoubleBarrelled()
    {
        $this -> user -> setName('John', 'Smith-Doe');
        $uid = $this -> user -> username();
        $this -> assertEquals($uid, 'jsmith-doe');
    }
}
