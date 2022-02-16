<?php

namespace App\Security\Exceptions;

class CompteSuspenduException extends \Symfony\Component\Security\Core\Exception\DisabledException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey(): string
    {
        return 'Ce compte est désactivé.';
    }

}