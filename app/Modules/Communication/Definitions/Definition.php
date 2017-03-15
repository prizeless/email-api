<?php
namespace App\Modules\Communication\Definitions;

class Definition
{
    protected function assignAttributes(array $object)
    {
        $classVariables = array_keys(get_class_vars(get_called_class()));

        foreach ($classVariables as $variable) {
            $this->setAttributeValue($object, $variable);
        }

    }

    /**
     * @param array $object
     * @param $variable
     */
    protected function setAttributeValue(array $object, $variable)
    {
        if (empty($object[$variable]) === false) {
            $this->{$variable} = $object[$variable];
        }
    }
}
