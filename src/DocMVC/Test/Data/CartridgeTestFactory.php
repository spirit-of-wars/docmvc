<?php

namespace DocMVC\Test\Data;

use DocMVC\Cartridge\SetupCartridgeInterface;

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
     * @return \DocMVC\sample\test\Doc
     */
    public static function createTestDocCartridge($params = [])
    {
        $defaultParams = [
            'test' => 'test content'
        ];
        $resultParams = array_merge($defaultParams, $params);

        return new \DocMVC\sample\test\Doc($resultParams);
    }

    /**
     * @param array $params
     * @return \DocMVC\sample\residentrfact\Excel
     */
    public static function createTestExcelCartridge($params = [])
    {
        return new \DocMVC\sample\residentrfact\Excel($params);
    }

    /**
     * @param array $params
     * @return \DocMVC\sample\residentrfact\Pdf
     */
    public static function createTestPdfCartridge($params = [])
    {
        return new \DocMVC\sample\residentrfact\Pdf($params);
    }
}