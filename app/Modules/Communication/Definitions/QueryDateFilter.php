<?php

namespace App\Modules\Communication\Definitions;

class QueryDateFilter
{
    public $column;

    public $compareSign;

    public $value;

    /**
     * @param $column : The name of the database table column
     * @param $compareSign : The logic sign to compare <> < <= etc
     * @param $value : The value to use in the comparison
     */
    public function __construct($column, $compareSign, $value)
    {
        $this->column = $column;

        $this->compareSign = $compareSign;

        $this->value = $value;
    }
}
