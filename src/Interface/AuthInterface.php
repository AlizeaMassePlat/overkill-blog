<?php

namespace App\Interface;

interface AuthInterface {
    public function authenticate($data): bool;
}
