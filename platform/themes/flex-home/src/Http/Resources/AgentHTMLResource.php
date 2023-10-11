<?php

namespace Theme\FlexHome\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Theme;

class AgentHTMLResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'HTML' => Theme::partial('real-estate.agents.item', ['account' => $this]),
        ];
    }
}
