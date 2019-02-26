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

class TagControllerTest extends BaseAPITestCase {

    /**
     * @testdox Listing items
     */
    public function testListItemsSuccess(){
        $response = $this->primecontent->tag->getAll();

        $this->assertTrue(count($response)>1);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess(){
        $itemId = "test";
        $response = $this->primecontent->tag->getOne($itemId);

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
        $response = $this->primecontent->tag->getOne($itemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess(){
        $title = "Item create test";
        $active = true;

        $data = $this->generateItem($title, $active);

        $response = $this->primecontent->tag->create($data);

        $this->assertTrue($title === $response->attributes->name);
        $this->assertTrue($active === $response->attributes->active);
    }

    /**
     * @testdox Create item missed name exception
     */
    public function testCreateSingleItemUnsetTypeException(){
        $title = "Item create test";
        $active = true;

        $data = $this->generateItem($title, $active);
        unset($data["name"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontent->tag->create($data);

    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess(){
        $title = "Item create test";
        $active = true;

        $data = $this->generateItem($title, $active);

        $response = $this->primecontent->tag->create($data);
        $itemId = $response->id;
        $randInt = random_int(1,100000);
        $data["name"] = "Item {$randInt} edited!!!!!!";

        $response = $this->primecontent->tag->update($itemId,$data);

        $this->assertTrue($data["name"] === $response->attributes->name);
    }

    /**
     * @testdox Update item not found exception
     */
    public function testUpdateSingleItemNotFoundException(){
        $title = "Item create test";
        $active = true;

        $data = $this->generateItem($title, $active);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->tag->update("test-edit-fail",$data);
    }


    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess(){
        $title = "Item delete test";
        $active = true;

        $data = $this->generateItem($title, $active);

        $response = $this->primecontent->tag->create($data);
        $itemId = $response->id;

        $response = $this->primecontent->tag->delete($itemId);

        $this->assertTrue(!$response);
    }

    /**
     * @testdox Delete item not found exception
     */
    public function testDeleteSingleItemNotFoundException(){
        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->tag->delete($itemId);
    }

    /**
     * @param string $name
     * @param bool $isPublished
     * @return array
     */
    private function generateItem(string $name,bool $isPublished = true)
    {

        $data = array(
            "name" => $name,
            "active" => $isPublished
        );

        return $data;
    }
}
