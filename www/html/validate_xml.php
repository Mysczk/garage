<?php

function validateXmlAgainstXsd(string $xmlString, string $xsdPath): array
{
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    if (!$dom->loadXML($xmlString)) {
        return [
            'success' => false,
            'message' => '❌ Chyba načtení XML.',
            'errors' => libxml_get_errors()
        ];
    }

    if (!$dom->schemaValidate($xsdPath)) {
        return [
            'success' => false,
            'message' => '❌ XML není validní dle XSD.',
            'errors' => libxml_get_errors()
        ];
    }

    return [
        'success' => true,
        'message' => '✅ XML je validní.',
        'errors' => []
    ];
}
