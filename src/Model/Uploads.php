<?php

namespace Sentech\MediaManager\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Uploads extends Model implements HasMedia
{
    use InteractsWithMedia;
}