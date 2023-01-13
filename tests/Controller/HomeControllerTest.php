<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private static $client;
    private $crawler;

    public static function setUpBeforeClass(): void
    {
        self::$client = static::createClient();
        self::bootKernel();
    }

    public function setUp(): void
    {
        $router = self::getContainer()->get('router');
        $this->crawler = self::$client->request('GET', $router->generate('home_index'));
    }

    public function testSuccessfull(): void
    {
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Bienvenue sur notre app Sf Av');
    }

    public function testCountItemsProgram(): void
    {
        $crawler = $this->crawler->filter('main > ul > li');

        $this->assertEquals(9, $crawler->count());
    }

    public function testItemsProgramIsFinished(): void
    {
        // $this->markTestIncomplete();
        $crawler = $this->crawler->filter('main > ul > li.text-decoration-line-through');

        $this->assertEquals(8, $crawler->count());
    }
}
