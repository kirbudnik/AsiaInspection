<?php
// src/Acme/UserBundle/AcmeUserBundle.php

namespace AI\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AIUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}