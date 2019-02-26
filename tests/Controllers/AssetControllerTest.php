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

class AssetControllerTest extends BaseAPITestCase {

    /**
     * @testdox Listing items
     */
    public function testListItemsSuccess(){
        $response = $this->primecontent->asset->getAll();

        $this->assertTrue(count($response)>=0);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess(){
        $itemId = "test";
        $response = $this->primecontent->asset->getOne($itemId);

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
        $response = $this->primecontent->asset->getOne($itemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess(){
        $filePath = "test/e1f2e5f89fd530f666053191d7838b6c.png";
        $title = "File test";
        $fileUrl = "https://dummyimage.com/300/09f/test.png";

        $data = $this->generateItem($title, $fileUrl);

        $response = $this->primecontent->asset->create($data);

        $this->assertTrue($title === $response->attributes->name);
        $this->assertTrue($filePath === $response->attributes->path);
    }

    /**
     * @testdox Create item missed file_url exception
     */
    public function testCreateSingleItemUnsetTypeException(){
        $title = "File test";
        $fileUrl = "test/e1f2e5f89fd530f666053191d7838b6c.png";

        $data = $this->generateItem($title, $fileUrl);
        unset($data["file_url"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontent->asset->create($data);

    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess(){
        $title = "File test";
        $pathName = "test/e1f2e5f89fd530f666053191d7838b6c.png";
        $fileUrl = "https://dummyimage.com/300/09f/test.png";

        $data = $this->generateItem($title, $fileUrl);

        $response = $this->primecontent->asset->create($data);

        $itemId = $response->id;
        $randInt = random_int(1,100000);
        $data["name"] = "Item {$randInt} edited!!!!!!";

        $response = $this->primecontent->asset->update($itemId,$data);

        $this->assertTrue($data["name"] === $response->attributes->name);
        $this->assertTrue($pathName === $response->attributes->path);
    }

    /**
     * @testdox Update item not found exception
     */
    public function testUpdateSingleItemNotFoundException(){
        $title = "File test";
        $pathName = "test/e1f2e5f89fd530f666053191d7838b6c.png";
        $fileUrl = "https://dummyimage.com/300/09f/test.png";

        $data = $this->generateItem($title, $fileUrl);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->asset->update("test-edit-fail",$data);
    }


    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess(){
        $title = "File test";
        $pathName = "test/e1f2e5f89fd530f666053191d7838b6c.png";
        $fileUrl = "https://dummyimage.com/300/09f/test.png";

        $data = $this->generateItem($title, $fileUrl);

        $response = $this->primecontent->asset->create($data);
        $itemId = $response->id;

        $response = $this->primecontent->asset->delete($itemId);

        $this->assertTrue(!$response);
    }

    /**
     * @testdox Delete item not found exception
     */
    public function testDeleteSingleItemNotFoundException(){
        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->asset->delete($itemId);
    }

    /**
     * @param string $name
     * @param string $fileUrl
     * @return array
     */
    private function generateItem(string $name, string $fileUrl)
    {

        $data = array(
            "name" => $name,
            "file_url" => $fileUrl
        );

        return $data;
    }
}
