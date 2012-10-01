<?php

require_once(dirname(__FILE__) . '/../functions.php');

class GeneratePasswordsTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultLength()
    {
        $password = generatePassword();
        $this -> assertEquals(8, strlen($password));
    }
    
    public function testMaxLength()
    {
        $password = generatePassword(46);
        $this -> assertEquals(46, strlen($password));
        
        $passwordLong = generatePassword(48);
        $this -> assertEquals(46, strlen($passwordLong));
    }
    

}
