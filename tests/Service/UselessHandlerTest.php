<?php

namespace App\Tests\Service;

use App\Event\PreUselessNothingEvent;
use App\EventSubscriber\PreUselessNothingSubscriber;
use App\Service\UselessHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\EventDispatcher\Event;

class UselessHandlerTest extends KernelTestCase
{
    private $useless;

    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
    }

    protected function setUp(): void
    {
        $dispatcher = self::getContainer()->get('event_dispatcher');
        $subscriber = self::getContainer()->get(PreUselessNothingSubscriber::class);
        $dispatcher->removeSubscriber($subscriber);

        $this->useless = self::getContainer()->get(UselessHandler::class);
    }

    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    /*
    public function testSkipIt(): void
    {
        $this->markTestSkipped('Fonction pas encore implémenté');
    }

    public function testRisky(): void
    {
        $this->markAsRisky();
    }
    */

    public function testSum(): void
    {
        $sum = 1 + 2;
        $this->assertEquals(3, $sum);
    }

    public function testServiceExist(): void
    {
        $this->assertInstanceOf(UselessHandler::class, $this->useless);
    }

    public function testMessage(): void
    {
        $this->assertEquals('Hello World', $this->useless->nothing('Hello World'));
    }

    /**
     * @dataProvider badMessageProvider
     */
    public function testThrowException($value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->assertEquals($value, $this->useless->nothing($value));
    }

    public function badMessageProvider()
    {
        yield [null];
        yield [1];
        yield [new \ArrayObject()];
        yield [true];
        yield [1.1];
    }
}
