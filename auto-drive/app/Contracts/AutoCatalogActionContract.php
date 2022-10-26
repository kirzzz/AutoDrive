<?php

namespace App\Contracts;

interface AutoCatalogActionContract
{
    public function __invoke(string $file): mixed;
}
