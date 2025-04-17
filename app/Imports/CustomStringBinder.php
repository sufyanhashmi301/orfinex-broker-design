<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\IValueBinder;

class CustomStringBinder extends DefaultValueBinder implements IValueBinder
{
    public function bindValue(Cell $cell, $value)
    {
        // Convert all numeric values (like phone numbers) to string
        if (is_numeric($value)) {
            $value = (string) $value;
        }

        return parent::bindValue($cell, $value);
    }
}
