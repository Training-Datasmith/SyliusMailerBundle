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
namespace Sylius\Bundle\Mailer_Bundle\Renderer\Adapter;

use Sylius\Component\Mailer\Event\Email_Render_Event;
use Sylius\Component\Mailer\Model\Email_Interface;
use Sylius\Component\Mailer\Renderer\Adapter\Abstract_Adapter;
use Sylius\Component\Mailer\Renderer\Rendered_Email;
use Sylius\Component\Mailer\Sylius_Mailer_Events;
use Symfony\Component\Event_Dispatcher\Event_Dispatcher_Interface;
use Twig\Environment;
use Twig\Loader\Array_Loader;
class Email_Twig_Adapter extends Abstract_Adapter
{
    public function __construct(protected Environment $twig, ?Event_Dispatcher_Interface $dispatcher = null)
    {
        $this->dispatcher = $dispatcher;
    }
    public function render(Email_Interface $email, array $data = []): Rendered_Email
    {
        $rendered_email = $this->get_rendered_email($email, $data);
        $event = new Email_Render_Event($rendered_email);
        if ($this->dispatcher !== null) {
            /** @var EmailRenderEvent $event */
            $event = $this->dispatcher->dispatch($event, Sylius_Mailer_Events::EMAIL_PRE_RENDER);
        }
        return $event->get_rendered_email();
    }
    private function get_rendered_email(Email_Interface $email, array $data): Rendered_Email
    {
        if (null !== $email->get_template()) {
            return $this->provide_email_with_template($email, $data);
        }
        return $this->provide_email_without_template($email, $data);
    }
    /**
     * @psalm-suppress InternalMethod
     */
    private function provide_email_with_template(Email_Interface $email, array $data): Rendered_Email
    {
        $data = $this->twig->merge_globals($data);
        $template = $this->twig->load((string) $email->get_template())->unwrap();
        $subject = trim((string) $template->render_block('subject', $data));
        $body = $template->render_block('body', $data);
        return new Rendered_Email($subject, $body);
    }
    private function provide_email_without_template(Email_Interface $email, array $data): Rendered_Email
    {
        $twig = new Environment(new Array_Loader([]));
        $subject_template = $twig->create_template((string) $email->get_subject());
        $body_template = $twig->create_template((string) $email->get_content());
        $subject = trim((string) $subject_template->render($data));
        $body = $body_template->render($data);
        return new Rendered_Email($subject, $body);
    }
}