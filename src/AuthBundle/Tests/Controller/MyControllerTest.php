<?php

namespace AuthBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyControllerTest extends WebTestCase
{
    public function testTest()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/test');
    }

}
