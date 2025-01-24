@extends('layouts.guest')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary/5 via-purple-100 to-primary/5">
        <div class="w-full max-w-md">
            <livewire:auth.login-form />
        </div>
    </div>
@endsection
