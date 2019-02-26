<?php

namespace Primecontent\Tests\Controllers;

/**
 *  Corresponding Class to test YourClass class
 *
 *  For each class in your library, there should be a corresponding Unit-Test for it
 *  Unit-Tests should be as much as possible independent from other test going on.
 *
 * @author Álex Martin|Juan Manuel Castillo
 */

use Primecontent\Tests\BaseAPITestCase;

class FormControllerTest extends BaseAPITestCase
{

    /**
     * @testdox Listing items
     */
    public function testListItemsSuccess()
    {
        $response = $this->primecontent->form->getAll();

        $this->assertTrue(count($response) >= 1);
    }

    /**
     * @testdox Listing submit items
     */
    public function testListSubmitItemsSuccess()
    {
        $itemId = "test";

        $response = $this->primecontent->form->getSubmits($itemId);

        $this->assertTrue(count($response) >= 1);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess()
    {
        $itemId = "test";
        $response = $this->primecontent->form->getOne($itemId);

        $this->assertObjectHasAttribute("id", $response);
        $this->assertTrue($response->id === $itemId);
    }

    /**
     * @testdox Restore item version item/version not found
     */
    public function testRestoreItemNotFoundException(){
        $itemId = "test-fail";
        $versionId = "test-version";

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);

        $response = $this->primecontent->form->restoreVersion($itemId,$versionId);
    }

    /**
     * @testdox Restore item version success
     */
    public function testRestoreItemSuccess(){
        $itemId = "test";
        $versionId = "test-version";

        $response = $this->primecontent->form->restoreVersion($itemId,$versionId);

        $this->assertTrue($response->id === $itemId);
    }

    /**
     * @testdox Get single item versions
     */
    public function testGetSingleItemVersionsSuccess(){
        $itemId = "test";
        $response = $this->primecontent->form->getVersions($itemId);

        $this->assertTrue(count($response)>=1);
    }

    /**
     * @testdox Get single item versions not found
     */
    public function testGetSingleItemVersionsFail(){
        $itemId = "test-fail";

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);

        $response = $this->primecontent->form->getVersions($itemId);
    }

    /**
     * @testdox Get single item not found exception
     */
    public function testGetSingleItemNotFound()
    {
        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->form->getOne($itemId);
    }

    /**
     * @testdox Get single submit item
     */
    public function testGetSingleSubmitItemSuccess(){
        $itemId = "test";
        $subitemId = "test";
        $response = $this->primecontent->form->getOneSubmit($itemId,$subitemId);

        $this->assertObjectHasAttribute("id",$response);
        $this->assertTrue($response->id === $itemId);
    }

    /**
     * @testdox Get single submit item
     */
    public function testGetSingleSubmitItemNotFound(){
        $itemId = "test";
        $subitemId = "test-fail";

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->form->getOneSubmit($itemId,$subitemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess()
    {
        $formTypeId = "test";
        $title = "Item create test";
        $slug = "entry-create-test-1";
        $isPublished = true;
        $submitURL = "https://demo.primecontent.io/wellcome-to-the-jungle";

        $data = $this->generateItem($formTypeId, $title, $slug, $isPublished, $submitURL);

        $response = $this->primecontent->form->create($data);

        $this->assertTrue($title === $response->attributes->name);
        $this->assertTrue($formTypeId === $response->attributes->formType);
    }

    /**
     * @testdox Create item missed content type exception
     */
    public function testCreateSingleItemUnsetTypeException()
    {
        $formTypeId = "test";
        $title = "Item create test";
        $slug = "entry-create-test-1";
        $isPublished = true;
        $submitURL = "https://demo.primecontent.io/wellcome-to-the-jungle";

        $data = $this->generateItem($formTypeId, $title, $slug, $isPublished, $submitURL);
        unset($data["type"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontent->form->create($data);

    }

    /**
     * @testdox Create item submit success
     */
    public function testCreateSingleSubmitItemSuccess()
    {
        $formId = "test";
        $data = '{"title":"juanmanuel.castillo@innn.es","text":"Este es el texto en formato <b>HTML<\/b>"}';

        $data = $this->generateSubItem($data);

        $response = $this->primecontent->form->createSubmit($formId,$data);

        $this->assertTrue($formId === $response->attributes->form);
    }

    /**
     * @testdox Create item submit success
     */
    public function testCreateSingleSubmitItemInvalidDataSchemaException()
    {
        $formId = "test";
        $data = '{"text":"Este es el texto en formato <b>HTML<\/b>"}';

        $data = $this->generateSubItem($data);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontent->form->createSubmit($formId,$data);
    }

    /**
     * @testdox Delete item submit success
     */
    public function testDeleteSingleSubmitItemSuccess()
    {
        $formId = "test";
        $formSubmitId = "test";

        $response = $this->primecontent->form->deleteSubmit($formId,$formSubmitId);

        $this->assertTrue(!$response);
    }

    /**
     * @testdox Delete item submit not found exception
     */
    public function testDeleteSingleSubmitItemNotFoundException()
    {
        $formId = "test";
        $formSubmitId = "test";

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->form->deleteSubmit($formId,$formSubmitId);
    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess()
    {
        $formTypeId = "test";
        $title = "Item create test";
        $slug = "entry-create-test-1";
        $isPublished = true;
        $submitURL = "https://demo.primecontent.io/wellcome-to-the-jungle";

        $data = $this->generateItem($formTypeId, $title, $slug, $isPublished, $submitURL);

        $response = $this->primecontent->form->create($data);
        $itemId = $response->id;
        $randInt = random_int(1, 100000);
        $data["name"] = "Item {$randInt} edited!!!!!!";

        $response = $this->primecontent->form->update($itemId, $data);

        $this->assertTrue($data["name"] === $response->attributes->name);
    }

    /**
     * @testdox Update item not found exception
     */
    public function testUpdateSingleItemNotFoundException()
    {
        $formTypeId = "test";
        $title = "Item create test";
        $slug = "entry-create-test-1";
        $isPublished = true;
        $submitURL = "https://demo.primecontent.io/wellcome-to-the-jungle";

        $data = $this->generateItem($formTypeId, $title, $slug, $isPublished, $submitURL);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->form->update("test-edit-fail", $data);
    }


    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess()
    {
        $formTypeId = "test";
        $title = "Item create test";
        $slug = "entry-create-test-1";
        $isPublished = true;
        $submitURL = "https://demo.primecontent.io/wellcome-to-the-jungle";

        $data = $this->generateItem($formTypeId, $title, $slug, $isPublished, $submitURL);

        $response = $this->primecontent->form->create($data);
        $itemId = $response->id;

        $response = $this->primecontent->form->delete($itemId);

        $this->assertTrue(!$response);
    }

    /**
     * @testdox Delete item not found exception
     */
    public function testDeleteSingleItemNotFoundException()
    {
        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->form->delete($itemId);
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $slug
     * @param bool $isPublished
     * @param string $submitURL
     * @return array
     */
    private function generateItem(string $type = "test", string $name = "", string $slug = "", bool $isPublished = true, string $submitURL = "")
    {

        $data = array(
            "name" => $name,
            "slug" => $slug,
            "type" => $type,
            "active" => $isPublished,
            "submit_url" => $submitURL,
        );

        return $data;
    }

    /**
     * @param string $data
     * @return array|string
     */
    private function generateSubItem(string $data)
    {

        $data = array(
            "data" => json_decode($data,true)
        );

        return $data;
    }
}
