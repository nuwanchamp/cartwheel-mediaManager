<?php
namespace Sentech\MediaManager\Facades;
use Illuminate\Support\Facades\Facade;

class MediaManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'test';
    }

}