<?php

namespace Sentech\MediaManager\Tests\Unit;

use Sentech\MediaManager\Manger\MediaManager;
use Sentech\MediaManager\Tests\TestCase;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class MediaManagerTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public  function mediaManager_handler_is_spatie_media_instance(){
        $mediaManger = new MediaManager();
        self::assertInstanceOf(Media::class, $mediaManger->handler());
    }
    /**
     * @test
     */
    public function add_media_to_collection(){
        $mediaManager = new MediaManager();
        $testResourcePath = dirname(__DIR__).'/testResource/image01.jpg';
        try {
           $mediaManager->add($testResourcePath, 'products');
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            dd($e);
        }
        self::assertCount(1, $mediaManager->handler()::all());

    }
    /**
     * @test
     */
    public function add_custom_properties(){
        $mediaManager = new MediaManager();
        $testResourcePath = dirname(__DIR__).'/testResource/image01.jpg';
        try {
            $mediaManager->add($testResourcePath, 'products');
            $mediaManager->addProperties(1, ['altText' => 'My Alt Text' ]);
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            dd($e);
        }
        self::assertEquals('My Alt Text', Media::first()->getCustomProperty('altText') );
    }
    /**
     * @test
     */
    public function update_custom_property(){
        $mediaManager = new MediaManager();
        $testResourcePath = dirname(__DIR__).'/testResource/image01.jpg';
        try {
            $mediaManager->add($testResourcePath, 'products');
            $mediaManager->addProperties(1, ['altText' => 'My Alt Text' ]);
            $mediaManager->updateProperties(1, ['altText' => 'My Alt Text Updated' ]);
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            dd($e);
        }
        self::assertEquals('My Alt Text Updated', Media::first()->getCustomProperty('altText') );
    }

    /**
     * @test
     */
    public function delete_media(){
        $mediaManager = new MediaManager();
        $testResourcePath = dirname(__DIR__).'/testResource/image01.jpg';
        try {
            $mediaManager->add($testResourcePath, 'products');
           $mediaManager->delete(1);
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            dd($e);
        }
        self::assertCount(0, Media::all());
    }
    /**
     * @test
     */
    public function get_all_media(){
        $mediaManager = new MediaManager();
        $testResourcePath = dirname(__DIR__).'/testResource/image01.jpg';
        try {
            $mediaManager->add($testResourcePath, 'products');
            $mediaManager->add($testResourcePath, 'products');
            $mediaManager->add($testResourcePath, 'products');
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            dd($e);
        }
        self::assertCount(3, $mediaManager->getAllMedia());
    }
    /**
     * @test
     */
    public function check_responsive_image_generation(){
        $mediaManager = new MediaManager();
        $testResourcePath = dirname(__DIR__).'/testResource/image01.jpg';
        try {
            $mediaManager->add($testResourcePath, 'products');
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            dd($e);
        }
        self::assertNotEmpty($mediaManager->getAllMedia()->first()->responsive_images);
    }
}