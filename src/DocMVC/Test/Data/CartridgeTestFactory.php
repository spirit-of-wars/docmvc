<?php

namespace SpiritOfWars\DocMVC\Test\Data;

use SpiritOfWars\DocMVC\Cartridge\SetupCartridgeInterface;

class CartridgeTestFactory
{
    /**
     * @return SetupCartridgeInterface[]
     */
    public static function createTestCartridges(): array
    {
        $docCartridge = self::createTestDocCartridge([
            'randParam' => 'random param'
        ]);
        $excelCartridge = self::createTestExcelCartridge();
        $pdfCartridge = self::createTestPdfCartridge();

        return [$docCartridge, $excelCartridge, $pdfCartridge];
    }

    /**
     * @param array $params
     * @return \SpiritOfWars\DocMVC\sample\test\Doc
     */
    public static function createTestDocCartridge($params = [])
    {
        $defaultParams = [
            'test' => 'test content'
        ];
        $resultParams = array_merge($defaultParams, $params);

        return new \SpiritOfWars\DocMVC\sample\test\Doc($resultParams);
    }

    /**
     * @param array $params
     * @return \SpiritOfWars\DocMVC\sample\residentrfact\Excel
     */
    public static function createTestExcelCartridge($params = [])
    {
        return new \SpiritOfWars\DocMVC\sample\residentrfact\Excel($params);
    }

    /**
     * @param array $params
     * @return \SpiritOfWars\DocMVC\sample\residentrfact\Pdf
     */
    public static function createTestPdfCartridge($params = [])
    {
        return new \SpiritOfWars\DocMVC\sample\residentrfact\Pdf($params);
    }
}