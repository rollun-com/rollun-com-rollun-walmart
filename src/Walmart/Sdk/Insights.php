<?php


namespace rollun\Walmart\Sdk;


class Insights extends Base
{
    public function getTopTrendingItems(
        $departmentId,
        $categoryId = null,
        $limit = 1000,
        $offset = 0,
        callable $callback = null
    ) {
        $path = 'insights/items/trending';

        $params = array_filter(
            compact('departmentId', 'categoryId', 'limit', 'offset'),
            function ($value) {
                return $value !== null;
            }
        );

        $path .= '?' . http_build_query($params);

        $response = $this->request($path);

        if ($callback) {
            $response = $callback($response);
        }

        return $response;
    }
}