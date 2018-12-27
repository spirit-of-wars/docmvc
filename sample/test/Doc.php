<?php

namespace DocMVC\sample\test;

use \DocMVC\Doc as PDoc;

class Doc extends PDoc
{

    public function setupTemplate()
    {
        return null;
    }

    public function setupView()
    {
        return 'doc/view.php';
    }

    public function setupModel()
    {
        $test = $this->params['test']; // required param
        $randParam = $this->params['test2'];
        return [
            'test' => $test,
            'randParam' => $randParam
        ];
    }

    public function setupRequiredParams()
    {
        return ['test'];
    }

    public function setupDocName()
    {
        return 'test-name';
    }
}