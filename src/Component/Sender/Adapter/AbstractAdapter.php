<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare (strict_types=1);
namespace Sylius\Component\Mailer\Sender\Adapter;

use Symfony\Component\Event_Dispatcher\Event_Dispatcher_Interface;
abstract class Abstract_Adapter implements Adapter_Interface
{
    protected ?Event_Dispatcher_Interface $dispatcher = null;
    public function set_event_dispatcher(Event_Dispatcher_Interface $dispatcher): void
    {
        $this->dispatcher = $dispatcher;
    }
}