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
namespace Sylius\Bundle\Mailer_Bundle\Console\Command;

use Sylius\Bundle\Mailer_Bundle\Console\Command\Dumper\Dumper_Interface;
use Sylius\Bundle\Mailer_Bundle\Console\Command\Dumper\Email_Detail_Dumper_Interface;
use Symfony\Component\Console\Attribute\As_Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Input_Argument;
use Symfony\Component\Console\Input\Input_Interface;
use Symfony\Component\Console\Output\Output_Interface;
use Webmozart\Assert\Assert;
#[As_Command(name: 'sylius:debug:mailer', description: 'Debug email messages')]
final class Debug_Mailer_Command extends Command
{
    public function __construct(
        /** @var DumperInterface[] $dumpers */
        private readonly iterable $dumpers,
        private readonly Email_Detail_Dumper_Interface $email_detail_dumper
    )
    {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this->add_argument('codeOfEmail', Input_Argument::OPTIONAL, 'Expected email to be shown identified by its code');
    }
    protected function execute(Input_Interface $input, Output_Interface $output): int
    {
        if ($input->get_argument('codeOfEmail') === null) {
            return $this->dump_all_emails($input, $output);
        }
        return $this->dump_email_details($input, $output);
    }
    private function dump_all_emails(Input_Interface $input, Output_Interface $output): int
    {
        foreach ($this->dumpers as $dumper) {
            $dumper->dump($input, $output);
        }
        return Command::SUCCESS;
    }
    private function dump_email_details(Input_Interface $input, Output_Interface $output): int
    {
        $code_of_email = $input->get_argument('codeOfEmail');
        Assert::string($code_of_email);
        $this->email_detail_dumper->dump($code_of_email, $input, $output);
        return Command::SUCCESS;
    }
}