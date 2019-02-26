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

class UserControllerTest extends BaseAPITestCase
{

    /**
     * @testdox Listing items
     */
    public function testListItemsSuccess()
    {
        $response = $this->primecontent->user->getAll();

        $this->assertTrue(count($response) > 1);
    }

    /**
     * @testdox Get single item
     */
    public function testGetSingleItemSuccess()
    {
        $itemId = "test-rea";
        $response = $this->primecontent->user->getOne($itemId);

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
        $response = $this->primecontent->user->getOne($itemId);
    }

    /**
     * @testdox Create item success
     */
    public function testCreateSingleItemSuccess()
    {
        $title = "Item create test";
        $active = true;

        $data = $this->generateItem("test-user-create","test-user-create","sistemas@wuzo.io", ["test"], ["test-wri"],'test',$title,$active);

        $response = $this->primecontent->user->create($data);

        $this->assertTrue($title === $response->attributes->description);
        $this->assertTrue($active === $response->attributes->active);
    }

    /**
     * @testdox Create item missed username exception
     */
    public function testCreateSingleItemUnsetTypeException()
    {
        $title = "Item create test";
        $active = true;

        $data = $this->generateItem("test-user-create","test-user-create","sistemas@wuzo.io", ["test"], ["test-wri"],'test',$title,$active);
        unset($data["username"]);

        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(400);
        $response = $this->primecontent->user->create($data);

    }

    /**
     * @testdox Update item success
     */
    public function testUpdateSingleItemSuccess()
    {
        $title = "Item create test";
        $active = true;

        $data = $this->generateItem("test-user-update","test-user-update","sistemas-update@wuzo.io", ["test"], ["test-wri"],'test',$title,$active);


        $response = $this->primecontent->user->create($data);
        $itemId = $response->id;
        $randInt = random_int(1, 100000);
        $data["description"] = "Item {$randInt} edited!!!!!!";

        $response = $this->primecontent->user->update($itemId, $data);

        $this->assertTrue($data["name"] === $response->attributes->name);
    }

    /**
     * @testdox Update item not found exception
     */
    public function testUpdateSingleItemNotFoundException()
    {
        $title = "Item create test";
        $active = true;

        $data = $this->generateItem("test-user-create","test-user-create","sistemas@wuzo.io", ["test"], ["test-wri"],'test',$title,$active);


        $this->expectException(\Primecontent\Exceptions\ClientException::class);
        $this->expectExceptionCode(404);
        $response = $this->primecontent->user->update("test-edit-fail", $data);
    }


    /**
     * @testdox Delete item success
     */
    public function testDeleteSingleItemSuccess()
    {
        $title = "Item create test";
        $active = true;

        $data = $this->generateItem("test-user-delete","test-user-delete","sistemas-delete@wuzo.io", ["test"], ["test-wri"],'test',$title,$active);

        $response = $this->primecontent->user->create($data);
        $itemId = $response->id;

        $response = $this->primecontent->user->delete($itemId);

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
        $response = $this->primecontent->user->delete($itemId);
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $description
     * @param string $email
     * @param array $spacesId
     * @param array $groupsId
     * @param string $organizationId
     * @param bool $isPublished
     * @return array
     */
    private function generateItem(string $username, string $password, string $email, array $spacesId, array $groupsId, string $organizationId = null, string $description = "", bool $isPublished = true)
    {

        $data = array(
            "username" => $username,
            "description" => $description,
            "email" => $email,
            "organization" => $organizationId,
            "spaces" => $spacesId,
            "groups" => $groupsId,
            "password" => $password,
            "active" => $isPublished,
        );

        return $data;
    }
}
