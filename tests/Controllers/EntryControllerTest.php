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

class EntryControllerTest extends BaseAPITestCase {

    /**
     * @testdox Listing items
     */
    public function testListItemsSuccess(){
        $response = $this->primecontent->entry->getAll();

        $this->assertTrue(count($response)>=1);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess(){
        $itemId = "test";
        $response = $this->primecontent->entry->getOne($itemId);

        $this->assertObjectHasAttribute("id",$response);
        $this->assertTrue($response->id === $itemId);
    }

    /**
     * @testdox Get single item versions
     */
    public function testGetSingleItemVersionsSuccess(){
        $itemId = "test";
        $response = $this->primecontent->entry->getVersions($itemId);

        $this->assertTrue(count($response)>=1);
    }

    /**
     * @testdox Get single item versions not found
     */
    public function testGetSingleItemVersionsFail(){
        $itemId = "test-fail";

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);

        $response = $this->primecontent->entry->getVersions($itemId);

    }

    /**
     * @testdox Get single item by slug
     */
    public function testGetSingleItemBySlugSuccess(){
        $itemId = "test";
        $response = $this->primecontent->entry->getOneBySlug($itemId);

        $this->assertObjectHasAttribute("id",$response);
        $this->assertTrue($response->id === $itemId);
    }

    /**
     * @testdox Get single item not found by slug
     */
    public function testGetSingleItemBySlugNotFound(){
        $itemId = "test-fail";

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->entry->getOneBySlug($itemId);
    }

    /**
     * @testdox Get single item not found exception
     */
    public function testGetSingleItemNotFound(){
        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->entry->getOne($itemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess(){
        $contentTypeId = "test";
        $title = "Item create test";
        $slug = "entry-create-test-1";

        $data = $this->generateItem($contentTypeId,$title, $slug);

        $response = $this->primecontent->entry->create($data);

        $this->assertTrue($title === $response->attributes->name);
        $this->assertTrue($contentTypeId === $response->attributes->contentType);
        $this->assertTrue(count($response->attributes->tags) === 0);
    }

    /**
     * @testdox Create item missed content type exception
     */
    public function testCreateSingleItemUnsetTypeException(){
        $contentTypeId = "test";
        $title = "Item create test";
        $slug = "entry-create-test-1";

        $data = $this->generateItem($contentTypeId,$title, $slug);
        unset($data["type"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontent->entry->create($data);

    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess(){
        $contentTypeId = "test";
        $title = "Item create test";
        $slug = "entry-create-test-1";

        $data = $this->generateItem($contentTypeId,$title, $slug);

        $response = $this->primecontent->entry->create($data);
        $itemId = $response->id;
        $randInt = random_int(1,100000);
        $data["name"] = "Item {$randInt} edited!!!!!!";

        $response = $this->primecontent->entry->update($itemId,$data);

        $this->assertTrue($data["name"] === $response->attributes->name);
    }

    /**
     * @testdox Restore item version item/version not found
     */
    public function testRestoreItemNotFoundException(){
        $itemId = "test-fail";
        $versionId = "test-version";

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);

        $response = $this->primecontent->entry->restoreVersion($itemId,$versionId);
    }

    /**
     * @testdox Restore item version success
     */
    public function testRestoreItemSuccess(){
        $itemId = "test";
        $versionId = "test-version";

        $response = $this->primecontent->entry->restoreVersion($itemId,$versionId);

        $this->assertTrue($response->id === $itemId);
    }

    /**
     * @testdox Update item not found exception
     */
    public function testUpdateSingleItemNotFoundException(){
        $data = array();

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->entry->update("test-edit-fail",$data);
    }


    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess(){
        $contentTypeId = "test";
        $title = "Item create test";
        $slug = "entry-create-test-1";

        $data = $this->generateItem($contentTypeId,$title, $slug);

        $response = $this->primecontent->entry->create($data);
        $itemId = $response->id;

        $response = $this->primecontent->entry->delete($itemId);

        $this->assertTrue(!$response);
    }

    /**
     * @testdox Delete item not found exception
     */
    public function testDeleteSingleItemNotFoundException(){
        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->entry->delete($itemId);
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $slug
     * @param array $tags
     * @param bool $published
     * @return array
     */
    private function generateItem(string $type="test", string $name="", string $slug="", array $tags = [],bool $published = true)
    {

        if ("" === $name) {
            $name = "Item " . rand(0, 99);
        }

        $data = array(
            "type" => $type,
            "name" => $name,
            "slug" => $slug,
            "tags" => $tags,
            "active" => $published,
            "data" => array(
                "title" => "Test title",
                "text" => "Este es el texto en formato <b>HTML</b>"
            ),
        );

        return $data;
    }
}
