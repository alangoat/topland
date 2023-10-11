<?php

namespace Botble\Base\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class BeforeUpdateContentEvent extends Event
{
    use SerializesModels;

    public function __construct(public Request $request, public false|Model|null $data)
    {
    }
}
