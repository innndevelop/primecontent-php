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

use Primecontent\Tests\BaseAPITestCase;

class SpaceControllerTest extends BaseAPITestCase {

    /**
     * @testdox Listing items
     */
    public function testListItemsSuccess(){
        $response = $this->primecontent->space->getAll();

        $this->assertTrue(count($response)>1);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess(){
        $itemId = "test";
        $response = $this->primecontent->space->getOne($itemId);

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
        $response = $this->primecontent->space->getOne($itemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess(){
        $title = "WUZO S.L. ".random_int(1,1000000);
        $organization = "test";

        $data = $this->generateItem($title,$organization);

        $response = $this->primecontent->space->create($data);

        $this->assertTrue($title === $response->attributes->name);
    }

    /**
     * @testdox Create item missed name exception
     */
    public function testCreateSingleItemUnsetTypeException(){
        $title = "WUZO S.L. ".random_int(1,1000000);
        $organization = "test";

        $data = $this->generateItem($title,$organization);
        unset($data["name"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontent->space->create($data);

    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess(){
        $title = "WUZO S.L. ".random_int(1,1000000);
        $organization = "test";

        $data = $this->generateItem($title,$organization);

        $response = $this->primecontent->space->create($data);
        $itemId = $response->id;
        $randInt = random_int(1,100000);
        $data["name"] = "Item {$randInt} edited!!!!!!";

        $response = $this->primecontent->space->update($itemId,$data);

        $this->assertTrue($data["name"] === $response->attributes->name);
    }

    /**
     * @testdox Update item not found exception
     */
    public function testUpdateSingleItemNotFoundException(){
        $title = "WUZO S.L. ".random_int(1,1000000);
        $organization = "test";

        $data = $this->generateItem($title,$organization);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->space->update("test-edit-fail",$data);
    }


    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess(){
        $title = "WUZO S.L. ".random_int(1,1000000);
        $organization = "test";

        $data = $this->generateItem($title,$organization);

        $response = $this->primecontent->space->create($data);
        $itemId = $response->id;

        $response = $this->primecontent->space->delete($itemId,$data);

        $this->assertTrue(!$response);
    }

    /**
     * @testdox Delete item not found exception
     */
    public function testDeleteSingleItemNotFoundException(){
        $title = "WUZO S.L. ".random_int(1,1000000);
        $organization = "test";

        $data = $this->generateItem($title,$organization);

        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->space->delete($itemId,$data);
    }

    /**
     * @param string $name
     * @param string $organization
     * @return array
     */
    private function generateItem(string $name, string $organization)
    {

        $data = array(
            "name" => $name,
            "organization" => $organization,
        );

        return $data;
    }
}
