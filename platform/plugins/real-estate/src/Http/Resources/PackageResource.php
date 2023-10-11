<?php

namespace Botble\RealEstate\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'price_text' => $this->price_format,
            'price_per_post_text' => $this->price_per_listing_format . ' / ' . trans('plugins/real-estate::dashboard.per_post'),
            'percent_save' => $this->percent_save,
            'number_of_listings' => $this->number_of_listings,
            'number_posts_free' => trans('plugins/real-estate::dashboard.free') . ' ' . $this->number_of_listings . ' ' . trans('plugins/real-estate::dashboard.posts'),
            'price_text_with_sale_off' => $this->price_format . ' ' . trans('plugins/real-estate::dashboard.total') . ' (' . trans('plugins/real-estate::dashboard.percent_save') . ' ' . $this->percent_save . '%)',
        ];
    }
}
