<?php

namespace Database\Seeders;

use Botble\Career\Models\Career;
use Botble\Language\Models\LanguageMeta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Language;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        $items = LanguageMeta::where('reference_type', Career::class)
            ->where('lang_meta_code', '!=', Language::getDefaultLocaleCode())
            ->get();

        foreach ($items as $item) {
            $originalItem = Career::find($item->reference_id);

            if (! $originalItem) {
                continue;
            }

            $originalId = LanguageMeta::where('lang_meta_origin', $item->lang_meta_origin)
                ->where('lang_meta_code', Language::getDefaultLocaleCode())
                ->value('reference_id');

            if (! $originalId) {
                continue;
            }

            $originalItem->delete();

            $item->delete();
        }
    }
}