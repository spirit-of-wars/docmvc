<?php
$objPHPExcel = $driver;

$headersData = $model['headersData'];
$data = $model['items'];
$cntr = $model['cntr'];
$datePeriod = $model['datePeriod'];

$styleArray = [
    'font'  => [
        'size'  => 9,
    ]
];

$objPHPExcel->getDefaultStyle()
    ->applyFromArray($styleArray)
    ->getAlignment()
    ->setVertical(PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0);

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getRowDimension(1)->setRowHeight(22);
$sheet->mergeCells('E1:K1');
$sheet->setCellValue('E1', 'Фактуры за период' . '. ' . $datePeriod);
$sheet->getStyle('E1')->applyFromArray([
    'font'  => [
        'bold' => true,
        'size'  => 14,
    ]
])->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
//        $sheet->setTitle(lang('Фактуры за период'));
$sheet->getRowDimension(3)->setRowHeight(40);
foreach ($headersData as $headerData) {
    $cell = $headerData['column'] .'3';
    $sheet->setCellValue($cell, $headerData['text']);
    $styleCell = $sheet->getStyle($cell);
    $styleCell->getBorders()->getAllBorders()
        ->setBorderStyle(PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
    $styleCell->getAlignment()
        ->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
        ->setVertical(PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
        ->setWrapText(true);

    if(!empty($headerData['width'])) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($headerData['column'])->setWidth($headerData['width']);
    }

}
