<?php
namespace Primecontent\Tests\Controllers;

/**
*  Corresponding Class to test YourClass class
*
*  For each class in your library, there should be a corresponding Unit-Test for it
*  Unit-Tests should be as much as possible independent from other test going on.
*
*  @author Álex Martin|Juan Manuel Castillo
*/

use Primecontent\Client;
use Primecontent\Tests\BaseAPITestCase;

class AuthControllerTest extends BaseAPITestCase {

    /**
     * @testdox Get entries without init client should return an exception
     */
    public function testNotAllowedAccess(){

        $this->expectException(\Primecontent\Exceptions\NotFoundPropertyException::class);

        $newClient = new Client("jwt", "", "", "test", 15);

        $newClient->entry->getAll();
    }

    /**
    * @testdox Test login should return a valid JWT Token
    */
    public function testLoginSuccess(){
        $response = $this->primecontent->auth->login("test-adm","test");

        $this->assertTrue(key_exists("token", $response) === true);

        $this->token = $response->token;
        $this->assertTrue($this->primecontent->getToken() === $this->token);
        $this->assertObjectHasAttribute("refresh_token",$response);
    }

    /**
     * @testdox Test login should return a valid JWT Token
     */
    public function testLoginInvalidCredentials(){

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(401);
        $response = $this->primecontent->auth->login("test","testadm");
    }

    /**
    * @testdox Invalid config should return an exception
    */
    public function testInvalidInit(){

        $invalidConfig = array("test"=>"foo");
        $this->expectException(\Primecontent\Exceptions\InvalidConfigException::class);
        $this->primecontent->setup($invalidConfig);

    }

    /**
     * @testdox Invalid config should return an exception
     */
    public function testValidInit(){

        $validConfig = array("space"=>"test", "token"=>$this->token);
        $this->assertTrue($this->primecontent->setup($validConfig) === true);

    }

}
