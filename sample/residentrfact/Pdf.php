<?php
namespace sample\residentrfact;

use SpiritOfWars\DocMVC\Cartridge\PdfCartridge;

class Pdf extends PdfCartridge
{
    protected $viewName = 'pdf/act_2016.php';

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

        $this->commonData['contract'] = $data['contract'];

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

    public function setupDocumentName()
    {
//        $contract = 345643;
        $contract = $this->commonData['contract'];
        return implode('_', ['act', $contract, '2008', '23']);
    }
}