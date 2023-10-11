<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CityRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'slug' => 'required|unique:cities,slug,' . $this->route('city'),
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
