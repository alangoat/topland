<?php

// Custom routes
Route::group(['namespace' => 'Theme\FlexHome\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get('wishlist', 'FlexHomeController@getWishlist')->name('public.wishlist');
        Route::get('ajax/cities', 'FlexHomeController@ajaxGetCities')->name('public.ajax.cities');
        Route::get('ajax/properties', 'FlexHomeController@ajaxGetProperties')->name('public.ajax.properties');
        Route::get('ajax/posts', 'FlexHomeController@ajaxGetPosts')->name('public.ajax.posts');
        Route::get('ajax/properties/map', 'FlexHomeController@ajaxGetPropertiesForMap')->name('public.ajax.properties.map');
        Route::get('ajax/agents/featured', 'FlexHomeController@ajaxGetFeaturedAgents')->name('public.ajax.featured-agents');
        Route::get('ajax/projects-filter', 'FlexHomeController@ajaxGetProjectsFilter')->name('public.ajax.projects-filter');
    });
});

Theme::routes();
