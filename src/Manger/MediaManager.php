<?php

namespace Sentech\MediaManager\Manger;

use Sentech\MediaManager\Model\Uploads;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaManager
{
    private Media $mediaHandler;
    private Uploads $uploader;
    public function __construct()
    {
        $this->mediaHandler = new Media();
        $this->uploader = new Uploads();
    }
    public function handler(): Media
    {
        return $this->mediaHandler;
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function add($path, $collection):Media
    {
        return Uploads::create()->addMedia($path)->preservingOriginal()->withResponsiveImages()->toMediaCollection($collection);
    }
    public function addProperties($id, array $extra = []):Media{
        // Add media with additional details
        $mediaItem = Media::find($id);
        foreach ($extra as $customProperty => $customValue){
            $mediaItem->setCustomProperty($customProperty, $customValue);
        }
        $mediaItem->save();
        return $mediaItem;
    }
    public function updateProperties($id, $extra=[]){
        $mediaItem = Media::find($id);
        foreach ($extra as $customProperty => $customValue){
            $mediaItem->setCustomProperty($customProperty, $customValue);
        }
        $mediaItem->save();
        return $mediaItem;
    }
    public function delete($id){
        $mediaItem = Media::find($id);
        Uploads::find($mediaItem->model_id)->delete(); // Delete Media along with the attached model
    }
    public function getAllMedia(){
        return Media::all();
    }

}