<?php

namespace Database\Seeders;

use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Setting\Models\Setting;
use Botble\Slug\Models\Slug;
use SlugHelper;

class SettingSeeder extends BaseSeeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'media_random_hash',
                'value' => md5(time()),
            ],
            [
                'key' => SlugHelper::getPermalinkSettingKey(Post::class),
                'value' => 'news',
            ],
            [
                'key' => SlugHelper::getPermalinkSettingKey(Category::class),
                'value' => 'news',
            ],
            [
                'key' => 'real_estate_display_views_count_in_detail_page',
                'value' => 1,
            ],
            [
                'key' => 'payment_cod_status',
                'value' => 1,
            ],
            [
                'key' => 'payment_cod_description',
                'value' => 'Please pay money directly to the postman, if you choose cash on delivery method (COD).',
            ],
            [
                'key' => 'payment_bank_transfer_status',
                'value' => 1,
            ],
            [
                'key' => 'payment_bank_transfer_description',
                'value' => 'Please send money to our bank account: ACB - 9913 3821 19.',
            ],
            [
                'key' => 'payment_stripe_payment_type',
                'value' => 'stripe_checkout',
            ],
        ];

        Setting::whereIn('key', collect($settings)->pluck('key')->all())->delete();

        if (BaseModel::determineIfUsingUuidsForId()) {
            $settings = array_map(function ($item) {
                $item['id'] = BaseModel::newUniqueId();

                return $item;
            }, $settings);
        }

        Setting::insert($settings);

        Slug::where('reference_type', Post::class)->update(['prefix' => 'news']);
        Slug::where('reference_type', Category::class)->update(['prefix' => 'news']);
    }
}
