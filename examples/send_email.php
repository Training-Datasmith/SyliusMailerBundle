<?php

declare(strict_types=1);

/**
 * SyliusMailerBundle — sending a custom email example.
 *
 * Shows how to define an email, configure the bundle, and send it.
 *
 * --- config/packages/sylius_mailer.yaml ---
 *
 * sylius_mailer:
 *     sender:
 *         name: 'My Shop'
 *         address: noreply@myshop.com
 *     emails:
 *         order_confirmation:
 *             subject: 'Order #{{ order.number }} confirmed'
 *             template: 'email/order_confirmation.html.twig'
 *
 * --- templates/email/order_confirmation.html.twig ---
 *
 * <h1>Thank you for your order, {{ order.customer.fullName }}!</h1>
 * <p>Your order #{{ order.number }} has been confirmed.</p>
 *
 * --- Service usage in a controller or event listener ---
 *
 * use Sylius\Component\Mailer\Sender\SenderInterface;
 *
 * class OrderConfirmationListener
 * {
 *     public function __construct(private SenderInterface $sender) {}
 *
 *     public function onOrderComplete(OrderEvent $event): void
 *     {
 *         $order = $event->getOrder();
 *
 *         $this->sender->send(
 *             'order_confirmation',
 *             [$order->getCustomer()->getEmail()],
 *             ['order' => $order]
 *         );
 *     }
 * }
 *
 * --- services.yaml ---
 *
 * App\EventListener\OrderConfirmationListener:
 *     tags:
 *         - { name: kernel.event_listener, event: sylius.order.post_complete }
 *
 * --- Debug: list all configured emails ---
 *
 * bin/console debug:sylius-mailer
 */

echo 'SyliusMailerBundle requires a Symfony kernel.' . PHP_EOL;
echo 'See the docblock above for email sending patterns.' . PHP_EOL;
