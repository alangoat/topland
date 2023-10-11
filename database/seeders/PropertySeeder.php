<?php

namespace Database\Seeders;

use Botble\Language\Models\LanguageMeta;
use Botble\RealEstate\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Language;
use RealEstateHelper;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $items = LanguageMeta::where('reference_type', Property::class)
            ->where('lang_meta_code', '!=', Language::getDefaultLocaleCode())
            ->get();

        foreach ($items as $item) {
            $originalItem = Property::find($item->reference_id);

            if (! $originalItem) {
                continue;
            }

            $originalItem->delete();

            $item->delete();
        }

        Property::query()->update(['expire_date' => now()->addDays(RealEstateHelper::propertyExpiredDays())]);

        DB::statement('UPDATE re_properties SET views = FLOOR(rand() * 10000) + 1;');
    }
}
