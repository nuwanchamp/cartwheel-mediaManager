<?php

namespace Sentech\MediaManager\Tests;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseCase;
use Sentech\MediaManager\MediaManagerServiceProvider;

abstract class TestCase extends  BaseCase
{
    use RefreshDatabase;
    public  function  setUp():void{
        parent::setUp();
    }
    protected function getPackageProviders($app): array
    {
        return [
            MediaManagerServiceProvider::class
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [ ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testdb');
        $app['config']->set('database.connections.testdb', [
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
    }


}