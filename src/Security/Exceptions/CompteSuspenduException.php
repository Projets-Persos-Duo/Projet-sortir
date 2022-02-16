<?php

namespace App\Security\Exceptions;

class CompteSuspenduException extends \Symfony\Component\Security\Core\Exception\DisabledException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Ce compte est désactivé.';
    }

}