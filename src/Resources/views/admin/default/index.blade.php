@extends('admin::layouts.content')
@push('css')
    <link type="text/css" rel="stylesheet" href="{{ asset('media-manager/css/admin.media.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('media-manager/css/admin.media-modal-view.css') }}">

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
@endpush
@section('content')
    <div class="page-header">
        <div class="page title">
            <h1>Media Library</h1>
        </div>
        <div class="media-listing mt-3" id="media-container">
            <media-collection v-bind:medias='@json(collect($media))'></media-collection>
            <modal-view  v-bind:ast='"{{asset('media-manager/images/')}}"'></modal-view>
        </div>
    </div>
@stop
@push('scripts')
    <script type="text/javascript" src="{{asset('media-manager/js/app.js')}}"/></script>
@endpush