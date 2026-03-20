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
namespace Sylius\Component\Mailer\Modifier;

use Sylius\Component\Mailer\Model\Email_Interface;
final readonly class Composite_Email_Modifier implements Email_Modifier_Interface
{
    /**
     * @param EmailModifierInterface[] $emailModifiers
     */
    public function __construct(private iterable $email_modifiers = [])
    {
    }
    public function modify(Email_Interface $email, array $factors = []): Email_Interface
    {
        foreach ($this->email_modifiers as $modifier) {
            $email = $modifier->modify($email, $factors);
        }
        return $email;
    }
}