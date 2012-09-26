<?php

require_once(dirname(__FILE__) . '/../functions.php');

class GenerateUsernameTest extends PHPUnit_Framework_TestCase
{
    public function testOneWordEach()
    {
        $uid = generateUsername('John', 'Smith');
        $this -> assertEquals($uid, 'jsmith');
    }
    
    public function testTwoWordSurname()
    {
        $uid = generateUsername('james', 'Van der Beek');
        $this -> assertEquals($uid, 'jvanderbeek');
    }
    
    public function testDoubleBarrelled()
    {
        $uid = generateUsername('John', 'Smith-Doe');
        $this -> assertEquals($uid, 'jsmith-doe');
    }
}
