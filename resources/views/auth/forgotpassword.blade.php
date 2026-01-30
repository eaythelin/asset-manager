@extends('layouts.authlayout')
@section('content')
<x-auth-card title="Forgot Password">
  <form method = "POST" action = "{{ route("password.email") }}">
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
      <div class = "card-actions justify-center">
        @if(session(key:"status"))
          <p class = "text-green-500 text-xs">{{ session("status") }}</p>
        @endif
        <x-buttons class="w-full" type="submit">Send Reset Email Link</x-buttons>
        <a href = "{{ route("login") }}" class = "text-sm hover:underline text-primary">Back to Login</a>
      </div>
    </div>
  </form>
</x-auth-card>
@endsection