<?php

require_once(dirname(__FILE__) . '/is_email.php');
class User
{
	private $email;
	private $forename;
	private $surname;
	private $studentNumber;
	
	function setName($first, $last)
	{
		$this -> forename = $this -> tidy($first);
		$this -> surname = $this -> tidy($last);
	}
	
	function setStudentNumber($number)
	{
		$this -> studentNumber = $this -> tidy($number);
	}
	
	function setEmail($email)
	{
		$this -> email = $email;
	}
	
	function firstName()
	{
		return $this -> forename;
	}
	
	function lastName()
	{
		return $this -> surname;
	}
	
	function email()
	{
		return $this -> email;
	}
	
	function studentNumber()
	{
		return $this -> studentNumber;
	}
	
	function validate()
	{
		$errors = array();
		$errors = array_merge($errors, $this -> validateStudentNumber());
		$errors = array_merge($errors, $this -> validateFirstName());
		$errors = array_merge($errors, $this -> validateSurname());
		$errors = array_merge($errors, $this -> validateEmail());
		
		return $errors;
	}
	
	function validateStudentNumber()
	{
		$errors = array();
		if (!is_numeric($this -> studentNumber) 
			|| strlen($this -> studentNumber) != 9)
		{
			$errors[] = "Student number invalid";
		}
		
		return $errors;
	}
	
	function validateFirstName()
	{
		$errors = array();
		
		if (strlen($this -> forename) == 0)
		{
			$errors[] = "No first name entered";
		}
		
		return $errors;
	}
	
	function validateSurname()
	{	
		$errors = array();
			
		if (strlen($this -> surname) == 0)
		{
			$errors[] = "No last name entered";
		}
		
		return $errors;
	}
	
	function validateEmail()
	{		
		$errors = array();
		
		if (strlen($this -> email) == 0)
		{
			$errors[] = "No email address entered";
		}
		elseif (!is_email($this -> email))
		{
			$errors[] = "Invalid email address entered";
		}
		
		return $errors;
	}
	
	function tidy($string)
	{
		$string = trim($string);
		$string = mb_convert_case($string, MB_CASE_TITLE);
		
		return $string;
	}
}

?>
