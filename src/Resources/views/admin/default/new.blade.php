@extends('admin::layouts.content')
@push('css')
    <link type="text/css" rel="stylesheet" href="{{ asset('media-manager/css/admin.media.css') }}">

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
@endpush
@section('content')
    <div class="page-header">
        <div class="page title">
            <h1>Upload New Media</h1>
        </div>
    </div>
    <div class="upload-area">
        <form action="{{route('media.admin.store')}}" class="dropzone" id="my-dropzone" enctype="multipart/form-data" method="post">
            @csrf
        </form>
        <p class="mt-1 mb-0 text-muted">You are using the multi-file uploader.</p>
        <p class="text-muted">Maximum upload file size: 2 MB</p>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{asset('media-manager/js/app.js')}}"/></script>
@endpush