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

class FormTypeControllerTest extends BaseAPITestCase {

    /**
     * @testdox Listing items
     */
    public function testListItemsSuccess(){
        $response = $this->primecontent->formType->getAll();

        $this->assertTrue(count($response)>=1);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess(){
        $itemId = "test";
        $response = $this->primecontent->formType->getOne($itemId);

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
        $response = $this->primecontent->formType->getOne($itemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess(){
        $fields = json_decode('{"$schema":"http://json-schema.org/draft-07/schema#","title":"Simple form type","type":"object","properties":{"title":{"type":"string"},"address":{"$ref": "#/primecontent/address"}}}',true);
        $title = "Item create test";

        $data = $this->generateItem($title,$fields);

        $response = $this->primecontent->formType->create($data);
        $this->assertObjectHasAttribute("id",$response);
        $this->assertTrue($title === $response->attributes->name);
    }

    /**
     * @testdox Create item missed fields exception
     */
    public function testCreateSingleItemUnsetFieldsException(){
        $fields = json_decode('{"$schema":"http://json-schema.org/draft-07/schema#","title":"Simple form type","type":"object","properties":{"title":{"type":"string"},"address":{"$ref": "#/primecontent/address"}}}',true);
        $title = "Item create test";

        $data = $this->generateItem($title,$fields);
        unset($data["fields"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontent->formType->create($data);

    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess(){
        $itemId = "test";
        $fields = json_decode('{"$schema":"http://json-schema.org/draft-07/schema#","title":"Simple form type","type":"object","properties":{"title":{"type":"string"},"address":{"$ref": "#/primecontent/address"}}}',true);
        $title = "Item create test";

        $data = $this->generateItem($title, $fields);

        $response = $this->primecontent->formType->create($data);
        $itemId = $response->id;
        $randInt = random_int(1,100000);
        $data["name"] = "Item {$randInt} edited!!!!!!";

        $response = $this->primecontent->formType->update($itemId,$data);

        $this->assertTrue($data["name"] === $response->attributes->name);
    }

    /**
     * @testdox Update item not found exception
     */
    public function testUpdateSingleItemNotFoundException(){
        $fields = json_decode('{"$schema":"http://json-schema.org/draft-07/schema#","title":"Simple form type","type":"object","properties":{"title":{"type":"string"},"address":{"$ref": "#/primecontent/address"}}}',true);
        $title = "Item create test";

        $data = $this->generateItem($title, $fields);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->formType->update("test-edit-fail",$data);
    }

    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess(){
        $fields = json_decode('{"$schema":"http://json-schema.org/draft-07/schema#","title":"Simple form type","type":"object","properties":{"title":{"type":"string"},"address":{"$ref": "#/primecontent/address"}}}',true);
        $title = "Item create test";

        $data = $this->generateItem($title, $fields);

        $response = $this->primecontent->formType->create($data);
        $itemId = $response->id;

        $response = $this->primecontent->formType->delete($itemId);

        $this->assertTrue(!$response);
    }

    /**
     * @testdox Delete item not found exception
     */
    public function testDeleteSingleItemNotFoundException(){
        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->formType->delete($itemId);
    }

    /**
     * @param string $name
     * @param mixed $fields
     * @return array
     */
    private function generateItem(string $name, $fields)
    {

        $data = array(
            "name" => $name,
            "fields" => $fields
        );

        return $data;
    }
}
