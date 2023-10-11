<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomFieldOption extends BaseModel
{
    protected $table = 're_custom_field_options';

    protected $fillable = [
        'custom_field_id',
        'label',
        'value',
        'order',
    ];

    protected $casts = [
        'order' => 'int',
    ];

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }
}
