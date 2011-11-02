<?php

require_once 'application/php/Application/JsonRpc/V1/Admin/Auth.php';

/**
 * Auth test case.
 */
class AuthTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Auth
     */
    private $Auth;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        
        // TODO Auto-generated AuthTest::setUp()
        

        $this->Auth = new Auth(/* parameters */);
    
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated AuthTest::tearDown()
        

        $this->Auth = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct ()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests Auth->setAuthData()
     */
    public function testSetAuthData ()
    {
        // TODO Auto-generated AuthTest->testSetAuthData()
        $this->markTestIncomplete("setAuthData test not implemented");
        
        $this->Auth->setAuthData(/* parameters */);
    
    }

    /**
     * Tests Auth->isAuthorized()
     */
    public function testIsAuthorized ()
    {
        // TODO Auto-generated AuthTest->testIsAuthorized()
        $this->markTestIncomplete("isAuthorized test not implemented");
        
        $this->Auth->isAuthorized(/* parameters */);
    
    }

}

