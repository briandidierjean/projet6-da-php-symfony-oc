<?php

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetLastName()
    {
        $user = new User();
        $user->setLastName('Didierjean');

        // assert that last name is correctly returned
        $this->assertEquals('Didierjean', $user->getLastName());
    }
}