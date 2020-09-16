@extends('layouts.master')

@section('css')
    <link href="{{ asset('academic/css/academic.css') }}" rel="stylesheet">
    @yield('page_css')
@endsection

@section('js')
    <script src="{{ asset('academic/js/academic.js') }}"></script>
    @yield('page_js')
@endsection

@section('content')
    @include("academic::layouts.header")
    @yield("page_content")
@stop
