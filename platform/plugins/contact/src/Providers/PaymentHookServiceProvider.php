<?php

namespace Botble\Contact\Providers;

use Assets;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Repositories\Interfaces\PaymentInterface;
use Illuminate\Support\Facades\DB;
use Botble\Shortcode\Compilers\Shortcode;
use Html;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Theme;

class PaymentHookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(BASE_FILTER_TOP_HEADER_LAYOUT, [$this, 'registerTopHeaderNotification'], 120);
        add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getUnreadCount'], 120, 2);
        add_filter(BASE_FILTER_MENU_ITEMS_COUNT, [$this, 'getMenuItemCount'], 120);

        if (function_exists('add_shortcode')) {
            add_shortcode(
                'contact-form',
                trans('plugins/contact::contact.shortcode_name'),
                trans('plugins/contact::contact.shortcode_description'),
                [$this, 'form']
            );

            shortcode()
                ->setAdminConfig('contact-form', view('plugins/contact::partials.short-code-admin-config')->render());
        }

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSettings'], 93);
    }

    public function registerTopHeaderNotification(?string $options): ?string
    {
        if (Auth::user()->hasPermission('payments.edit')) {
            $payments = $this->app[PaymentInterface::class]
                ->advancedGet([
                    'condition' => [
                        'status' => PaymentStatusEnum::PENDING,
                    ],
                    'paginate' => [
                        'per_page' => 10,
                        'current_paged' => 1,
                    ],
                    'select' => ['id', 'amount', 'charge_id', 'payment_channel', 'currency','created_at'],
                    'order_by' => ['created_at' => 'DESC'],
                ]);

            if ($payments->count() == 0) {
                return $options;
            }

            return $options . view('plugins/payment::partials.notification', compact('payments'))->render();
        }

        return $options;
    }

    public function getUnreadCount(string|null|int $number, string $menuId): int|string|null
    {
        if ($menuId !== 'cms-plugins-payments') {
            return $number;
        }

        $attributes = [
            'class' => 'badge badge-success menu-item-count unread-payments',
            'style' => 'display: none;',
        ];

        return Html::tag('span', '', $attributes)->toHtml();
    }

    public function getMenuItemCount(array $data = []): array
    {
        if (Auth::user()->hasPermission('payments.index')) {
            $data[] = [
                'key' => 'unread-payments',
                'value' => DB::table('payments')->where('status', 'pending')->count(),
            ];
        }

        return $data;
    }

    public function form(Shortcode $shortcode): string
    {
        $view = apply_filters(CONTACT_FORM_TEMPLATE_VIEW, 'plugins/contact::forms.contact');

        if (defined('THEME_OPTIONS_MODULE_SCREEN_NAME')) {
            $this->app->booted(function () {
                Theme::asset()
                    ->usePath(false)
                    ->add('contact-css', asset('vendor/core/plugins/contact/css/contact-public.css'), [], [], '1.0.0');

                Theme::asset()
                    ->container('footer')
                    ->usePath(false)
                    ->add(
                        'contact-public-js',
                        asset('vendor/core/plugins/contact/js/contact-public.js'),
                        ['jquery'],
                        [],
                        '1.0.0'
                    );
            });
        }

        if ($shortcode->view && view()->exists($shortcode->view)) {
            $view = $shortcode->view;
        }

        return view($view, compact('shortcode'))->render();
    }

    public function addSettings(?string $data = null): string
    {
        Assets::addStylesDirectly('vendor/core/core/base/libraries/tagify/tagify.css')
            ->addScriptsDirectly([
                'vendor/core/core/base/libraries/tagify/tagify.js',
                'vendor/core/core/base/js/tags.js',
            ]);

        return $data . view('plugins/contact::settings')->render();
    }
}