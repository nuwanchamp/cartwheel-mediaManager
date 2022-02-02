<?php

namespace Sentech\MediaManager;


use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Sentech\MediaManager\Manger\MediaManager;
use Spatie\MediaLibrary\Conversions\Commands\RegenerateCommand;
use Spatie\MediaLibrary\MediaCollections\Commands\CleanCommand;
use Spatie\MediaLibrary\MediaCollections\Commands\ClearCommand;
use Spatie\MediaLibrary\MediaCollections\Filesystem;
use Spatie\MediaLibrary\MediaCollections\MediaRepository;
use Spatie\MediaLibrary\MediaCollections\Models\Observers\MediaObserver;
use Spatie\MediaLibrary\ResponsiveImages\TinyPlaceholderGenerator\TinyPlaceholderGenerator;
use Spatie\MediaLibrary\ResponsiveImages\WidthCalculator\WidthCalculator;

class MediaManagerServiceProvider extends ServiceProvider
{
    public function boot(){
        $this->registerPublishers();
        $this->loaders();
        $mediaClass = config('media-library.media_model');
        $mediaClass::observe(new MediaObserver());
        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('media::admin.layouts.styles');
        });
    }
    public function register()
    {
        parent::register();
        $this->configs();
        $this->app->singleton(MediaRepository::class, function () {
            $mediaClass = config('media-library.media_model');
            return new MediaRepository(new $mediaClass());
        });
        $this->app->bind('test',function() {
            return new MediaManager;
        });

        $this->registerCommands();

    }
    public function registerPublishers(){
        $this->publishes([
            __DIR__.'/Config/media-library.php' => config_path('media-library.php'), // spatie config extended
        ], 'config');

        if (! class_exists('CreateMediaTable')) {
            $this->publishes([
                __DIR__.'/Database/Stubs/create_media_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_media_table.php'), // extended from spatie
            ], 'migrations');
        }
        if (! class_exists('CreateUploadsTable')) {
            $this->publishes([
                __DIR__ .'/Database/Stubs/create_uploads_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_media_table.php'),
            ], 'migrations');
        }
    }
    public function registerCommands(){
        $this->app->bind(Filesystem::class, Filesystem::class);
        $this->app->bind(WidthCalculator::class, config('media-library.responsive_images.width_calculator'));
        $this->app->bind(TinyPlaceholderGenerator::class, config('media-library.responsive_images.tiny_placeholder_generator'));

        $this->app->bind('command.media-library:regenerate', RegenerateCommand::class);
        $this->app->bind('command.media-library:clear', ClearCommand::class);
        $this->app->bind('command.media-library:clean', CleanCommand::class);

        $this->commands([
            'command.media-library:regenerate',
            'command.media-library:clear',
            'command.media-library:clean',
        ]);
    }

    public function configs(){
        // spatie config file has been extended by our config;
        $this->mergeConfigFrom(__DIR__.'/Config/media-library.php', 'media-library');
        $this->mergeConfigFrom(__DIR__.'/Config/admin-menu.php', 'menu.admin');
    }

    public function loaders(){
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'media');
        $this->loadViewsFrom($this->getSpatiePath().'/resources/views', 'media-library'); // necessary for testing
    }

    public function getSpatiePath(){
        return dirname(__DIR__).'/vendor/spatie/laravel-medialibrary';
    }

}