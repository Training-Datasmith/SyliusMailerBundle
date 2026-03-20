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

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\Input_Interface;
use Symfony\Component\Console\Output\Output_Interface;
use Symfony\Component\Console\Style\Symfony_Style;
use Symfony\Contracts\Translation\Translator_Interface;
final readonly class Emails_List_Dumper implements Dumper_Interface
{
    public function __construct(private array $emails, private ?Translator_Interface $translator)
    {
    }
    public function dump(Input_Interface $input, Output_Interface $output): void
    {
        $io = new Symfony_Style($input, $output);
        $rows = [];
        foreach ($this->emails as $code => $email_configuration) {
            $subject = $email_configuration['subject'] ?? '';
            if ($this->translator !== null) {
                $subject = $this->translator->trans($subject);
            }
            $rows[] = [$code, $email_configuration['template'], $email_configuration['enabled'] ? 'yes' : 'no', $subject];
        }
        $io->section('<info>Emails</info>');
        $table = new Table($output);
        $table->set_headers(['Code', 'Template', 'Enabled', 'Subject']);
        $table->set_rows($rows);
        $table->render();
    }
}