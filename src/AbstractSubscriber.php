<?php

namespace Chaos\Service;

use Chaos\Support\Messenger\EventSubscriberInterface;

/**
 * Class AbstractSubscriber.
 *
 * @author t(-.-t) <ntd1712@mail.com>
 */
abstract class AbstractSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     *
     * @param \Psr\Container\ContainerInterface $container The Container instance.
     * @param object $instance Optional.
     *
     * @return $this
     */
    public function __invoke($container, $instance)
    {
        $container->get('dispatcher')->subscribe($container, $this);

        return $this;
    }
}
