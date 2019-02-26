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

use PHPUnit\Framework\TestCase;
use Primecontent\Tests\BaseAPITestCase;

class GroupControllerTest extends BaseAPITestCase {

    /**
     * @testdox Listing items
     */
    public function testListItemsSuccess(){
        $response = $this->primecontentSA->group->getAll();

        $this->assertTrue(count($response)>1);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess(){
        $itemId = "test-wri";
        $response = $this->primecontentSA->group->getOne($itemId);

        $this->assertObjectHasAttribute("id",$response);
        $this->assertTrue($response->id === $itemId);
    }

    /**
     * @testdox Get single item not found exception
     */
    public function testGetSingleItemNotFound(){
        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontentSA->group->getOne($itemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess(){
        $title = "Role ".random_int(1,1000000);
        $level = 80;
        $roles = array("ROLE_READER","ROLE_WRITER","ROLE_DEVELOPER");

        $data = $this->generateItem($title, $level, $roles);

        $response = $this->primecontentSA->group->create($data);

        $this->assertTrue($title === $response->attributes->name);
    }

    /**
     * @testdox Create item missed title exception
     */
    public function testCreateSingleItemUnsetTypeException(){
        $title = "Role ".random_int(1,1000000);
        $level = 80;
        $roles = array("ROLE_READER","ROLE_WRITER","ROLE_DEVELOPER");

        $data = $this->generateItem($title, $level, $roles);
        unset($data["name"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontentSA->group->create($data);

    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess(){
        $title = "Role ".random_int(1,1000000);
        $level = 80;
        $roles = array("ROLE_READER","ROLE_WRITER","ROLE_DEVELOPER");

        $data = $this->generateItem($title, $level, $roles);

        $response = $this->primecontentSA->group->create($data);
        $itemId = $response->id;
        $randInt = random_int(1,100000);
        $data["name"] = "Item {$randInt} edited!!!!!!";

        $response = $this->primecontentSA->group->update($itemId,$data);

        $this->assertTrue($data["name"] === $response->attributes->name);
    }

    /**
     * @testdox Update item not found exception
     */
    public function testUpdateSingleItemNotFoundException(){
        $title = "Role ".random_int(1,1000000);
        $level = 80;
        $roles = array("ROLE_READER","ROLE_WRITER","ROLE_DEVELOPER");

        $data = $this->generateItem($title, $level, $roles);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontentSA->group->update("test-edit-fail",$data);
    }


    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess(){
        $title = "Role ".random_int(1,1000000);
        $level = 80;
        $roles = array("ROLE_READER","ROLE_WRITER","ROLE_DEVELOPER");

        $data = $this->generateItem($title, $level, $roles);

        $response = $this->primecontentSA->group->create($data);
        $itemId = $response->id;

        $response = $this->primecontentSA->group->delete($itemId,$data);

        $this->assertTrue(!$response);
    }

    /**
     * @testdox Delete item not found exception
     */
    public function testDeleteSingleItemNotFoundException(){
        $title = "Role ".random_int(1,1000000);
        $level = 80;
        $roles = array("ROLE_READER","ROLE_WRITER","ROLE_DEVELOPER");

        $data = $this->generateItem($title, $level, $roles);

        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontentSA->group->delete($itemId,$data);
    }

    /**
     * @param string $name
     * @param int $level
     * @param array $roles
     * @return array
     */
    private function generateItem(string $name, int $level, array $roles)
    {

        $data = array(
            "name" => $name,
            "level" => $level,
            "roles" => $roles
        );

        return $data;
    }
}
