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

class HookControllerTest extends BaseAPITestCase
{

    /**
     * @testdox Listing event items
     */
    public function testListEventsSuccess()
    {
        $response = $this->primecontent->hook->getEvents();

        $this->assertTrue(count($response) >= 1);
    }

    /**
     * @testdox Listing items
     */
    public function testListItemsSuccess()
    {
        $response = $this->primecontent->hook->getAll();

        $this->assertTrue(count($response) >= 1);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess()
    {
        $itemId = "test";
        $response = $this->primecontent->hook->getOne($itemId);

        $this->assertObjectHasAttribute("id", $response);
        $this->assertTrue($response->id === $itemId);
    }

    /**
     * @testdox Get single item not found exception
     */
    public function testGetSingleItemNotFound()
    {
        $itemId = "test-fail";
        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->hook->getOne($itemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess()
    {
        $title = "Item create test";
        $eventName = "form.submitted";
        $isPublished = true;
        $endPoint = "https://forms.primecontent.io";

        $data = $this->generateItem($title,$eventName,$isPublished,$endPoint);

        $response = $this->primecontent->hook->create($data);

        $this->assertTrue($title === $response->attributes->name);
        $this->assertTrue($eventName === $response->attributes->event_name);
        $this->assertTrue($isPublished === $response->attributes->active);
        $this->assertTrue($endPoint === $response->attributes->end_point);
    }

    /**
     * @testdox Create item missed content type exception
     */
    public function testCreateSingleItemUnsetTypeException()
    {
        $title = "Item create test";
        $eventName = "form.submitted";
        $isPublished = true;
        $endPoint = "https://forms.primecontent.io";

        $data = $this->generateItem($title,$eventName,$isPublished,$endPoint);
        unset($data["event_name"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontent->hook->create($data);

    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess()
    {
        $title = "Item create test";
        $eventName = "form.submitted";
        $isPublished = true;
        $endPoint = "https://forms.primecontent.io";

        $data = $this->generateItem($title,$eventName,$isPublished,$endPoint);

        $response = $this->primecontent->hook->create($data);
        $itemId = $response->id;
        $randInt = random_int(1, 100000);
        $data["name"] = "Item {$randInt} edited!!!!!!";
        $data["active"] = false;

        $response = $this->primecontent->hook->update($itemId, $data);

        $this->assertTrue($data["name"] === $response->attributes->name);
        $this->assertTrue($data["active"] === $response->attributes->active);
    }

    /**
     * @testdox Update item not found exception
     */
    public function testUpdateSingleItemNotFoundException()
    {
        $title = "Item create test";
        $eventName = "form.submitted";
        $isPublished = true;
        $endPoint = "https://forms.primecontent.io";

        $data = $this->generateItem($title,$eventName,$isPublished,$endPoint);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->hook->update("test-edit-fail", $data);
    }


    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess()
    {
        $title = "Item delete test";
        $eventName = "form.submitted";
        $isPublished = true;
        $endPoint = "https://forms.primecontent.io";

        $data = $this->generateItem($title,$eventName,$isPublished,$endPoint);

        $response = $this->primecontent->hook->create($data);
        $itemId = $response->id;

        $response = $this->primecontent->hook->delete($itemId);

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
        $response = $this->primecontent->hook->delete($itemId);
    }

    /**
     * @param string $name
     * @param string $eventName
     * @param bool $isPublished
     * @param string $endPoint
     * @return array
     */
    private function generateItem(string $name = "", string $eventName = "", bool $isPublished = true, string $endPoint = "")
    {

        $data = array(
            "name" => $name,
            "event_name" => $eventName,
            "end_point" => $endPoint,
            "active" => $isPublished
        );

        return $data;
    }
}
