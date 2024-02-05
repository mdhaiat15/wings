<?php

namespace App\View\Components\Input;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $item = array(),
        public array $arrayLookup = array(),
        public array $arrayAttachment = array(),
        public bool $isFilter = false,
        public bool $isSearch = false,
        public mixed $onChange = '',
        public mixed $value = '',
        public string $formName = '',
        public string $key = '',
        public string $rowId = '',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input.input');
    }

    public function getId()
    {
        $id = '';
        if ($this->rowId != "") {
            $id = $this->formName . '_' . $this->rowId . '_' . $this->key;
        } else {
            $id = $this->formName . $this->key;
        }

        return $id;
    }

    public function getName()
    {
        $name = '';
        if ($this->rowId != "") {
            $name = $this->formName . '[' . $this->rowId . '][' . $this->key . ']';
        } else {
            $name = $this->key;
        }

        return $name;
    }

    public function getFormFieldName()
    {
        $name = '';
        if ($this->rowId != "") {
            $name = $this->formName . '.' . $this->rowId . '.' . $this->key;
        } else {
            $name = $this->key;
        }

        return $name;
    }
}
