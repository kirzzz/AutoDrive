<?php

namespace App\Actions;

use App\Contracts\AutoCatalogActionContract;

class AutoCatalogParseAction implements AutoCatalogActionContract
{
    public function __invoke(string $file): ?array
    {
        $xmlFile = file_get_contents(public_path($file));

        $xmlObject = simplexml_load_string($xmlFile);

        $jsonFormatData = json_encode($xmlObject);
        $jsonFormatData = json_decode($jsonFormatData, true);
        return $jsonFormatData['offers']['offer'] ?? null;
    }
}
