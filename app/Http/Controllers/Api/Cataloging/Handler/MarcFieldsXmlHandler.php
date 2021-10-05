<?php


namespace App\Http\Controllers\Api\Cataloging\Handler;

use Spatie\ArrayToXml\ArrayToXml;

/**
 * Class MarcFieldsXmlHandler
 */
final class MarcFieldsXmlHandler
{
    /**
     * @var string
     */
    private string $xml;

    /**
     * MarcFieldsXmlHandler constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->xml = $this->generateXml($data);
    }

    /**
     * @return string
     */
    public function getXml(): string
    {
        return $this->xml;
    }

    /**
     * @param array $data
     * @return string
     */
    private function generateXml(array $data): string
    {
        return ArrayToXml::convert($data, [
            'rootElementName' => 'TreeList',
            '_attributes' => [
                'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                'xmlns:xsd' => 'http://www.w3.org/2001/XMLSchema'
            ],
        ], false,  'UTF-8');
    }
}
