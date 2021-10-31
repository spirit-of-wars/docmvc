<?php

namespace DocMVC\Assembly;

use DocMVC\Exception\RenderException;

class FileRenderer
{
    /**
     * Render file content from view file
     *
     * @param $driver
     * @param $model
     * @param string $viewPath
     * @param array $params
     *
     * @return false|string
     * @throws RenderException
     */
    public function render($driver, $model, $viewPath, array $params = []): ?string
    {
        $arr = [
            'model' => $model,
            'driver' => $driver
        ];
        $arr = array_merge($arr, $params);
        extract($arr, EXTR_SKIP);

        ob_start();
        try {
            include $viewPath;
            echo $viewPath;
        } catch (\Exception $e) {
            ob_end_clean();
            throw new RenderException($e->getMessage(), $e->getCode(), $e);
        }

        return ob_get_clean();
    }
}