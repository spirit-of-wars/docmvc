<?php

use sample\residentrfact\Excel;
use sample\residentrfact\Pdf;
use sample\test\Doc;
use SpiritOfWars\DocMVC\DocumentManager;

require_once 'vendor/autoload.php';

$testDoc = new Doc([
    'test' => 'test content',
    'randParam' => 'random param'
]);

$fileManager = new DocumentManager($testDoc);

$fileManager->build()->download();

$excelReport = new Excel();

//$fileManager->load($excelReport)->rewritableModeOn()->build()->saveToDir('C:\test-document')->download();


$pdfReport = new Pdf();

//$fileManager->load($pdfReport)->rewritableModeOn()->build()->saveToDir('C:\test-document')->download();