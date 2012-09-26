<?php

require_once(dirname(__FILE__) . '/../functions.php');

class ComputeExpiryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this -> threshold = mktime(0, 0, 0, 5, 25, 2012);
        $this -> expiry = mktime(0, 0, 0, 10, 5, 2012);
        $this -> nextExpiry = mktime(0, 0, 0, 10, 4, 2013);
    }
    
    public function testBeforeThreshold()
    {
        $date = $this -> threshold - (7 * 24 * 60 * 60);
        $expiry = computeExpiry($date);
        
        $this -> assertEquals($this -> expiry, $expiry);
    }

    public function testAfterThreshold()
    {
        $date = $this -> threshold + (7 * 24 * 60 * 60);
        $expiry = computeExpiry($date);
        
        $this -> assertEquals($this -> nextExpiry, $expiry);
    }

    public function testOnThreshold()
    {
        $expiry = computeExpiry($this -> threshold);
        
        $this -> assertEquals($this -> expiry, $expiry);
    }
}
