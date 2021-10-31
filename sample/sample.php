<?php

namespace DocMVC\sample;


require_once 'vendor/autoload.php';

$testDoc = new \DocMVC\sample\test\Doc([
    'test' => 'test content',
    'randParam' => 'random param'
]);

$fileManager = new \DocMVC\DocumentManager\DocumentManager($testDoc);

$fileManager->build()->download();

$excelReport = new \DocMVC\sample\residentrfact\Excel();

$fileManager->load($excelReport)->build()->download();


$pdfReport = new \DocMVC\sample\residentrfact\Pdf();

$fileManager->load($pdfReport)->build()->download();