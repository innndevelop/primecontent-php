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

class AccessTokenControllerTest extends BaseAPITestCase
{
    /**
     * @testdox Listing items
     */
    const TOKEN_USER_ID = "test-rea";

    public function testListItemsSuccess()
    {
        $response = $this->primecontent->token->getAll(self::TOKEN_USER_ID);

        $this->assertTrue(count($response) >= 0);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess()
    {
        $itemId = "test-dummy-token";
        $response = $this->primecontent->token->getOne(self::TOKEN_USER_ID, $itemId);

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

        $response = $this->primecontent->token->getOne(self::TOKEN_USER_ID, $itemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess()
    {
        $title = "Item create test";

        $data = $this->generateItem($title);

        $response = $this->primecontent->token->create(self::TOKEN_USER_ID, $data);

        $this->assertTrue($title === $response->attributes->name);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemNoEnoughPermissionException()
    {
        $title = "Item create test";

        $data = $this->generateItem($title);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(403);
        $response = $this->primecontent->token->create($this->userId, $data);
    }

    /**
     * @testdox Create item missed name exception
     */
    public function testCreateSingleItemUnsetTypeException()
    {
        $title = "Item create test";

        $data = $this->generateItem($title);
        unset($data["name"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontent->token->create($this->userId, $data);

    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess()
    {
        $title = "Item create test";

        $data = $this->generateItem($title);

        $response = $this->primecontent->token->create(self::TOKEN_USER_ID, $data);
        $itemId = $response->id;
        $randInt = random_int(1, 100000);
        $data["name"] = "Item {$randInt} edited!!!!!!";

        $response = $this->primecontent->token->update(self::TOKEN_USER_ID, $itemId, $data);

        $this->assertTrue($data["name"] === $response->attributes->name);
    }

    /**
     * @testdox Update item not found exception
     */
    public function testUpdateSingleItemNotFoundException()
    {
        $title = "Item create test";

        $data = $this->generateItem($title);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->token->update(self::TOKEN_USER_ID, "test-edit-fail", $data);
    }


    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess()
    {
        $title = "Item create test";

        $data = $this->generateItem($title);

        $response = $this->primecontent->token->create(self::TOKEN_USER_ID, $data);
        $itemId = $response->id;

        $response = $this->primecontent->token->delete(self::TOKEN_USER_ID, $itemId);

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
        $response = $this->primecontent->token->delete(self::TOKEN_USER_ID, $itemId);
    }

    /**
     * @param string $name
     * @return array
     */
    private function generateItem(string $name)
    {

        $data = array(
            "name" => $name
        );

        return $data;
    }
}
