<?php

namespace Avl\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class UserBundle
 * @package Avl\UserBundle
 */
class UserBundle extends Bundle {

    /**
     * @return string
     */
    public function getParent() {
        return 'FOSUserBundle';
    }
}