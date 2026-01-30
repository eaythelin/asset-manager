@extends('layouts.authlayout')
@section('content')
<x-auth-card title="Reset Password">
  <form method = "POST" action = "{{ route("password.update") }}">
    <div class = "flex flex-col gap-3">
      @csrf

      {{-- Hidden input field for the token because we also need the reset token! --}}
      <input name = "token" value = "{{ $token }}" type = "hidden">

      {{-- Email field --}}
      <label for = "email" class = "font-medium">Email Address</label>
      <div class = "relative">
        <x-heroicon-o-envelope class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 opacity-50 z-10 pointer-events-none" />
        <input type = "email" name = "email" id = "email" class = "input border-2 border-base-500 pl-10 w-full" value = "{{ old('email', $email) }}" readonly>
      </div>
      {{-- New Password Field --}}
      <label for = "password" class = "font-medium">New Password</label>
      <div class = "relative">
        <x-heroicon-o-key class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 opacity-50 z-10 pointer-events-none" />
        <input type = "password" id = "password" name = "password" class = "input border-2 border-gray-400 pl-10 w-full" placeholder="New Password">
      </div>
      {{-- Confirm Password Field --}}
      <label for = "password" class = "font-medium">Confirm Password</label>
      <div class = "relative">
        <x-heroicon-o-key class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 opacity-50 z-10 pointer-events-none" />
        <input type = "password" id = "password" name = "password_confirmation" class = "input border-2 border-gray-400 pl-10 w-full" placeholder="Confirm Password">
      </div>
      @error("password")
        <p class = "text-red-500 text-xs">{{ $message }}</p>
      @enderror
      <div class = "card-actions justify-center pt-2">
        <x-buttons class="w-full" type="submit">Confirm</x-buttons>
      </div>
    </div>
  </form>
</x-auth-card>
@endsection