<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;
use Botble\RealEstate\Enums\ProjectStatusEnum;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use RvMedia;

class Project extends BaseModel
{
    protected $table = 're_projects';

    protected $fillable = [
        'name',
        'description',
        'content',
        'location',
        'images',
        'status',
        'is_featured',
        'investor_id',
        'number_block',
        'number_floor',
        'number_flat',
        'date_finish',
        'date_sell',
        'price_from',
        'price_to',
        'currency_id',
        'city_id',
        'state_id',
        'country_id',
        'author_id',
        'author_type',
        'category_id',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'status' => ProjectStatusEnum::class,
        'date_finish' => 'datetime',
        'date_sell' => 'datetime',
        'price_from' => 'float',
        'price_to' => 'float',
        'number_block' => 'int',
        'number_float' => 'int',
        'number_flat' => 'int',
        'views' => 'int',
        'is_featured' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Project $project) {
            $project->categories()->detach();
            $project->customFields()->delete();
            $project->reviews()->delete();
        });
    }

    public function author(): MorphTo
    {
        return $this->morphTo();
    }

    public function property(): HasMany
    {
        return $this->hasMany(Property::class, 'project_id');
    }

    public function getImagesAttribute($value): array
    {
        try {
            if ($value === '[null]') {
                return [];
            }

            $images = json_decode((string)$value, true);

            if (is_array($images)) {
                $images = array_filter($images);
            }

            return $images ?: [];
        } catch (Exception) {
            return [];
        }
    }

    public function getImageAttribute(): ?string
    {
        return Arr::first($this->images) ?? null;
    }

    public function investor(): BelongsTo
    {
        return $this->belongsTo(Investor::class)->withDefault();
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 're_project_features', 'project_id', 'feature_id');
    }

    public function facilities(): BelongsToMany
    {
        return $this->morphToMany(Facility::class, 'reference', 're_facilities_distances')->withPivot('distance');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->withDefault();
    }

    public function getAddressAttribute(): ?string
    {
        return $this->location;
    }

    public function getCategoryAttribute(): Category
    {
        return $this->categories->first() ?: new Category();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 're_project_categories');
    }

    public function getCategoryNameAttribute(): ?string
    {
        return $this->category->name;
    }

    public function getImageThumbAttribute(): ?string
    {
        return $this->image ? RvMedia::getImageUrl($this->image, 'thumb', false, RvMedia::getDefaultImage()) : null;
    }

    public function getImageSmallAttribute(): ?string
    {
        return $this->image ? RvMedia::getImageUrl($this->image, 'small', false, RvMedia::getDefaultImage()) : null;
    }

    public function getMapIconAttribute(): ?string
    {
        return $this->name;
    }

    public function getCityNameAttribute(): string
    {
        return $this->city->name . ', ' . $this->city->state->name;
    }

    public function customFields(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'reference', 'reference_type', 'reference_id')->with('customField.options');
    }

    protected function customFieldsArray(): Attribute
    {
        return Attribute::make(
            get: function () {
                return CustomFieldValue::getCustomFieldValuesArray($this);
            },
        );
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
