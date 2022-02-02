<?php

namespace Sentech\MediaManager\Http\Controllers;


use Sentech\MediaManager\Facades\MediaManager;


class MediaController extends Controller
{

    public function index()
    {
        return view('media::admin.index', ["media" => MediaManager::getAllMedia()]);
    }

    public function create()
    {
        return view("media::admin.new");
    }

    public function store()
    {
        $media = request()->file('media');
        $this->validate(request(), [
            "media" => 'required'
        ]);
        MediaManager::add(request()->file('media'), 'products');
        return redirect()->route('media.admin.index');
    }

    public function update($id)
    {
        $this->validate(request(), [
            // At the moment there is no validation
        ]);
        $customProperties = [
            "title" => request()->get('title'),
            "altText" => request()->get('altText'),
            "caption" => request()->get('caption'),
            "description" => request()->get('description'),
        ];
        $media = MediaManager::addProperties($id, $customProperties);

        return response()->json(['success' => request()->all(), 'media' => $media]);
    }

    public function destroy($id)
    {
        MediaManager::delete($id);
        return response()->json(['success' => "Data has been deleted"]);
    }
}