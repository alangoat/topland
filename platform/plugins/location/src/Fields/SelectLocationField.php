<?php

namespace Botble\Location\Fields;

use Assets;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Html;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Kris\LaravelFormBuilder\Fields\FormField;
use Kris\LaravelFormBuilder\Form;

class SelectLocationField extends FormField
{
    protected CountryInterface $countryRepository;

    protected StateInterface $stateRepository;

    protected CityInterface $cityRepository;

    protected array $locationKeys = [];

    public function __construct(
        $name,
        $type,
        Form $parent,
        array $options = []
    ) {
        parent::__construct($name, $type, $parent);

        $default = [
            'country' => 'country_id',
            'state' => 'state_id',
            'city' => 'city_id',
        ];
        $this->locationKeys = array_filter(array_merge($default, Arr::get($options, 'locationKeys', [])));

        $this->name = $name;
        $this->type = $type;
        $this->parent = $parent;
        $this->formHelper = $this->parent->getFormHelper();

        $this->setTemplate();
        $this->setDefaultOptions($options);
        $this->setupValue();
        $this->initFilters();

        $this->countryRepository = app(CountryInterface::class);
        $this->stateRepository = app(StateInterface::class);
        $this->cityRepository = app(CityInterface::class);

        Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');
    }

    protected function getConfig($key = null, $default = null)
    {
        return $this->parent->getConfig($key, $default);
    }

    protected function setTemplate(): void
    {
        $this->template = $this->getConfig($this->getTemplate(), $this->getTemplate());
    }

    protected function setupValue(): void
    {
        $values = $this->getOption($this->valueProperty);
        foreach ($this->locationKeys as $k => $v) {
            $value = Arr::get($values, $k);
            if ($value === null) {
                $value = old($v, $this->getModelValueAttribute($this->parent->getModel(), $v));
            }

            $values[$k] = $value;
        }
        $this->setValue($values);
    }

    public function getCountryOptions(): array
    {
        $countryKey = Arr::get($this->locationKeys, 'country');
        $countries = $this->countryRepository->pluck('name', 'id');
        $value = Arr::get($this->getValue(), 'country');
        $attr = array_merge($this->getOption('attr', []), [
            'id' => $countryKey,
            'class' => 'select-search-full',
            'data-type' => 'country',
        ]);

        return array_merge([
            'label' => trans('plugins/location::city.country'),
            'attr' => $attr,
            'choices' => ['' => trans('plugins/location::city.select_country')] + $countries,
            'selected' => $value,
            'empty_value' => null,
        ], $this->getOption('attrs.country', []));
    }

    public function getStateOptions(): array
    {
        $states = [];
        $stateKey = Arr::get($this->locationKeys, 'state');
        $countryId = Arr::get($this->getValue(), 'country');
        $value = Arr::get($this->getValue(), 'state');
        if ($countryId) {
            $states = $this->stateRepository->pluck('name', 'id', [['country_id', '=', $countryId]]);
        }

        $attr = array_merge($this->getOption('attr', []), [
            'id' => $stateKey,
            'data-url' => route('ajax.states-by-country'),
            'class' => 'select-search-full',
            'data-type' => 'state',
        ]);

        return array_merge([
            'label' => trans('plugins/location::city.state'),
            'attr' => $attr,
            'choices' => ['' => trans('plugins/location::city.select_state')] + $states,
            'selected' => $value,
            'empty_value' => null,
        ], $this->getOption('attrs.state', []));
    }

    public function getCityOptions(): array
    {
        $cities = [];
        $cityKey = Arr::get($this->locationKeys, 'city');
        $stateId = Arr::get($this->getValue(), 'state');
        $value = Arr::get($this->getValue(), 'city');
        if ($stateId) {
            $cities = $this->cityRepository->pluck('name', 'id', [['state_id', '=', $stateId]]);
        }

        $attr = array_merge($this->getOption('attr', []), [
            'id' => $cityKey,
            'data-url' => route('ajax.cities-by-state'),
            'class' => 'select-search-full',
            'data-type' => 'city',
        ]);

        return array_merge([
            'label' => trans('plugins/location::city.city'),
            'attr' => $attr,
            'choices' => ['' => trans('plugins/location::city.select_city')] + $cities,
            'selected' => $value,
            'empty_value' => null,
        ], $this->getOption('attrs.city', []));
    }

    public function render(
        array $options = [],
        $showLabel = true,
        $showField = true,
        $showError = true
    ): HtmlString|string {
        $html = '';

        $this->prepareOptions($options);

        if ($showField) {
            $this->rendered = true;
        }

        if (! $this->needsLabel()) {
            $showLabel = false;
        }

        if ($showError) {
            $showError = $this->parent->haveErrorsEnabled();
        }

        $data = $this->getRenderData();

        foreach ($this->locationKeys as $k => $v) {
            // Override default value with value
            $options = [];
            switch ($k) {
                case 'country':
                    $options = $this->getCountryOptions();

                    break;
                case 'state':
                    $options = $this->getStateOptions();

                    break;
                case 'city':
                    $options = $this->getCityOptions();

                    break;
            }

            $options = array_merge($this->options, $options);

            $html .= $this->formHelper->getView()->make(
                $this->getViewTemplate(),
                $data + [
                    'name' => $v,
                    'nameKey' => $v,
                    'type' => $this->type,
                    'options' => $options,
                    'showLabel' => $showLabel,
                    'showField' => $showField,
                    'showError' => $showError,
                    'errorBag' => $this->parent->getErrorBag(),
                    'translationTemplate' => $this->parent->getTranslationTemplate(),
                ]
            )->render();
        }

        return Html::tag(
            'div',
            $html,
            ['class' => ($this->getOption('wrapperClassName') ?: 'row g-1') . ' select-location-fields']
        );
    }

    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.custom-select';
    }
}
