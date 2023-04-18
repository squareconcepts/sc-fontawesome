<?php

namespace Squareconcepts\ScFontAwesome\Components;

use Livewire\Component;
use Squareconcepts\ScFontAwesome\ScFontAwesome;

class FontAwesomeComponent extends Component
{
    public ScFontAwesome $service;
    public $style;
    public $name;
    public $icons = [];
    public $field;
    public $value;
    public $event;

    protected array $rules = [
        'style' => 'nullable',
        'name' => 'nullable',
        'icons' => 'nullable',
        'icon' => 'nullable'
    ];

    public function mount()
    {
        $this->service = new ScFontAwesome();

        if (!empty($this->value)) {
            $values = explode(' ', $this->value);

            if (!empty($values[0])) {
                $this->style = $values[0];
            }

            if (!empty($values[1])) {
                $name = explode('fa-', $values[1]);

                if (!empty($name[1])) {
                    $this->name = $name[1];
                } else {
                    $this->name = $values[1];
                }
            }
        }
    }

    public function render()
    {
        return view('sc-fontawesome::sc-fontawesome-component', ['styles' => ScFontAwesome::getStyles()]);
    }

    public function emitValue()
    {
        $this->emit($this->event, $this->field, $this->value);
    }

    public function searchName()
    {
        $data = $this->service->searchIcon($this->name);

        if (!empty($data) && $data['success'] === true) {
            $this->icons = $data['data'];
        }

        $this->value = $this->style . ' fa-' . $this->name;
        $this->emitValue();
    }
}
