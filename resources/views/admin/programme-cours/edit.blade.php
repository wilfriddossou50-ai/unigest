@extends('layouts.admin')

@section('title', 'Modifier le Cours')

@section('content')
<div class="p-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Modifier le cours</h1>
            <p class="text-slate-600">{{ $programme->matiere?->libelle ?? 'Matière inconnue' }}</p>
        </div>

        @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('admin.programme-cours.update', $programme) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.programme-cours._form', ['programme' => $programme])
        </form>
    </div>
</div>
@endsection