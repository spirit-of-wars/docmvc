<?php
/**
 * @var $driver
 * @var array $model
 */
$objPHPExcel = $driver;

$data = $model['data'];
$period = $model['period'];
$dt1 = $model['dt1'];
$dt2 = $model['dt2'];

$objPHPExcel->setActiveSheetIndex(0);

$sheet = $objPHPExcel->getActiveSheet();
$monthRus = [
    1 => 'январь',  2 => 'февраль', 3 => 'март', 4 => 'апрель',
    5 => 'май',  6 => 'июнь', 7 => 'июль', 8 => 'август',
    9 => 'сентябрь', 10 => 'октябрь', 11 => 'ноябрь', 12 => 'декабрь',
];

# Вторая строка заголовка договора (номер, дата и период)
$sheet->setCellValueByColumnAndRow(1, 2, "№ {$data['new_dogovor']} от {$data['new_dog_date']} за период с {$dt1} по {$dt2}");

# Наименование исполнителя
$sheet->setCellValueByColumnAndRow(1, 5, $data['name']);

# Контракт исполнителя
$sheet->setCellValueByColumnAndRow(10, 5, $data['contract']);

# Статус (сокращённое наименование ранга)
$sheet->setCellValueByColumnAndRow(1, 7, "Статус: {$data['rang_name']}");

# Ранг (число)
$sheet->setCellValueByColumnAndRow(1, 8, "Ступень: {$data['rang_number']}");

# Краткое наименование заказчика (т.е. нашего предприятия)
$sheet->setCellValueByColumnAndRow(1, 9, "Передал, а : Заказчик {$data['short_name']}");