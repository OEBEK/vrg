@extends('layouts.app')

@section('content')
<div id="app">
    <edit-todo todo-id="{{ $todo->id }}"></edit-todo>
</div>
@endsection

@push('scripts')
<script src="{{ mix('js/app.js') }}"></script>
@endpush