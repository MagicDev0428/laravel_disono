<!DOCTYPE html>
<html lang="en" {{html_app_cache()}}>
{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 *
 * Web styles and master view
--}}

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="_token" content="{{csrf_token()}}">
    @if(auth()->check())
        <meta name="_authenticated_id" content="{{auth()->user()->id}}">
    @endif

    {{-- SEO variables --}}
    <title>{{ app_header('title') }} @yield('title')</title>
    <meta name="description" content="{{ (isset($page_description)) ? $page_description : app_header('description') }}">
    <meta name="keywords" content="{{ (isset($page_keywords)) ? $page_keywords : app_header('keywords') }}">
    <meta name="author" content="{{ (isset($page_author)) ? $page_author : app_header('author') }}">

    {{-- ICON --}}
    <link rel="icon" type="image/png" href="{{url('assets/img/placeholder/favicon.png')}}"/>

    {{-- Styles --}}
    @include('vendor.css')

    <style id="loaderStyles">
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: table
        }

        #loaderContent {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        #loaderContent img {
            opacity: 0.5;
        }
    </style>
</head>

<body>
{{-- loader --}}
<div id="loaderContent">
    <img src="{{asset('assets/img/loaders/rolling.svg')}}" alt="Loading...">
</div>

{{-- main application content --}}
<main id="WBMainApp" style="display: none;">
    @include(current_theme() . 'layout.header')

    <div class="page-content">
        @yield('content')
    </div>

    @include(current_theme() . 'layout.footer')

    {{-- modals --}}
    @include('modals.include')

    {{-- javascript dynamic container --}}
    <div id="dynamic_container"></div>
</main>

{{-- Scripts --}}
@if(env('APP_DEBUG'))
    @include('vendor.loaders', ['scripts' => [
        'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_API_KEY') . '&libraries=places',

        'assets/js/vendor.js',
        'assets/js/lib/helper.js',
        'assets/js/lib/socket.js',
        'assets/js/main.js',
        'assets/js/app.js'
    ], 'after_load' => true])
@else
    @include('vendor.loaders', ['scripts' => [
        'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_API_KEY') . '&libraries=places',

        'assets/js/vendor.js',
        'assets/js/app.js'
    ], 'after_load' => true])
@endif

@yield('javascript')
@include('vendor.loaders', ['js_run' => true])
</body>
</html>