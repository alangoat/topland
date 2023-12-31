<?php

namespace Botble\Location\Providers;

use Botble\Base\Models\BaseModel;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Location\Facades\LocationFacade;
use Botble\Location\Models\City;
use Botble\Location\Repositories\Caches\CityCacheDecorator;
use Botble\Location\Repositories\Eloquent\CityRepository;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Location\Models\Country;
use Botble\Location\Repositories\Caches\CountryCacheDecorator;
use Botble\Location\Repositories\Eloquent\CountryRepository;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Models\State;
use Botble\Location\Repositories\Caches\StateCacheDecorator;
use Botble\Location\Repositories\Eloquent\StateRepository;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Location;
use MacroableModels;

class LocationServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(CountryInterface::class, function () {
            return new CountryCacheDecorator(new CountryRepository(new Country()));
        });

        $this->app->bind(StateInterface::class, function () {
            return new StateCacheDecorator(new StateRepository(new State()));
        });

        $this->app->bind(CityInterface::class, function () {
            return new CityCacheDecorator(new CityRepository(new City()));
        });

        AliasLoader::getInstance()->alias('Location', LocationFacade::class);
    }

    public function boot(): void
    {
        $this->setNamespace('plugins/location')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->publishAssets();

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Country::class, [
                'name',
                'nationality',
            ]);

            LanguageAdvancedManager::registerModule(State::class, [
                'name',
                'abbreviation',
            ]);

            LanguageAdvancedManager::registerModule(City::class, [
                'name',
            ]);
        }

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-plugins-location',
                    'priority' => 900,
                    'parent_id' => null,
                    'name' => 'plugins/location::location.name',
                    'icon' => 'fas fa-globe',
                    'url' => null,
                    'permissions' => ['country.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-state',
                    'priority' => 1,
                    'parent_id' => 'cms-plugins-location',
                    'name' => 'plugins/location::state.name',
                    'icon' => null,
                    'url' => route('state.index'),
                    'permissions' => ['state.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-city',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-location',
                    'name' => 'plugins/location::city.name',
                    'icon' => null,
                    'url' => route('city.index'),
                    'permissions' => ['city.index'],
                ]);
        });

        $this->app->booted(function () {
            Blueprint::macro('location', function ($item = null, $keys = []) {
                if ($item) {
                    if (class_exists($item) && Location::isSupported($item)) {
                        $data = Location::getSupported($item);
                        $model = new $item();
                        $table = $model->getTable();
                        $connection = $model->getConnectionName();
                        $keys = [];
                        foreach ($data as $key => $column) {
                            if (! Schema::connection($connection)->hasColumn($table, $column)) {
                                $keys[$key] = $column;
                            }
                        }
                    }
                } else {
                    $keys = array_filter(array_merge([
                        'country' => 'country_id',
                        'state' => 'state_id',
                        'city' => 'city_id',
                    ], $keys));
                }

                /**
                 * @var Blueprint $this
                 */
                if ($columnName = Arr::get($keys, 'country')) {
                    $this->foreignId($columnName)->default(1)->nullable();
                }

                if ($columnName = Arr::get($keys, 'state')) {
                    $this->foreignId($columnName)->nullable();
                }

                if ($columnName = Arr::get($keys, 'city')) {
                    $this->foreignId($columnName)->nullable();
                }

                return true;
            });

            foreach (Location::getSupported() as $item => $keys) {
                if (! class_exists($item)) {
                    continue;
                }

                if ($foreignKey = Arr::get($keys, 'country')) {
                    /**
                     * @var BaseModel $item
                     */
                    $item::resolveRelationUsing('country', function ($model) use ($foreignKey) {
                        return $model->belongsTo(Country::class, $foreignKey)->withDefault();
                    });

                    MacroableModels::addMacro($item, 'getCountryNameAttribute', function () {
                        /**
                         * @var BaseModel $this
                         */
                        return $this->country->name;
                    });
                }

                if ($foreignKey = Arr::get($keys, 'state')) {
                    /**
                     * @var BaseModel $item
                     */
                    $item::resolveRelationUsing('state', function ($model) use ($foreignKey) {
                        return $model->belongsTo(State::class, $foreignKey)->withDefault();
                    });

                    MacroableModels::addMacro($item, 'getStateNameAttribute', function () {
                        /**
                         * @var BaseModel $this
                         */
                        return $this->state->name;
                    });
                }

                if ($foreignKey = Arr::get($keys, 'city')) {
                    /**
                     * @var BaseModel $item
                     */
                    $item::resolveRelationUsing('city', function ($model) use ($foreignKey) {
                        return $model->belongsTo(City::class, $foreignKey)->withDefault();
                    });

                    MacroableModels::addMacro($item, 'getCityNameAttribute', function () {
                        /**
                         * @var BaseModel $this
                         */
                        return $this->city->name;
                    });
                }

                MacroableModels::addMacro($item, 'getFullAddressAttribute', function () {
                    /**
                     * @var BaseModel $this
                     */
                    return ($this->address ? $this->address . ', ' : null) .
                        ($this->city_name ? $this->city_name . ', ' : null) .
                        ($this->state_name ? $this->state_name . ', ' : null) .
                        $this->country_name;
                });
            }
        });

        $this->app->register(CommandServiceProvider::class);
        $this->app->register(HookServiceProvider::class);
    }
}
