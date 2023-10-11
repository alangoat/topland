<?php

namespace Botble\RealEstate\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'symbol' => $this->symbol,
        ];
    }
}
