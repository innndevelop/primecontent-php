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

class ContentTypeControllerTest extends BaseAPITestCase {

    /**
     * @testdox Listing items
     */
    public function testListItemsSuccess(){
        $response = $this->primecontent->entryType->getAll();

        $this->assertTrue(count($response)>=1);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess(){
        $itemId = "test";
        $response = $this->primecontent->entryType->getOne($itemId);

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
        $response = $this->primecontent->entryType->getOne($itemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess(){
        $fields = json_decode('{"$schema":"http:\/\/json-schema.org\/draft-06\/schema#","title":"Simple page","type":"object","additionalProperties": false,"properties":{"title":{"type":"string"},"text":{"type":"string"}}}',true);
        $title = "Item create test";

        $data = $this->generateItem($title,$fields);
        $response = $this->primecontent->entryType->create($data);

        $this->assertObjectHasAttribute("id",$response);
        $this->assertTrue($title === $response->attributes->name);
    }

    /**
     * @testdox Create item missed fields exception
     */
    public function testCreateSingleItemUnsetFieldsException(){
        $fields = json_decode('{"$schema":"http:\/\/json-schema.org\/draft-06\/schema#","title":"Simple page","type":"object","additionalProperties": false,"properties":{"title":{"type":"string"},"text":{"type":"string"}}}',true);
        $title = "Item create test";

        $data = $this->generateItem($title,$fields);
        unset($data["fields"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontent->entryType->create($data);

    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess(){
        $fields = json_decode('{"$schema":"http:\/\/json-schema.org\/draft-06\/schema#","title":"Simple page","type":"object","additionalProperties": false,"properties":{"title":{"type":"string"},"text":{"type":"string"}}}',true);
        $title = "Item create test";

        $data = $this->generateItem($title, $fields);

        $response = $this->primecontent->entryType->create($data);
        $itemId = $response->id;
        $randInt = random_int(1,100000);
        $data["name"] = "Item {$randInt} edited!!!!!!";

        $response = $this->primecontent->entryType->update($itemId,$data);

        $this->assertTrue($data["name"] === $response->attributes->name);
    }

    /**
     * @testdox Update item not found exception
     */
    public function testUpdateSingleItemNotFoundException(){
        $fields = json_decode('{"$schema":"http:\/\/json-schema.org\/draft-06\/schema#","title":"Simple page","type":"object","additionalProperties": false,"properties":{"title":{"type":"string"},"text":{"type":"string"}}}',true);
        $title = "Item create test";

        $data = $this->generateItem($title, $fields);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->entryType->update("test-edit-fail",$data);
    }

    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess(){
        $fields = json_decode('{"$schema":"http:\/\/json-schema.org\/draft-06\/schema#","title":"Simple page","type":"object","additionalProperties": false,"properties":{"title":{"type":"string"},"text":{"type":"string"}}}',true);
        $title = "Item create test";

        $data = $this->generateItem($title, $fields);

        $response = $this->primecontent->entryType->create($data);
        $itemId = $response->id;

        $response = $this->primecontent->entryType->delete($itemId);

        $this->assertTrue(!$response);
    }

    /**
     * @testdox Delete item not found exception
     */
    public function testDeleteSingleItemNotFoundException(){
        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->entryType->delete($itemId);
    }

    /**
     * @param string $name
     * @param mixed $data
     * @return array
     */
    private function generateItem(string $name, $data)
    {

        $data = array(
            "name" => $name,
            "fields" => $data
        );

        return $data;
    }
}
