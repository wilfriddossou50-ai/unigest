# Remise en coherence du projet UniGest

## Diagnostic rapide

Le projet a deja une bonne base Laravel, mais il melange actuellement trois niveaux qui ne sont pas encore alignes :

1. le schema de donnees,
2. la logique metier,
3. les interfaces admin et etudiant.

Le resultat est un projet qui "a l'air avance", mais dont plusieurs parcours reposent sur des hypotheses contradictoires.

## Incoherences majeures reperees

### 1. Structure academique incomplete ou contradictoire

Le dashboard etudiant suppose qu'une `Filiere` possede des `Module`. La correction commence par ajouter `filiere_id` sur la table `modules`.

Impacts :

- `Filiere::modules()` ne peut pas fonctionner correctement.
- les pages etudiant `modules`, `matieres` et `emploi` reposent sur une relation incomplete.
- on ne sait pas encore si un module appartient a une filiere, a un niveau, a un semestre, ou a une combinaison de ces trois dimensions.

Conclusion :

Le modele academique doit etre decide avant toute continuation fonctionnelle.

### 2. Deux parcours etudiant qui ne racontent pas la meme histoire

Il existe aujourd'hui deux facons de creer un etudiant :

- inscription publique via `RegisteredUserController`,
- creation directe par l'admin via `EtudiantController`.

Probleme :

- le login et le dashboard etudiant s'appuient sur `inscriptions()->latest()->first()`,
- un etudiant cree manuellement par l'admin peut avoir un profil `etudiants`, mais aucune inscription,
- dans ce cas, il risque d'etre renvoye vers la page d'attente alors que son compte existe deja.

Conclusion :

Il faut choisir une seule verite de reference pour l'admission :

- soit toute creation d'etudiant passe par une inscription,
- soit le dashboard et la connexion acceptent aussi les etudiants admin sans inscription.

### 3. Vues et sections admin affichees mais non finalisees

Le layout admin expose des sections comme :

- resultats semestre,
- resultats annuels,
- progression.

Mais plusieurs vues ciblees par les controleurs n'existent pas encore, ou ne sont pas presentes dans `resources/views`.

Impacts :

- navigation qui donne une impression de produit termine,
- clics qui peuvent casser en production,
- confusion entre ce qui est reellement disponible et ce qui est encore en chantier.

Conclusion :

Il faut masquer ou retirer temporairement les sections non terminees, ou bien les terminer avant d'elargir le perimetre.

### 4. Logique annuelle encore en mode placeholder

Dans `ResultatAnnuelController`, les moyennes de semestre sont encore forcees a `0`.

Impacts :

- les resultats annuels ne representent pas les vraies notes,
- la progression et la diplomation ne peuvent pas etre fiables,
- toute decision academique downstream est potentiellement fausse.

Conclusion :

Ne pas construire de nouvelles vues de resultats tant que les calculs reels ne sont pas branches.

### 5. Emploi du temps partiellement modele, mais filtre etudiant incomplet

L'admin cree les seances avec `niveau_id` et `semestre_id`, ce qui est une bonne direction.

Probleme :

- la vue etudiant filtre surtout via les semestres deduits des modules de la filiere,
- elle n'exploite pas correctement `niveau_id`,
- donc un etudiant peut recuperer un emploi qui ne correspond pas precisement a son niveau.

Conclusion :

Le parcours etudiant doit etre recable sur une affectation academique explicite.

## Ordre de reprise recommande

### Phase 1. Fixer la verite metier

Avant de corriger les vues, il faut decider la structure academique officielle :

- une filiere contient-elle plusieurs niveaux ?
- un niveau appartient-il a une filiere ou est-il global ?
- un module appartient-il a une filiere + niveau + semestre ?
- les matieres dependent-elles uniquement du module ?

Decision retenue :

- `Filiere`
- `Niveau`
- `Semestre`
- `Module` appartient a `filiere` et `semestre`
- `Matiere` appartient a `module`
- `Etudiant` appartient a `user`, `filiere`, `niveau`

Tant que cette base n'est pas figee, les ecrans etudiants resteront fragiles.

### Phase 2. Aligner la base de donnees

Une fois le modele choisi :

- ajouter `filiere_id` sur `modules`,
- verifier si `emploi_du_temps` doit aussi porter `filiere_id` ou seulement `niveau_id`,
- verifier les contraintes et suppressions en cascade,
- nettoyer les migrations d'ajout tardif qui corrigent un schema encore mouvant.

Objectif :

avoir un schema qui raconte une seule histoire coherente.

### Phase 3. Unifier le parcours admission etudiant

Choisir entre deux strategies :

1. toute inscription etudiante passe par `inscriptions`,
2. l'admin peut creer un etudiant directement, mais ce cas doit etre reconnu partout.

Recommandation :

garder `inscriptions` comme porte d'entree officielle pour les etudiants externes, et reserver la creation admin a des cas exceptionnels clairement assumes.

Dans cette phase, il faut :

- definir les etats du parcours : `en_attente`, `approuvee`, `refusee`,
- garantir qu'un compte approuve cree toujours un vrai profil `etudiants`,
- garantir qu'un etudiant authentifie a une source de verite unique pour son acces.

### Phase 4. Stabiliser l'admin avant d'elargir

Ne garde dans le menu admin que les modules vraiment operationnels :

- inscriptions,
- etudiants,
- filieres,
- niveaux,
- semestres,
- modules,
- matieres,
- professeurs,
- notes,
- emplois du temps.

Reporter temporairement ou cacher :

- resultats semestriels,
- resultats annuels,
- progression,
- tout ecran sans vue ou sans logique complete.

### Phase 5. Rebrancher les ecrans etudiant

Quand les relations seront fiables, corriger dans cet ordre :

1. dashboard principal,
2. modules,
3. matieres,
4. emploi du temps,
5. notes,
6. bulletin,
7. dettes.

Pourquoi cet ordre :

- `modules`, `matieres` et `emploi` dependent directement du modele academique,
- `notes` et `bulletin` dependent ensuite de cette structure,
- `dettes` depend des evaluations et des regles de validation.

### Phase 6. Finaliser les calculs academiques

Seulement apres les phases precedentes :

- calcul reel du resultat semestriel depuis les notes,
- calcul annuel a partir de S1 + S2,
- progression academique,
- diplomation.

## Ce qu'il ne faut plus faire maintenant

- ajouter de nouvelles pages avant d'aligner le schema,
- enrichir les dashboards etudiant tant que `modules` n'est pas correctement rattache,
- afficher des liens admin vers des fonctionnalites inachevees,
- empiler des corrections ponctuelles sans choisir le modele metier cible.

## Prochaine etape conseillee

La prochaine vraie bonne etape est :

**refondre proprement le modele academique autour de `modules`**, puis corriger les flux `inscription -> approbation -> etudiant`.

## Proposition de chantier concret

Si on continue ensemble, l'ordre le plus sain est :

1. corriger le schema `modules` et les relations associees,
2. aligner les modeles Eloquent,
3. corriger les dashboards etudiant qui en dependent,
4. nettoyer le menu admin et les sections non finies,
5. reprendre ensuite les calculs de resultats.
