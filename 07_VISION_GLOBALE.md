
# Vision du projet

L'objectif n'est **pas** de créer un SAP RH.

L'objectif est de développer un **petit SIRH** moderne destiné à une PME de 20 à 50 employés.

On va privilégier :

- interface simple
    
- peu de clics
    
- architecture claire
    
- code facilement défendable en soutenance
    
- base de données propre
    

Le professeur demande beaucoup de fonctionnalités, mais elles peuvent rester simples.

---

# Stack

Puisque la stack imposée est XAMPP :

- PHP 8
    
- MySQL
    
- HTML
    
- CSS
    
- JavaScript
    
- Bootstrap 5
    

Mais on va transformer Bootstrap pour obtenir un style **Claymorphism**.

On ajoutera simplement :

- Bootstrap Icons
    
- Chart.js
    

Pas besoin d'autres frameworks.

---

# Architecture

Je déconseille Laravel.

Une architecture MVC légère sera largement suffisante.

```
sirh/

assets/
    css/
    js/
    images/

config/
    database.php

models/

controllers/

views/

includes/

uploads/

exports/

vendor/

index.php
login.php
logout.php
```

Très simple.

---

# Les rôles

Seulement 4 rôles.

## Administrateur

gère tout

---

## RH

gère

- employés
    
- contrats
    
- paie
    
- formations
    

---

## Manager

peut

- approuver congés
    
- voir son équipe
    

---

## Employé

peut

- voir ses bulletins
    
- demander un congé
    
- modifier quelques informations
    

---

# Les modules

Je découperais le projet en **7 modules**.

---

## Module 1

Authentification

- connexion
    
- déconnexion
    
- rôles
    
- profil
    

---

## Module 2

Gestion des employés

CRUD

Informations :

- prénom
    
- nom
    
- téléphone
    
- email
    
- date naissance
    
- poste
    
- département
    
- salaire
    
- photo
    
- état
    

---

## Module 3

Contrats

CRUD

CDD

CDI

Date début

Date fin

Type

Salaire

Statut

---

## Module 4

Congés

Le salarié

↓

fait une demande

↓

manager valide

↓

RH valide

↓

solde automatiquement diminué

Simple.

---

## Module 5

Paie

Le cœur du projet.

Chaque mois :

on choisit

Mai 2026

↓

employé

↓

on saisit :

- prime
    
- heures sup
    
- absences
    

↓

Calcul automatique

↓

génération bulletin PDF

---

Le calcul peut être volontairement simplifié.

Le professeur veut voir :

- salaire brut
    
- IPRES
    
- CSS
    
- CFCE
    
- IR
    
- Net à payer
    

Pas forcément reproduire toute la complexité d'un logiciel de paie professionnel.

---

## Module 6

Formation

CRUD

Formation

Date

Lieu

Employés concernés

Présence

---

## Module 7

Dashboard

Statistiques

Employés

Congés

Masse salariale

Répartition H/F

CDD/CDI

Graphiques Chart.js

---

# Base de données

Une douzaine de tables suffit.

```
users

roles

employees

departements

contracts

leave_requests

leave_types

payrolls

payroll_items

trainings

training_participants

audit_logs
```

Seulement 12 tables.

Très propre.

---

# Navigation

```
Connexion

↓

Dashboard

↓

Employés

↓

Contrats

↓

Congés

↓

Paie

↓

Formations

↓

Rapports

↓

Paramètres
```

---

# Dashboard

Le dashboard doit impressionner.

Je verrais :

```
+------------------------------+

Effectif

Masse salariale

Congés en attente

CDD arrivant à échéance

+------------------------------+

Graphique

Evolution masse salariale

+

Répartition employés

+

Congés du mois

```

Très moderne.

---

# UI

Tu souhaites du **Claymorphism**.

Je valide totalement.

Palette :

```
Fond

#eef2f8

Cartes

rgba(255,255,255,.45)

Ombres

box-shadow:
15px 15px 30px rgba(0,0,0,.08),
-10px -10px 20px rgba(255,255,255,.8)

Border

rgba(255,255,255,.35)

Border radius

25px

Blur

backdrop-filter: blur(14px)
```

Les boutons auront des coins très arrondis.

Les cartes flotteront légèrement.

Très moderne.

---

# Ce qu'on peut simplifier

Pour rester dans les délais, je propose de ne pas implémenter entièrement :

- les déclarations IPRES/CSS réglementaires (on pourra générer un état simplifié),
    
- les calculs fiscaux extrêmement détaillés,
    
- les workflows complexes de validation,
    
- la gestion documentaire avancée.
    

En revanche, on conservera toutes les fonctionnalités attendues sous une forme simple afin de satisfaire le cahier des charges.

---

# Planning de réalisation

Je te propose ce plan de développement :

### Phase 1 — Conception

- Analyse fonctionnelle
    
- Modèle relationnel (MCD/MLD)
    
- Architecture MVC
    
- Maquettes UI Claymorphism
    

### Phase 2 — Base du projet

- Structure des dossiers
    
- Configuration PDO
    
- Authentification
    
- Gestion des rôles
    

### Phase 3 — Modules métiers

- Employés
    
- Contrats
    
- Congés
    
- Paie
    
- Formations
    

### Phase 4 — Reporting

- Dashboard
    
- Graphiques Chart.js
    
- Export PDF
    
- Export Excel/CSV
    

### Phase 5 — Finalisation

- Journal d'audit
    
- Sauvegarde de la base
    
- Responsive
    
- Documentation
    
- Données de démonstration
    

## Ordre dans lequel je te propose de construire le projet

1. Architecture MVC
    
2. Base de données MySQL complète
    
3. Interface Claymorphism (layout général)
    
4. Authentification
    
5. Dashboard
    
6. Module Employés
    
7. Module Contrats
    
8. Module Congés
    
9. Module Paie
    
10. Module Formations
    
11. Rapports (PDF/Excel)
    
12. Finitions et sécurité
    
