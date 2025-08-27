<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Input extends Component
{
    public $type;
    public $fieldId;
    public $fieldName;
    public $fieldValue;
    public $fieldPlaceholder;
    public $fieldReadOnly;
    public $fieldRequired;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type,
        $fieldId,
        $fieldName,
        $fieldValue = null,
        $fieldPlaceholder = null,
        $fieldReadOnly = false,
        $fieldRequired = false
    )
    {
        $this->type = $type;
        $this->fieldId = $fieldId;
        $this->fieldName = $fieldName;
        $this->fieldValue = $fieldValue;
        $this->fieldPlaceholder = $fieldPlaceholder;
        $this->fieldReadOnly = $fieldReadOnly;
        $this->fieldRequired = $fieldRequired;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.input');
    }
}
