<?php

namespace Theme\FlexHome\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Theme;

class PropertyHTMLResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'HTML' => Theme::partial('real-estate.properties.item', ['property' => $this]),
        ];
    }
}
