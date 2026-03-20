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
final readonly class Sender_Data_Dumper implements Dumper_Interface
{
    public function __construct(private string $sender_name, private string $sender_email)
    {
    }
    public function dump(Input_Interface $input, Output_Interface $output): void
    {
        $io = new Symfony_Style($input, $output);
        $io->section('<info>Sender</info>');
        $io->horizontal_table(['Name', 'Email'], [[$this->sender_name, $this->sender_email]]);
    }
}