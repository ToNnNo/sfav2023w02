<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    private $client;
    private $crawler;

    public function setUp(): void
    {
        $this->client = static::createClient();
        self::bootKernel();

        $router = self::getContainer()->get('router');
        $this->crawler = $this->client->request('GET', $router->generate('post_index'));
    }

    public function testSuccessfull(): void
    {
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Article');
    }

    public function testNoDataInDB(): void
    {
        $crawler = $this->crawler->filter('main > table > tbody > tr');
        $this->assertEquals(1, $crawler->count());
        $this->assertSelectorTextContains('main > table > tbody > tr > td', 'No Data');
    }

    public function testClickToAddPost(): void
    {
        $link = $this->crawler->filter('a:contains("Ajouter")')->link();
        $this->client->click($link);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Editer un article');
    }

    public function testAddPost(): void
    {
        $this->client->clickLink('Ajouter');

        $this->assertResponseIsSuccessful();

        $this->client->followRedirects();
        $crawler = $this->client->submitForm('Sauvegarder', [
            'post[title]' => 'Article #1',
            'post[body]' => 'Body Article #1',
            'post[tags]' => 'Article, WebTestCase'
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Article');

        $trCrawler = $crawler->filter('main > table > tbody > tr')->last();
        $title = $trCrawler->filter('td:nth-child(2)')->text();
        $this->assertEquals('Article #1', $title);
    }

}
