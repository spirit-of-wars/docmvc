<?php
namespace DocMVC\sample\residentrfact;

use \DocMVC\Excel as PExcel;

class Excel extends PExcel
{
    protected $viewName = 'excel/view.php';
    protected $tmpName = 'excel/act_2016.xlsx';

    protected function setupModel()
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

    protected function setupView()
    {
        return $this->viewName;
    }

    protected function setupTemplate()
    {
        return $this->tmpName;
    }

    protected function setupRequiredParams()
    {
        return [];
    }

    protected function setupDocName()
    {
        $contract = $this->chosenParams['contract'];
        return implode('_', ['act', $contract, '2008', '23']);
    }
}