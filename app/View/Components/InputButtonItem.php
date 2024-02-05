<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputButtonItem extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $text = '',
        public string $confirm = '',
        public bool $overrideColor = false,
        public bool $overrideBackground = false,
        public string $formName = '',
        public string $key = '',
        public string $rowId = '',)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input-button-item');
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
}
