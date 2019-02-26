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

class ConfigControllerTest extends BaseAPITestCase {

    /**
     * @testdox Listing items
     */
    public function testListItemsSuccess(){
        $response = $this->primecontent->config->getAll();

        $this->assertTrue(count($response)>1);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess(){
        $itemId = "test";
        $response = $this->primecontent->config->getOne($itemId);

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
        $response = $this->primecontent->config->getOne($itemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess(){
        $key = "app-name-".random_int(1,100000);
        $value = "Test app";

        $data = $this->generateItem($key, $value);

        $response = $this->primecontent->config->create($data);

        $this->assertTrue($key === $response->attributes->key);
        $this->assertTrue($value === $response->attributes->value);
    }

    /**
     * @testdox Create item missed key exception
     */
    public function testCreateSingleItemUnsetTypeException(){
        $key = "app-name-".random_int(1,100000);
        $value = "Test app";

        $data = $this->generateItem($key, $value);
        unset($data["key"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontent->config->create($data);

    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess(){
        $key = "app-name-".random_int(1,100000);
        $value = "Test app";

        $data = $this->generateItem($key, $value);

        $response = $this->primecontent->config->create($data);
        $itemId = $response->attributes->key;
        $randInt = random_int(1,100000);
        $data["value"] = "Item {$randInt} edited!!!!!!";

        $response = $this->primecontent->config->update($itemId,$data);

        $this->assertTrue($data["value"] === $response->attributes->value);
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
        $response = $this->primecontent->config->update("test-edit-fail",$data);
    }


    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess(){
        $key = "app-name-delete";
        $value = "Test app";

        $data = $this->generateItem($key, $value);

        $response = $this->primecontent->config->create($data);
        $itemId = $response->attributes->key;

        $response = $this->primecontent->config->delete($itemId);

        $this->assertTrue(!$response);
    }

    /**
     * @testdox Delete item not found exception
     */
    public function testDeleteSingleItemNotFoundException(){
        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->config->delete($itemId);
    }

    /**
     * @param string $key
     * @param string $value
     * @return array
     */
    private function generateItem(string $key, string $value)
    {

        $data = array(
            "key" => $key,
            "value" => $value
        );

        return $data;
    }
}
