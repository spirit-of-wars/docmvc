<?php
namespace DocMVC\sample\residentrfact;

use DocMVC\Cartridge\ExcelCartridge;

class Excel extends ExcelCartridge
{
    protected $viewName = 'excel/view.php';
    protected $tmpName = 'excel/act_2016.xlsx';

    public function setupModel()
    {
        $data = [
            'contract' => 'R96000',
            'new_dogovor' => 123,
            'new_dog_date' => '12.21.2344',
            'name' => 'test name',
            'rang_name' => 'test rang name',
            'rang_number' => '123',
            'short_name' => 'test',

        ];

        $dt1 = '01.01.2018';
        $dt2 = '01.07.2018';

        $this->chosenParams['contract'] = $data['contract'];

        return [
            'data' => $data,
            'period' => '2008-02-02',
            'dt1' => $dt1,
            'dt2' => $dt2,
        ];
    }

    public function setupView()
    {
        return $this->viewName;
    }

    public function setupTemplate()
    {
        return $this->tmpName;
    }

    public function setupDocName()
    {
//        $contract = $this->chosenParams['contract'];
        $contract = 345864;
        return implode('_', ['act', $contract, '2008', '23']);
    }
}