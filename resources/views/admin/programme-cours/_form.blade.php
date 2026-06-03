@php
$isEdit = isset($programme);
@endphp

<div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Jour et Heure</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="jour_semaine" class="block text-sm font-medium text-slate-700 mb-1">Jour</label>
            <select name="jour_semaine" id="jour_semaine" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $jour)
                <option value="{{ $jour }}" {{ old('jour_semaine', $programme->jour_semaine ?? '') === $jour ? 'selected' : '' }}>{{ $jour }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="date_programme" class="block text-sm font-medium text-slate-700 mb-1">Date</label>
            <input type="date" name="date_programme" id="date_programme" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('date_programme', isset($programme) ? optional($programme->date_programme)->format('Y-m-d') : request('date') ?? '') }}" required>
        </div>
        <div>
            <label for="creneau_id" class="block text-sm font-medium text-slate-700 mb-1">Créneau</label>
            <select name="creneau_id" id="creneau_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                <option value="">-- Choisir --</option>
                @foreach($creneaux as $creneau)
                <option value="{{ $creneau->id }}" {{ old('creneau_id', $programme->creneau_id ?? request('creneau')) == $creneau->id ? 'selected' : '' }}>{{ is_string($creneau->heure_debut) ? substr($creneau->heure_debut, 0,5) : $creneau->heure_debut->format('H:i') }} - {{ is_string($creneau->heure_fin) ? substr($creneau->heure_fin, 0,5) : $creneau->heure_fin->format('H:i') }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm mt-4">
    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Informations Académiques</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="filiere_id" class="block text-sm font-medium text-slate-700 mb-1">Filière</label>
            <select name="filiere_id" id="filiere_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">-- Choisir --</option>
                @foreach($filieres as $filiere)
                <option value="{{ $filiere->id }}" {{ old('filiere_id', $programme->filiere_id ?? '') == $filiere->id ? 'selected' : '' }}>{{ $filiere->libelle }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="niveau_id" class="block text-sm font-medium text-slate-700 mb-1">Niveau</label>
            <select name="niveau_id" id="niveau_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">-- Choisir --</option>
                @foreach($niveaux as $niveau)
                <option value="{{ $niveau->id }}" {{ old('niveau_id', $programme->niveau_id ?? '') == $niveau->id ? 'selected' : '' }}>{{ $niveau->libelle }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="semestre_id" class="block text-sm font-medium text-slate-700 mb-1">Semestre</label>
            <select name="semestre_id" id="semestre_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">-- Choisir --</option>
                @foreach($semestres as $semestre)
                <option value="{{ $semestre->id }}" {{ old('semestre_id', $programme->semestre_id ?? '') == $semestre->id ? 'selected' : '' }}>{{ $semestre->libelle }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="module_id" class="block text-sm font-medium text-slate-700 mb-1">Module</label>
            <select name="module_id" id="module_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">-- Choisir --</option>
                @foreach($modules as $module)
                <option value="{{ $module->id }}" {{ old('module_id', $programme->module_id ?? '') == $module->id ? 'selected' : '' }}>{{ $module->libelle ?? $module->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="matiere_id" class="block text-sm font-medium text-slate-700 mb-1">Matière</label>
            <select name="matiere_id" id="matiere_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">-- Choisir --</option>
                @foreach($matieres as $matiere)
                <option value="{{ $matiere->id }}" {{ old('matiere_id', $programme->matiere_id ?? '') == $matiere->id ? 'selected' : '' }}>{{ $matiere->libelle }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="professeur_id" class="block text-sm font-medium text-slate-700 mb-1">Enseignant</label>
            <select name="professeur_id" id="professeur_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">-- Choisir --</option>
                @foreach($professeurs as $professeur)
                <option value="{{ $professeur->id }}" {{ old('professeur_id', $programme->professeur_id ?? '') == $professeur->id ? 'selected' : '' }}>{{ $professeur->nom_complet ?? ($professeur->nom . ' ' . $professeur->prenom) }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm mt-4">
    <div class="mb-4">
        <label for="salle_id" class="block text-sm font-medium text-slate-700 mb-1">Salle</label>
        <select name="salle_id" id="salle_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">-- Choisir --</option>
            @foreach($salles as $salle)
            <option value="{{ $salle->id }}" {{ old('salle_id', $programme->salle_id ?? '') == $salle->id ? 'selected' : '' }}>{{ $salle->nom_salle }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">Remarques</label>
        <textarea name="notes" id="notes" rows="3" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $programme->notes ?? '') }}</textarea>
    </div>
</div>

<div class="flex items-center justify-between pt-4">
    <a href="{{ route('admin.programme-cours.grille', ['date_debut' => request('date_debut') ?? (isset($programme) ? optional($programme->date_programme)->format('Y-m-d') : '')]) }}" class="text-sm text-slate-600 hover:text-slate-900 underline">Retour</a>
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">{{ $isEdit ? 'Enregistrer les modifications' : 'Programmer le cours' }}</button>
</div>