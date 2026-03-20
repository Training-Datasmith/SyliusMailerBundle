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
namespace Sylius\Component\Mailer\Provider;

use Sylius\Component\Mailer\Factory\Email_Factory_Interface;
use Sylius\Component\Mailer\Model\Email_Interface;
use Webmozart\Assert\Assert;
final class Email_Provider implements Email_Provider_Interface
{
    public function __construct(private readonly Email_Factory_Interface $email_factory, private array $configuration)
    {
    }
    public function get_email(string $code): Email_Interface
    {
        return $this->get_email_from_configuration($code);
    }
    private function get_email_from_configuration(string $code): Email_Interface
    {
        Assert::key_exists($this->configuration, $code, sprintf('Email with code "%s" does not exist!', $code));
        /** @var EmailInterface $email */
        $email = $this->email_factory->create_new();
        $configuration = $this->configuration[$code];
        $email->set_code($code);
        if (isset($configuration['subject'])) {
            $email->set_subject($configuration['subject']);
        }
        $email->set_template($configuration['template']);
        if (isset($configuration['enabled']) && false === $configuration['enabled']) {
            $email->set_enabled(false);
        }
        if (isset($configuration['sender']['name'])) {
            $email->set_sender_name($configuration['sender']['name']);
        }
        if (isset($configuration['sender']['address'])) {
            $email->set_sender_address($configuration['sender']['address']);
        }
        return $email;
    }
}