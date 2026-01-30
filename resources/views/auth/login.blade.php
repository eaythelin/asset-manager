@extends('layouts.authlayout')
@section('content')

<x-toast-success />
<x-session-error />

<x-auth-card title="Login">
  <form method = "POST" action = "{{ route('loginUser') }}">
    <div class = "flex flex-col gap-3">
      @csrf
      {{-- Email field --}}
      <label for = "email" class = "font-medium">Email Address</label>
      <div class = "relative">
        <x-heroicon-o-envelope class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 opacity-50 z-10 pointer-events-none" />
        <input type = "email" name = "email" id = "email" class = "input border-2 border-gray-400 pl-10 w-full" placeholder="Type your email">
      </div>
      @error("email")
        <p class = "text-red-500 text-xs">{{ $message }}</p>
      @enderror
      {{-- Password Field --}}
      <label for = "password" class = "font-medium">Password</label>
      <div class = "relative">
        <x-heroicon-o-key class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 opacity-50 z-10 pointer-events-none" />
        <input type = "password" id = "password" name = "password" class = "input border-2 border-gray-400 pl-10 w-full" placeholder="Type your password">
      </div>
      @error("password")
        <p class = "text-red-500 text-xs">{{ $message }}</p>
      @enderror
      <div class = "card-actions justify-center">
        <x-buttons class="w-full" type="submit">Login</x-buttons>
        <a href = "{{ route("password.request") }}" class = "text-sm hover:underline text-primary">Forgot Password? Click here</a>
      </div>
    </div>
  </form>
</x-auth-card>
@endsection