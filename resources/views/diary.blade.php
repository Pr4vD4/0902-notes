@extends('layouts.app')

@section('content')
    <div class="flex h-full">
        <livewire:categories.category-menu />
        <livewire:notes.note-list />
    </div>
@endsection
