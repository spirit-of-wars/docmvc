<?php

namespace docMVC\sample;

$testDoc = new test\Doc([
    'test' => 'test content',
    'randParam' => 'random param'
]);

$testDoc->download();


$excelReport = new headerlistreport\excel();

$excelReport->download();

$pdfAct = new residentrfact\PDF();

$pdfAct->download();