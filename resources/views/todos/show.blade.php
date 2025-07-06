@extends('layouts.app')
 
@section('content')
<div id="app">
    <show-todo todo-id="{{ $todo->id }}"></show-todo>
</div>
@endsection