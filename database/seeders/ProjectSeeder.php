<?php

namespace Database\Seeders;

use Botble\Language\Models\LanguageMeta;
use Botble\RealEstate\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Language;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $items = LanguageMeta::where('reference_type', Project::class)
            ->where('lang_meta_code', '!=', Language::getDefaultLocaleCode())
            ->get();

        foreach ($items as $item) {
            $originalItem = Project::find($item->reference_id);

            if (! $originalItem) {
                continue;
            }

            $originalItem->delete();

            $item->delete();
        }
    }
}