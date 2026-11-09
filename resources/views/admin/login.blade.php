@extends('layouts.app')
@section('content')
<div class="my-12"><p class="text-xs font-extrabold uppercase tracking-[.15em] text-rose-400">Store manager</p><h1 class="mt-1 text-4xl font-extrabold tracking-tighter">Admin sign in</h1></div>
<form class="max-w-md rounded-2xl border border-orange-100 bg-white p-7" method="post" action="{{ route('admin.authenticate') }}">@csrf<label class="mb-5 block font-bold">Email<input class="mt-2 block w-full rounded-lg border border-orange-100 bg-orange-50 px-4 py-3" type="email" name="email" value="{{ old('email') }}" required autofocus></label><label class="mb-5 block font-bold">Password<input class="mt-2 block w-full rounded-lg border border-orange-100 bg-orange-50 px-4 py-3" type="password" name="password" required></label><button class="rounded-full bg-slate-800 px-5 py-3 font-bold text-white" type="submit">Enter dashboard</button></form>
@endsection
