<?php


namespace rollun\Walmart\Sdk;


class Utilities extends Base
{
    public function getAllDepartments(callable $callback = null)
    {
        $path = "utilities/taxonomy/departments";

        $response = $this->request($path);

        if ($callback) {
            $response = $callback($response);
        }

        return $response;
    }

    public function getAllCategories($departmentId, callable $callback = null)
    {
        $path = 'utilities/taxonomy/departments/' . $departmentId;

        $response = $this->request($path);

        if ($callback) {
            $result = $callback($response);
        }

        return $result;
    }
}