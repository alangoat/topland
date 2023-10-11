<?php

namespace Botble\Theme\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Theme\Commands\ThemeActivateCommand;
use Botble\Theme\Commands\ThemeAssetsPublishCommand;
use Botble\Theme\Commands\ThemeAssetsRemoveCommand;
use Botble\Theme\Commands\ThemeOptionCheckMissingCommand;
use Botble\Theme\Commands\ThemeRemoveCommand;
use Botble\Theme\Commands\ThemeRenameCommand;
use Botble\Theme\Contracts\Theme as ThemeContract;
use Botble\Theme\Theme;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('packages/theme')
            ->loadHelpers();

        $this->app->bind(ThemeContract::class, Theme::class);

        $this->commands([
            ThemeActivateCommand::class,
            ThemeRemoveCommand::class,
            ThemeAssetsPublishCommand::class,
            ThemeOptionCheckMissingCommand::class,
            ThemeAssetsRemoveCommand::class,
            ThemeRenameCommand::class,
        ]);
    }

    public function boot(): void
    {
        $this
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->publishAssets();

        $this->app['events']->listen(RouteMatched::class, function () {

            admin_bar()
                ->registerLink(trans('packages/theme::theme.name'), route('theme.index'), 'appearance', 'theme.index')
                ->registerLink(trans('packages/theme::theme.theme_options'), route('theme.options'), 'appearance', 'theme.options');
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });

        $this->app->register(ThemeManagementServiceProvider::class);
    }
}
