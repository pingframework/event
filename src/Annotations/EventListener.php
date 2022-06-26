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

namespace Pingframework\Event\Annotations;

use Attribute;
use LogicException;
use Pingframework\Event\EventListenerRegistry;
use Pingframework\Ping\Annotations\MethodRegistrar;
use Pingframework\Ping\DependencyContainer\DependencyContainerInterface;
use Pingframework\Ping\Utils\Priority;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

/**
 * @author    Oleg Bronzov <oleg@bbumgames.com>
 * @copyright 2022
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
#[Attribute(Attribute::TARGET_METHOD)]
class EventListener implements MethodRegistrar
{
    public function __construct(
        public readonly ?string $event = null,
        public readonly int     $priority = Priority::NORMAL,
    ) {}

    public function registerMethod(
        DependencyContainerInterface $c,
        ReflectionClass              $rc,
        ReflectionMethod             $rm,
    ): void {
        $c->get(EventListenerRegistry::class)->add(
            $this->getEvent($rm),
            $rc->getName(),
            $rm->getName(),
            $this->priority
        );
    }

    private function getEvent(ReflectionMethod $rm): string
    {
        if (!is_null($this->event)) {
            return $this->event;
        }

        $params = $rm->getParameters();
        if (count($params) === 0) {
            throw new LogicException('Event listener must have at least one parameter');
        }

        $param = $params[0];
        $type = $param->getType();
        if (!$type instanceof ReflectionNamedType) {
            throw new LogicException('Event listener must have first parameter with type hint of event');
        }

        return $type->getName();
    }
}