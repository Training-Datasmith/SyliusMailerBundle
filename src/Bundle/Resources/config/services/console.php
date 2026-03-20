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
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Sylius\Bundle\Mailer_Bundle\Console\Command\Debug_Mailer_Command;
use Sylius\Bundle\Mailer_Bundle\Console\Command\Dumper\Email_Detail_Dumper_Interface;
use Sylius\Bundle\Mailer_Bundle\Console\Command\Dumper\Email_Details_Dumper;
use Sylius\Bundle\Mailer_Bundle\Console\Command\Dumper\Emails_List_Dumper;
use Sylius\Bundle\Mailer_Bundle\Console\Command\Dumper\Sender_Data_Dumper;
use Symfony\Contracts\Translation\Translator_Interface;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->defaults()->public();
    $services->set(Email_Detail_Dumper_Interface::class, Email_Details_Dumper::class)->args(['%sylius.mailer.emails%', service(Translator_Interface::class)->null_on_invalid(), service('twig.loader')]);
    $services->set(Emails_List_Dumper::class)->args(['%sylius.mailer.emails%', service(Translator_Interface::class)->null_on_invalid()])->tag('sylius_mailer.dumper');
    $services->set(Sender_Data_Dumper::class)->args(['%sylius.mailer.sender_name%', '%sylius.mailer.sender_address%'])->tag('sylius_mailer.dumper');
    $services->set(Debug_Mailer_Command::class)->autoconfigure()->args([tagged_iterator('sylius_mailer.dumper'), service(Email_Detail_Dumper_Interface::class)]);
};