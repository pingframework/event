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

/**
 * @author    Oleg Bronzov <oleg@bbumgames.com>
 * @copyright 2022
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class EventListenerRegistry
{
    /**
     * @var array<string, array<EventListenerDefinition>>
     */
    private array $listeners;

    public function add(
        string $event,
        string $service,
        string $method,
        int    $priority,
    ): void {
        $this->listeners[$event][] = new EventListenerDefinition($service, $method, $priority);
    }

    public function getAll(): array
    {
        return $this->listeners;
    }
}