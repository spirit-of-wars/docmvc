<?php

namespace DocMVC\traits;

use DocMVC\Exception\RenderException;

trait RenderTrait
{
    /**
     * Render file content from view file
     *
     * @throws RenderException
     *
     * @return string
     */

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
    protected function render($driver, $model, $viewPath, array $params = [])
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
            throw new RenderException($e->getMessage());
        }

        return ob_get_clean();
    }
}