@extends('layouts.admin')

@section('content')
<div class="max-w-3xl rounded-3xl bg-white p-8 shadow-sm border border-slate-200">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Modifier la filière</h1>
        <p class="text-sm text-slate-500">Mettez à jour le nom ou la description de la filière.</p>
    </div>

    <form action="{{ route('admin.filieres.update', $filiere) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-slate-700">Nom de la filière</label>
            <input type="text" name="libelle" value="{{ old('libelle', $filiere->libelle) }}"
                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
            @error('nom')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" rows="5"
                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('description', $filiere->description) }}</textarea>
            @error('description')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('admin.filieres.index') }}"
                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                Retour
            </a>
            <button type="submit"
                class="inline-flex items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                Mettre à jour la filière
            </button>
        </div>
    </form>
</div>
@endsection