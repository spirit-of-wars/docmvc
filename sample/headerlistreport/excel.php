<?php
namespace DocMVC\sample\headerlistreport;

use \DocMVC\src\Excel as PExcel;

class Excel extends PExcel
{
    const FORMAT_MONEY = 'money';

    protected $viewName = 'excel/view.php';

    protected function setupModel()
    {

        return [
            'headersData' => $this->getHeadersData(),
            'datePeriod' => '01-02-2018',
            'cntr' => 'ru'
        ];
    }

    protected function setupRequiredParams()
    {
        return [];
    }

    protected function setupView()
    {
        return $this->viewName;
    }

    protected function setupTemplate()
    {
        return null;
    }

    protected function getHeadersData()
    {
        $data = [
            [
                'text' => 'Номер',
                'column' => 'A',
                'width' => '20',
                'format' => ''
            ],
            [
                'text' => 'Дата',
                'column' => 'B',
                'width' => '10',
                'format' => ''
            ],
            [
                'text' => 'Дата регистрации',
                'column' => 'C',
                'width' => '10',
                'format' => ''
            ],
            [
                'text' => 'Дата оплаты',
                'column' => 'D',
                'width' => '10',
                'format' => ''
            ],
            [
                'text' => 'Менеджер',
                'column' => 'E',
                'width' => '20',
                'format' => ''
            ],
            [
                'text' => 'Кто купил',
                'column' => 'F',
                'width' => '20',
                'format' => ''
            ],
            [
                'text' => 'Для кого',
                'column' => 'G',
                'width' => '20',
                'format' => ''
            ],
            [
                'text' => 'Сумма',
                'column' => 'H',
                'width' => '15',
                'format' => self::FORMAT_MONEY
            ],
            [
                'text' => 'Налог',
                'column' => 'I',
                'width' => '15',
                'format' => self::FORMAT_MONEY
            ],
            [
                'text' => 'Сумма с налогом',
                'column' => 'J',
                'width' => '20',
                'format' => self::FORMAT_MONEY
            ],
            [
                'text' => 'Оплачено серт. / Промокод',
                'column' => 'K',
                'width' => '15',
                'format' => ''
            ],
            [
                'text' => 'БЗ',
                'column' => 'L',
                'width' => '15',
                'format' => self::FORMAT_MONEY
            ],
            [
                'text' => 'Б25',
                'column' => 'M',
                'width' => '15',
                'format' => self::FORMAT_MONEY
            ],
            [
                'text' => 'ЭС',
                'column' => 'N',
                'width' => '8',
                'format' => self::FORMAT_MONEY
            ],
        ];

        return $data;
    }
}