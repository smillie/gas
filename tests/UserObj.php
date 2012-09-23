<?php

require_once(dirname(__FILE__) . '/../user.php');

class UserClassTest extends PHPUnit_Framework_TestCase
{
	private $instance;
	
	protected function setUp()
	{
		$this -> instance = new User();
	}
	
    public function testSucceedingSpaces()
    {
        $name = $this -> instance -> tidy("Bob ");
		$this -> assertEquals('Bob', $name);
	}
	
	public function testPrecedingSpaces()
	{
        $name = $this -> instance -> tidy("  Bob ");
		$this -> assertEquals('Bob', $name);
    }
	
	public function testNoSpaces()
	{
		$name = $this -> instance -> tidy("Bob");
		$this -> assertEquals('Bob', $name);
	}
	
	public function testCaseAllUpper()
	{
		$name = $this -> instance -> tidy("BOB");
		$this -> assertEquals('Bob', $name);
	}
	
	public function testCaseAllLower()
	{
		$name = $this -> instance -> tidy("bob");
		$this -> assertEquals('Bob', $name);
	}
	
	public function testCaseAlreadyCorrect()	
	{
		$name = $this -> instance -> tidy("Bob");
		$this -> assertEquals('Bob', $name);
	}
	
	public function testCaseDoubleBarrelAlreadyCorrect()
	{
	    $name = $this -> instance -> tidy("John-Doe");
	    $this -> assertEquals('John-Doe', $name);
	}
	
	public function testCaseDoubleBarrelAllCaps()
	{
	    $name = $this -> instance -> tidy("JOHN-DOE");
	    $this -> assertEquals('John-Doe', $name);
	}
	
	public function testCaseDoubleBarrelAllLower()
	{
	    $name = $this -> instance -> tidy("john-doe");
	    $this -> assertEquals('John-Doe', $name);
	}
	
	public function testNoForename()
	{
		$validation = $this -> instance -> validate();
		$this -> assertContains("No first name entered", $validation);
	}
	
	public function testNoSurname()
	{
		$validation = $this -> instance -> validate();
		$this -> assertContains("No last name entered", $validation);
	}
	
	public function testNoEmail()
	{
		$validation = $this -> instance -> validate();
		$this -> assertContains("No email address entered", $validation);
	}
	
	/*
	* test cases taken from
	* http://blogs.msdn.com/b/testing123/archive/2009/02/05/email-address-test-cases.aspx
	*
	*/
	public function testValidEmailAddresses()
	{
		$valid = array(
			'email@domain.com',
			'firstname.lastname@domain.com',
			'email@subdomain.domain.com',
			'firstname+lastname@domain.com',
			'email@123.123.123.123',
			'email@[123.123.123.123]',
			'"email"@domain.com',
			'1234567890@domain.com',
			'email@domain-one.com',
			'_______@domain.com',
			'email@domain.name',
			'email@domain.co.jp',
			'firstname-lastname@domain.com',
			'john.smith@strath.ac.uk'
		);
		
		foreach ($valid as $email)
		{
			$this -> instance -> setEmail($email);
			$validation = $this -> instance -> validate();
			$this -> assertNotContains("Invalid email address entered", $validation);
		}
	}
	
	public function testInvalidEmailAddresses()
	{
		$invalid = array(
			'plainaddress',
			'#@%^%#$@#$@#.com',
			'@domain.com',
			'Joe Smith <email@domain.com>',
			'email.domain.com',
			'email@domain@domain.com',
			'.email@domain.com',
			'email.@domain.com',
			'email..email@domain.com',
			'email@domain.com (Joe Smith)',
			'email@-domain.com'
		);
		
		foreach ($invalid as $email)
		{
			$this -> instance -> setEmail($email);
			$validation = $this -> instance -> validate();
			$this -> assertContains("Invalid email address entered", $validation);
		}
	}
	
	public function testNoStudentNumber()
	{
		$validation = $this -> instance -> validate();
		$this -> assertContains("Student number invalid", $validation);
	}
	
	public function testShortStudentNumber()
	{
		$this -> instance -> setStudentNumber(123);
		$validation = $this -> instance -> validate();
		$this -> assertContains("Student number invalid", $validation);
	}
	
	public function testLongStudentNumber()
	{
		$this -> instance -> setStudentNumber(1234567890);
		$validation = $this -> instance -> validate();
		$this -> assertContains("Student number invalid", $validation);
	}
	
	public function testStringStudentNumber()
	{
		$this -> instance -> setStudentNumber("wibbledys");
		$validation = $this -> instance -> validate();
		$this -> assertContains("Student number invalid", $validation);
	}
	
	public function testValidStudentNumber()
	{
		$this -> instance -> setStudentNumber(123456789);
		$validation = $this -> instance -> validate();
		$this -> assertNotContains("Student number invalid", $validation);
	}
}

?>
