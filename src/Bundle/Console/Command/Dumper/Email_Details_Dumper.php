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
namespace Sylius\Bundle\Mailer_Bundle\Console\Command\Dumper;

use Symfony\Component\Console\Input\Input_Interface;
use Symfony\Component\Console\Output\Output_Interface;
use Symfony\Component\Console\Style\Symfony_Style;
use Symfony\Contracts\Translation\Translator_Interface;
use Twig\Loader\Loader_Interface;
use Webmozart\Assert\Assert;
final readonly class Email_Details_Dumper implements Email_Detail_Dumper_Interface
{
    public function __construct(private array $emails, private ?Translator_Interface $translator, private Loader_Interface $template_loader)
    {
    }
    public function dump(string $code, Input_Interface $input, Output_Interface $output): void
    {
        $email = $this->emails[$code];
        Assert::not_null($email, sprintf('Email with code "%s" does not exist.', $code));
        $subject = $email['subject'] ?? '';
        if ($this->translator !== null) {
            $subject = $this->translator->trans($subject);
        }
        $io = new Symfony_Style($input, $output);
        $io->title(sprintf('<fg=cyan>Email:</> %s', $code));
        $io->writeln(sprintf('<comment>Subject:</comment> %s', $subject));
        $io->writeln(sprintf('<comment>Enabled:</comment> %s', $email['enabled'] ? '<info>yes</info>' : '<error>no</error>'));
        $io->new_line();
        $io->text($this->template_loader->get_source_context($email['template'])->get_code());
    }
}