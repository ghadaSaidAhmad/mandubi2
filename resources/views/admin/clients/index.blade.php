@extends('layout.app')

@section('pageTitle')
    العملاء
@endsection
@section('content')
    <div class="row text-center">
    <div class="col-md-6 text-left">
         @include('common.forms.search', [
            'searchLabel' => 'بحث',
            'route' => route('users.search'),
            'types' => $searchTypes
         ])
        </div>
        <div class="col-md-6">
            <button class="btn btn-success" data-toggle="modal" data-target="#add-new">مستخدم جديد</button>
        </div>
    </div>
    <br>
    <div class="row" id="main-table">
        {!! $table !!}
    </div>

    @include('admin.clients.add')
    <section id="edit"></section>                


@endsection

@section('after_js')
    @include('admin.clients.ajax')
@endsection

{{-- Styles Section --}}
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection


{{-- Scripts Section --}}
@section('scripts')
    {{-- vendors --}}
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

    {{-- page scripts --}}
    <script src="{{ asset('js/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
    
@endsection
