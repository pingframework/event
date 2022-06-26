<?php

/**
 * Ping - Event
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * Json RPC://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@phpsuit.net so we can send you a copy immediately.
 *
 * @author    Oleg Bronzov <oleg@bbumgames.com>
 * @copyright 2022
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace Pingframework\Event;

use Pingframework\Boot\Annotations\PingBootApplication;
use Pingframework\Boot\Application\AbstractPingBootApplication;
use Pingframework\Ping\Annotations\Autowired;
use Pingframework\Ping\DependencyContainer\DependencyContainerInterface;

/**
 * @author    Oleg Bronzov <oleg@bbumgames.com>
 * @copyright 2022
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
#[PingBootApplication]
class EventApplication extends AbstractPingBootApplication
{
    #[Autowired]
    public function configure(
        DependencyContainerInterface $c,
        EventListenerRegistry        $eventListenerRegistry,
        EventDispatcher              $eventDispatcher,
    ): void {
        foreach ($eventListenerRegistry->getAll() as $event => $eldList) {
            foreach ($eldList as $eld) {
                $eventDispatcher->subscribeTo($event, fn(object $e) => $c->call(
                    $c->get($eld->service),
                    $eld->method,
                    [$e::class => $e]
                ), $eld->priority);
            }
        }
    }
}