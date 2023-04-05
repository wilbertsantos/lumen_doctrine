<?php

namespace App\Importers;

interface ImporterInterface
{
    public function import(array $data): void;
}
