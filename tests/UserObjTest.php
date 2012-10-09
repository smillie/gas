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
		$input = "Bob ";
        $name = $this -> instance -> tidy($input);
		$this -> assertEquals('Bob', $name);
		
		$this -> instance -> setName($input, "");
		$this -> assertEquals('Bob', $this -> instance -> firstName());
	}
	
	public function testPrecedingSpaces()
	{
		$input = "  Bob ";
        $name = $this -> instance -> tidy($input);
		$this -> assertEquals('Bob', $name);
		
		$this -> instance -> setName($input, "");
		$this -> assertEquals('Bob', $this -> instance -> firstName());
    }
	
	public function testNoSpaces()
	{
		$input = "Bob";
		$name = $this -> instance -> tidy($input);
		$this -> assertEquals('Bob', $name);
		
		$this -> instance -> setName($input, "");
		$this -> assertEquals('Bob', $this -> instance -> firstName());
	}
	
	public function testCaseAllUpper()
	{
		$input = "BOB";
		
		$name = $this -> instance -> tidy($input);
		$this -> assertEquals('Bob', $name);
		
		$this -> instance -> setName($input, "");
		$this -> assertEquals('Bob', $this -> instance -> firstName());
	}
	
	public function testCaseAllLower()
	{
		$input = "bob";
		$name = $this -> instance -> tidy($input);
		$this -> assertEquals('Bob', $name);
		
		$this -> instance -> setName($input, "");
		$this -> assertEquals('Bob', $this -> instance -> firstName());
	}
	
	public function testCaseAlreadyCorrect()	
	{
		$input = "Bob";
		$name = $this -> instance -> tidy($input);
		$this -> assertEquals('Bob', $name);
		
		$this -> instance -> setName($input, "");
		$this -> assertEquals('Bob', $this -> instance -> firstName());
	}
	
	public function testCaseDoubleBarrelAlreadyCorrect()
	{
		$input = "John-Doe";
		$expected = "John-Doe";
		
	    $name = $this -> instance -> tidy($input);
	    $this -> assertEquals('John-Doe', $name);
		
		$this -> instance -> setName("", $input);
		$this -> assertEquals($expected, $this -> instance -> lastName());
	}
	
	public function testCaseDoubleBarrelAllCaps()
	{
		$input = "JOHN-DOE";
		$expected = "John-Doe";
		
	    $name = $this -> instance -> tidy($input);
	    $this -> assertEquals('John-Doe', $name);
		
		$this -> instance -> setName("", $input);
		$this -> assertEquals($expected, $this -> instance -> lastName());
	}
	
	public function testCaseDoubleBarrelAllLower()
	{
		$input = "john-doe";
		$expected = "John-Doe";
		
	    $name = $this -> instance -> tidy($input);
	    $this -> assertEquals('John-Doe', $name);
		
		$this -> instance -> setName("", $input);
		$this -> assertEquals($expected, $this -> instance -> lastName());
	}
	
	public function testNoForename()
	{
		$validation = $this -> instance -> validate();
		$this -> assertContains("No first name entered", $validation);
		$this -> assertNull($this -> instance -> firstName());
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
		
		$validation = $this -> instance -> validateStudentNumber();
		$this -> assertContains("Student number invalid", $validation);
	}
	
	public function testShortStudentNumber()
	{
		$this -> instance -> setStudentNumber(123);
		$validation = $this -> instance -> validate();
		$this -> assertContains("Student number invalid", $validation);
		
		$validation = $this -> instance -> validateStudentNumber();
		$this -> assertContains("Student number invalid", $validation);
	}
	
	public function testLongStudentNumber()
	{
		$this -> instance -> setStudentNumber(1234567890);
		$validation = $this -> instance -> validate();
		$this -> assertContains("Student number invalid", $validation);
		
		$validation = $this -> instance -> validateStudentNumber();
		$this -> assertContains("Student number invalid", $validation);
	}
	
	public function testStringStudentNumber()
	{
		$this -> instance -> setStudentNumber("wibbledys");
		$validation = $this -> instance -> validate();
		$this -> assertContains("Student number invalid", $validation);
		
		$validation = $this -> instance -> validateStudentNumber();
		$this -> assertContains("Student number invalid", $validation);
		
		$validation = $this -> instance -> validateStudentNumber();
		$this -> assertContains("Student number invalid", $validation);
	}
	
	public function testValidStudentNumber()
	{
		$this -> instance -> setStudentNumber(123456789);
		$validation = $this -> instance -> validate();
		$this -> assertNotContains("Student number invalid", $validation);
		
		$validation = $this -> instance -> validateStudentNumber();
		$this -> assertNotContains("Student number invalid", $validation);
		
		$this -> assertEquals(123456789, $this -> instance -> studentNumber());
	}
}

?>
