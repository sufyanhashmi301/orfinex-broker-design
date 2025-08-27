<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Field extends Component
{
    public $type;
    public $fieldId;
    public $fieldName;
    public $fieldLabel;
    public $fieldValue;
    public $fieldPlaceholder;
    public $fieldReadOnly;
    public $fieldRequired;
    public $fieldHelp;
    public $popover;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type = 'text',
        $fieldId = null,
        $fieldName = null,
        $fieldLabel = null,
        $fieldValue = null,
        $fieldPlaceholder = null,
        $fieldReadOnly = false,
        $fieldRequired = false,
        $fieldHelp = null,
        $popover = null,
    )
    {
        $this->type = $type;
        $this->fieldId = $fieldId ?? $fieldName;
        $this->fieldName = $fieldName;
        $this->fieldLabel = $fieldLabel;
        $this->fieldValue = $fieldValue;
        $this->fieldPlaceholder = $fieldPlaceholder;
        $this->fieldReadOnly = $fieldReadOnly;
        $this->fieldRequired = $fieldRequired;
        $this->fieldHelp = $fieldHelp;
        $this->popover = $popover;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.field');
    }
} 