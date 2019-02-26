<?php
namespace Primecontent\Tests\Controllers;

use Primecontent\Tests\BaseAPITestCase;

class OrganizationControllerTest extends BaseAPITestCase {

    /**
     * @testdox Listing items
     */
    public function testListItemsSuccess(){
        $response = $this->primecontentSA->organization->getAll();

        $this->assertTrue(count($response)>1);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess(){
        $itemId = "test";
        $response = $this->primecontentSA->organization->getOne($itemId);

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
        $response = $this->primecontentSA->organization->getOne($itemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess(){
        $title = "WUZO S.L. ".random_int(1,1000000);

        $data = $this->generateItem($title);

        $response = $this->primecontentSA->organization->create($data);

        $this->assertTrue($title === $response->attributes->name);
    }

    /**
     * @testdox Create item missed name exception
     */
    public function testCreateSingleItemUnsetTypeException(){
        $title = "WUZO S.L. ".random_int(1,1000000);

        $data = $this->generateItem($title);
        unset($data["name"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontentSA->organization->create($data);

    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess(){
        $title = "WUZO S.L. ".random_int(1,1000000);

        $data = $this->generateItem($title);

        $response = $this->primecontentSA->organization->create($data);
        $itemId = $response->id;
        $randInt = random_int(1,100000);
        $data["name"] = "Item {$randInt} edited!!!!!!";

        $response = $this->primecontentSA->organization->update($itemId,$data);

        $this->assertTrue($data["name"] === $response->attributes->name);
    }

    /**
     * @testdox Update item not found exception
     */
    public function testUpdateSingleItemNotFoundException(){
        $title = "WUZO S.L. ".random_int(1,1000000);

        $data = $this->generateItem($title);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontentSA->organization->update("test-edit-fail",$data);
    }


    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess(){
        $title = "WUZO S.L. ".random_int(1,1000000);

        $data = $this->generateItem($title);

        $response = $this->primecontentSA->organization->create($data);
        $itemId = $response->id;

        $response = $this->primecontentSA->organization->delete($itemId,$data);

        $this->assertTrue(!$response);
    }

    /**
     * @testdox Delete item not found exception
     */
    public function testDeleteSingleItemNotFoundException(){
        $title = "WUZO S.L. ".random_int(1,1000000);

        $data = $this->generateItem($title);

        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontentSA->organization->delete($itemId,$data);
    }

    /**
     * @param string $name
     * @return array
     */
    private function generateItem(string $name)
    {

        $data = array(
            "name" => $name,
        );

        return $data;
    }
}
