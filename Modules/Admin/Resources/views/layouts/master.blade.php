@extends('layouts.master')

@section('css')
    <link href="{{ asset('admin/css/admin.css') }}" rel="stylesheet">
    @yield('page_css')
@endsection

@section('js')
    <script src="{{ asset('admin/js/admin.js') }}"></script>
    @yield('page_js')
@endsection

@section('content')
    @include("admin::layouts.header")
    @yield("page_content")
@stop
