<?php

namespace Pingframework\Event\Tests;

use PHPUnit\Framework\TestCase;
use Pingframework\Event\EventApplication;
use Pingframework\Event\EventListenerRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventApplicationTest extends TestCase
{
    public function testDispatch()
    {
        $app = EventApplication::build();
        $e = new TestEvent();
        $dispatcher = $app->getApplicationContext()->get(EventDispatcherInterface::class);
        $dispatcher->dispatch($e);

        $this->assertEquals(1, $e->x);
        $this->assertInstanceOf(EventListenerRegistry::class, $e->eventListenerRegistry);
    }
}
