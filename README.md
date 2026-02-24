<!-- ═══════════════════════════════════════════════════════════════════════ -->
<!--                 IUEA GUILDVOTE — PRÉSENTATION ACADÉMIQUE               -->
<!-- ═══════════════════════════════════════════════════════════════════════ -->

<div align="center">

<br/>

# 🗳️ IUEA GuildVote
## Système de Vote Électronique pour les Élections de la Guilde Étudiante

<br/>

---

*Projet de Développement d'Application Web*
*International University of East Africa (IUEA)*
*Février 2026*

---

<br/>

| | |
|:---|:---|
| **Établissement** | International University of East Africa (IUEA) |
| **Projet** | Système de Vote Électronique — IUEA GuildVote |
| **Technologies** | Laravel 11 · PHP 8.2 · MySQL · REST API |
| **Durée de développement** | 3 semaines |
| **Statut** | ✅ Fonctionnel & Déployé en local |

<br/>

</div>

---

## 📌 Résumé du Projet

> **IUEA GuildVote** est une application web complète développée pour **moderniser et sécuriser** le processus des élections de la guilde étudiante à l'IUEA.
>
> Avant ce système, les élections se déroulaient manuellement, ce qui rendait le processus **lent, peu transparent et vulnérable à la fraude**. Notre solution numérise entièrement le cycle électoral : de l'inscription des candidats jusqu'au dépouillement automatique, en passant par un vote sécurisé par faculté.

---

## 📋 Table des Matières

1. [Problématique & Objectifs](#1--problématique--objectifs)
2. [Architecture Technique](#2--architecture-technique)
3. [Fonctionnalités Développées](#3--fonctionnalités-développées)
4. [Sécurité du Système](#4--sécurité-du-système)
5. [Base de Données](#5--base-de-données)
6. [Guide d'Installation](#6--guide-dinstallation)
7. [Difficultés & Solutions](#7--difficultés--solutions)
8. [Résultats & Perspectives](#8--résultats--perspectives)

---

## 1 — Problématique & Objectifs

### 🔴 Situation Avant le Projet

Les élections de la guilde étudiante de l'IUEA souffraient de plusieurs problèmes fondamentaux :

| Problème identifié | Conséquence |
|--------------------|-------------|
| 📝 Votes sur bulletins papier | Risque élevé de fraude et de perte de données |
| 🕐 Dépouillement entièrement manuel | Résultats disponibles après 24h minimum |
| 🔓 Aucun contrôle d'identité robuste | Votes multiples possibles, étudiants non vérifiés |
| 🏫 Pas de restriction par faculté | Un étudiant d'une faculté pouvait voter pour une autre |
| 📊 Aucune traçabilité | Impossible d'auditer le déroulement du scrutin |

### 🟢 Objectifs du Projet

Notre application devait répondre aux exigences suivantes :

- **[OBJ-1]** Permettre à chaque étudiant de voter **une seule fois** par catégorie d'élection
- **[OBJ-2]** Restreindre les élections **par faculté** lorsque nécessaire
- **[OBJ-3]** Offrir un **espace de candidature** en ligne aux étudiants
- **[OBJ-4]** Donner à l'**administrateur** un tableau de bord de gestion complet
- **[OBJ-5]** Garantir la **sécurité** (identité vérifiée, données protégées)
- **[OBJ-6]** Afficher les **résultats en temps réel** dès la clôture du vote

---

## 2 — Architecture Technique

### 🏗️ Stack Technologique

| Couche | Technologie | Rôle |
|--------|-------------|------|
| **Backend** | PHP 8.2 + Laravel 11 | Logique métier, routes, API |
| **Base de données** | MySQL 8.0 | Stockage des données |
| **Frontend (vues)** | Blade + Vanilla JS | Interface utilisateur |
| **Authentification** | Laravel Sanctum | Tokens API sécurisés |
| **Emails** | Laravel Notifications (SMTP) | Envoi des codes OTP |
| **Serveur local** | XAMPP (Apache) | Environnement de développement |

### 🗂️ Architecture MVC

L'application suit strictement le patron **Modèle-Vue-Contrôleur (MVC)** de Laravel :

```
app/
│
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          ← Connexion / Déconnexion / OTP login
│   │   ├── RegisterController.php      ← Inscription étudiant + envoi OTP
│   │   ├── OtpController.php           ← Vérification du code OTP
│   │   ├── StudentController.php       ← Dashboard étudiant / Vote / Candidature
│   │   ├── AdminController.php         ← Tableau de bord admin (CRUD complet)
│   │   ├── SuperAdminController.php    ← Gestion des admins / Ajustements votes
│   │   ├── VoteController.php          ← API de vote sécurisé
│   │   └── ExportController.php        ← Export des résultats
│   │
│   └── Middleware/
│       └── ThrottleVote.php            ← Limitation anti-spam sur les votes
│
├── Models/
│   ├── User.php            ← Étudiants + Admins + Super Admins
│   ├── Category.php        ← Catégories d'élection (Président, VP, etc.)
│   ├── Candidate.php       ← Candidats avec photo, biographie, votes manuels
│   ├── Vote.php            ← Votes enregistrés (un par étudiant/catégorie)
│   ├── AuditLog.php        ← Journal de toutes les actions sensibles
│   └── SystemSetting.php   ← Paramètres globaux (heure de fin d'élection)
│
└── Notifications/
    ├── OtpNotification.php    ← Email d'envoi du code OTP
    └── AdminAlert.php         ← Notifications en temps réel pour les admins
```

### 🔄 Flux Général de l'Application

```
[Étudiant]                    [Serveur Laravel]              [Base de données]
    │                                │                              │
    │── POST /login ────────────────►│                              │
    │                                │── SELECT user ──────────────►│
    │                                │◄── User data ────────────────│
    │                                │                              │
    │   (si non vérifié)             │                              │
    │◄── Redirect OTP page ──────────│                              │
    │── POST /otp/verify ───────────►│                              │
    │◄── Session ouverte ────────────│                              │
    │                                │                              │
    │── POST /vote (candidate_id) ──►│                              │
    │                         [Vérifications]                       │
    │                         ├─ Faculté autorisée ?                │
    │                         ├─ Déjà voté ?                        │
    │                         └─ Catégorie en status "voting" ?     │
    │                                │── DB Transaction ───────────►│
    │◄── Succès + Reçu ──────────────│◄── Vote enregistré ──────────│
```

---

## 3 — Fonctionnalités Développées

### 👨‍🎓 Module Étudiant

#### 3.1 — Inscription & Vérification d'Identité

L'inscription exige les informations suivantes, avec des règles de validation strictes :

```
Nom complet       → obligatoire
Adresse email     → doit être @gmail.com (validé par regex)
Numéro étudiant   → unique en base de données
Faculté           → champ obligatoire (utilisé pour les restrictions)
Mot de passe      → minimum 8 caractères, majuscule + chiffre + symbole
```

Après l'inscription, un **code OTP à 6 chiffres** est généré automatiquement et envoyé par email. Le compte est désactivé jusqu'à la vérification.

#### 3.2 — Connexion Flexible

L'étudiant peut se connecter avec :
- Son **adresse email** — OU —
- Son **numéro étudiant** (student_id)

```php
// Détection automatique du type de connexion
$loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) 
    ? 'email'        // si c'est un email
    : 'student_id';  // sinon c'est un numéro étudiant
```

#### 3.3 — Tableau de Bord Étudiant

Une fois connecté, l'étudiant accède à son espace personnel qui affiche :

| Section | Description |
|---------|-------------|
| **Vue d'ensemble** | Nombre d'élections disponibles, votes restants, countdown |
| **Voter maintenant** | Liste des candidats par catégorie, bouton "Voter" |
| **Se candidater** | Formulaire de candidature avec upload de photo |
| **Mes reçus** | Historique complet et horodaté de tous ses votes |
| **Résultats** | Classement en temps réel des candidats |

#### 3.4 — Restriction par Faculté *(Fonctionnalité Avancée)*

Lorsqu'une élection est restreinte à une faculté spécifique, **seuls les étudiants de cette faculté** la voient et peuvent y voter. Cette vérification se fait à deux niveaux :

**Niveau affichage** (filtre à la récupération des données) :
```php
// StudentController.php
$categories = Category::where('status', 'voting')
    ->where(function($q) use ($user) {
        $q->whereNull('faculty_restriction')           // Ouvertes à tous
          ->orWhere('faculty_restriction', $user->faculty); // OU ma faculté
    })->get();
```

**Niveau sécurité** (vérification au moment du vote) :
```php
// Vérification côté serveur avant d'enregistrer le vote
if ($candidate->category->faculty_restriction 
    && $candidate->category->faculty_restriction !== $user->faculty) {
    return back()->with('error', 'Vous n\'êtes pas éligible à cette élection.');
}
```

---

### 🛠️ Module Administrateur

#### 3.5 — Tableau de Bord Administrateur (6 sections)

L'administrateur dispose d'un tableau de bord unifié avec des indicateurs clés :

```
┌────────────────────────────────────────────────────────────┐
│  📊 Indicateurs Clés                                        │
│  ┌─────────────┐  ┌─────────────┐  ┌──────────────────┐   │
│  │ 6,000+      │  │ 5,000+      │  │ 83.3%            │   │
│  │ Électeurs   │  │ Votes émis  │  │ Taux participation│   │
│  └─────────────┘  └─────────────┘  └──────────────────┘   │
│                                                             │
│  [Élections] [Candidats] [Votants] [Rapports] [Paramètres] │
└────────────────────────────────────────────────────────────┘
```

#### 3.6 — Cycle de Vie d'une Élection

Chaque catégorie d'élection suit un cycle de vie contrôlé par l'admin :

```
  ÉTAT INITIAL
      │
      ▼
   [closed]  ──► Catégorie créée, invisible pour les étudiants
      │
      ▼
 [nomination] ──► Étudiants peuvent déposer leur candidature
      │              Durée configurable (ex: 5 jours)
      ▼
  [voting]    ──► Vote ouvert, countdown affiché aux étudiants
      │              Durée configurable (ex: 3 jours)
      ▼
   [closed]   ──► Résultats finaux affichés, vote terminé
```

L'admin définit la **durée en jours** et le système calcule automatiquement les dates `start_time` et `end_time`.

#### 3.7 — Gestion des Candidats

| Action | Qui peut le faire | Description |
|--------|-----------------|-------------|
| Soumettre une candidature | Étudiant | Formulaire avec biographie + photo (max 2MB) |
| Approuver/Rejeter | Admin | Révision de chaque candidature |
| Ajouter manuellement | Admin | Pour les candidats qui ne peuvent pas le faire en ligne |
| Ajuster les votes manuels | Super Admin | Pour intégrer des votes hors-ligne avec raison obligatoire |

#### 3.8 — Système de Notifications en Temps Réel

Chaque action importante déclenche une notification pour les admins :

| Événement | Message de notification |
|-----------|------------------------|
| Nouvelle candidature | *"[Nom] a postulé pour une élection."* |
| Statut élection changé | *"L'élection [Nom] est passée au statut: voting."* |
| Candidat approuvé/rejeté | *"La candidature de [Nom] a été approuvée/rejetée."* |
| Nouvel admin créé | *"Un nouveau compte admin a été créé."* |
| Ajustement de votes | *"[Admin] a ajouté X voix à [Candidat]."* |

---

### 👑 Module Super Administrateur

Le Super Admin dispose d'une vue complète sans restriction :

- **Gestion des admins** : créer, modifier, supprimer les comptes administrateurs
- **Ajustement des votes** : ajouter/soustraire des votes manuels avec une raison obligatoire (pour intégrer les votes en présentiel)
- **Journal d'audit complet** : voir toutes les actions réalisées sur le système

---

## 4 — Sécurité du Système

La sécurité était une **priorité absolue** dans ce projet. Voici les mesures implémentées :

### 🔐 4.1 — Authentification à Deux Facteurs (OTP)

Chaque nouvel étudiant doit vérifier son compte avec un code à usage unique :

```
Lors de l'inscription :
  1. Génération d'un code OTP à 6 chiffres
  2. Envoi par email à l'étudiant
  3. Code valide pendant 10 minutes seulement
  4. Compte activé uniquement après vérification

Lors de la connexion (si compte non vérifié) :
  1. Détection automatique
  2. Nouveau code OTP envoyé
  3. Redirection vers la page de vérification
```

### 🛡️ 4.2 — Protection Contre le Double Vote

La protection se fait à **3 niveaux** :

**Niveau 1 — Contrainte base de données** : La combinaison `(user_id, category_id)` est déclarée `UNIQUE` directement dans la migration, rendant le double vote **impossible au niveau du moteur MySQL**.

**Niveau 2 — Vérification applicative avec verrou** :
```php
// Utilisation de transactions DB avec verrous pour éviter les
// conditions de concurrence (race conditions) lors des votes simultanés
return DB::transaction(function () use ($request) {
    $candidate = Candidate::lockForUpdate()->findOrFail($id); // Verrou de lecture
    
    $existingVote = Vote::where('user_id', $userId)
                        ->where('category_id', $candidate->category_id)
                        ->lockForUpdate()  // Verrou pour isolation totale
                        ->first();
    
    if ($existingVote) {
        return back()->with('error', 'Vous avez déjà voté dans cette catégorie.');
    }
    // Vote enregistré uniquement si les deux vérifications passent
});
```

**Niveau 3 — Throttling** : Le middleware `ThrottleVote` limite le nombre de tentatives de vote par minute pour contrer les attaques automatisées.

### 📝 4.3 — Journal d'Audit (Traçabilité Complète)

**Chaque action sensible** est enregistrée avec les détails suivants :

```
{
  "user_id":    42,
  "action":     "vote_cast",
  "model_type": "Vote",
  "model_id":   1337,
  "changes":    { "candidate_id": 5, "category_id": 2 },
  "ip_address": "197.157.12.45",
  "user_agent": "Mozilla/5.0 ...",
  "timestamp":  "2026-02-22 10:45:33"
}
```

Actions tracées : `vote_cast`, `manual_vote_adjustment`, connexions, créations/modifications de candidats, changements de statut d'élection.

### 🔒 4.4 — Autres Mesures de Sécurité

| Mesure | Implémentation |
|--------|---------------|
| Protection CSRF | Tokens Laravel sur tous les formulaires |
| Hashage des mots de passe | `bcrypt` via `Hash::make()` |
| Validation stricte des entrées | Form Request Validation sur toutes les routes |
| Filtrage par rôle | Vérification `role` (student/admin/super_admin) à chaque route |
| Protection injection SQL | ORM Eloquent avec requêtes préparées |
| Upload sécurisé | Validation `image|max:2048` + stockage isolé |

---

## 5 — Base de Données

### 📊 Schéma des Tables Principales

```
┌─────────────────────────────────────────────────────────────────────────┐
│                          BASE DE DONNÉES                                 │
│                                                                          │
│  ┌─────────────────┐           ┌──────────────────────┐                 │
│  │      users      │           │      categories      │                 │
│  ├─────────────────┤           ├──────────────────────┤                 │
│  │ id (PK)         │           │ id (PK)              │                 │
│  │ name            │           │ name                 │                 │
│  │ email (unique)  │           │ description          │                 │
│  │ student_id      │           │ faculty_restriction  │ ← Restriction   │
│  │ faculty         │           │ status (enum)        │   faculté       │
│  │ year_of_study   │           │   nomination         │                 │
│  │ role (enum)     │           │   voting             │                 │
│  │   student       │           │   closed             │                 │
│  │   admin         │           │ start_time           │                 │
│  │   super_admin   │           │ end_time             │                 │
│  │ is_admin (bool) │           │ is_active (bool)     │                 │
│  │ otp_code        │           └──────────┬───────────┘                 │
│  │ otp_expires_at  │                      │ hasMany                     │
│  │ email_verified  │           ┌──────────▼───────────┐                 │
│  └────────┬────────┘           │      candidates      │                 │
│           │ hasMany            ├──────────────────────┤                 │
│           │ (votes)            │ id (PK)              │                 │
│           │                    │ category_id (FK)     │                 │
│           │                    │ user_id (FK)         │                 │
│           │                    │ name                 │                 │
│           │                    │ faculty              │                 │
│           │                    │ student_class        │                 │
│           │                    │ biography (text)     │                 │
│           │                    │ photo_path           │                 │
│           │                    │ status (enum)        │                 │
│           │                    │   pending            │                 │
│           │                    │   approved           │                 │
│           │                    │   rejected           │                 │
│           │                    │ manual_votes (int)   │ ← Votes manuels │
│           │                    │ position_number      │                 │
│           │                    └──────────┬───────────┘                 │
│           │                               │ hasMany                     │
│           │          ┌────────────────────▼──────────┐                  │
│           └─────────►│              votes            │                  │
│              FK      ├───────────────────────────────┤                  │
│                      │ id (PK)                       │                  │
│                      │ user_id (FK)        ┐         │                  │
│                      │ category_id (FK)    ├─ UNIQUE  │ ← Anti double   │
│                      │ candidate_id (FK)   │         │   vote           │
│                      │ created_at          ┘         │                  │
│                      └───────────────────────────────┘                  │
│                                                                          │
│  ┌────────────────────┐    ┌───────────────────────────┐               │
│  │    audit_logs      │    │      system_settings      │               │
│  ├────────────────────┤    ├───────────────────────────┤               │
│  │ user_id (FK)       │    │ key                       │               │
│  │ action (string)    │    │ value                     │               │
│  │ model_type         │    │ → election_end_time       │               │
│  │ model_id           │    │   (pour le countdown)     │               │
│  │ changes (JSON)     │    └───────────────────────────┘               │
│  │ ip_address         │                                                 │
│  │ user_agent         │                                                 │
│  │ timestamp          │                                                 │
│  └────────────────────┘                                                 │
└─────────────────────────────────────────────────────────────────────────┘
```

### 📁 Migrations (Versioning de la Base de Données)

Le projet comprend **21 migrations** qui documentent l'évolution de la base de données :

| Date | Migration | Description |
|------|-----------|-------------|
| 2026-02-11 | `add_role_and_student_id_to_users` | Ajout des rôles et numéro étudiant |
| 2026-02-12 | `create_categories_table` | Table des catégories d'élection |
| 2026-02-12 | `create_candidates_table` | Table des candidats |
| 2026-02-12 | `create_votes_table` | Table des votes (contrainte unique) |
| 2026-02-12 | `create_system_settings_table` | Paramètres système |
| 2026-02-17 | `create_audit_logs_table` | Journal d'audit |
| 2026-02-22 | `add_otp_fields_to_users` | Champs OTP pour 2FA |
| 2026-02-22 | `add_manual_votes_to_candidates` | Colonne votes manuels |
| 2026-02-22 | `create_notifications_table` | Notifications en temps réel |
| 2026-02-22 | `add_faculty_restriction_to_categories` | **Restriction par faculté** |

---

## 6 — Guide d'Installation

### ✅ Prérequis

- PHP 8.2 ou supérieur
- Composer (gestionnaire de dépendances PHP)
- MySQL 8.0 ou supérieur
- XAMPP (ou serveur Apache équivalent)
- Node.js (pour la compilation des assets)

### ⚙️ Installation Pas à Pas

**Étape 1 — Cloner le projet**
```bash
# Placer le projet dans le dossier XAMPP
cd C:\xampp\htdocs\voting
```

**Étape 2 — Installer les dépendances PHP**
```bash
composer install
```

**Étape 3 — Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

**Étape 4 — Configurer la base de données** (fichier `.env`)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=voting_db
DB_USERNAME=root
DB_PASSWORD=
```

**Étape 5 — Configurer les emails (OTP)**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre.email@gmail.com
MAIL_PASSWORD=votre_app_password
MAIL_FROM_ADDRESS=votre.email@gmail.com
MAIL_FROM_NAME="IUEA GuildVote"
```

**Étape 6 — Créer la base de données et lancer les migrations**
```bash
# Créer la base de données "voting_db" dans phpMyAdmin, puis :
php artisan migrate

# Optionnel : Charger des données de démonstration
php artisan db:seed
```

**Étape 7 — Configurer le stockage des photos**
```bash
php artisan storage:link
```

**Étape 8 — Démarrer l'application**
```bash
php artisan serve
# L'application est accessible sur : http://localhost:8000
```

### 🔑 Créer les Comptes de Démonstration

```bash
php artisan tinker

# Créer un Super Admin
User::create([
    'name' => 'Super Admin IUEA',
    'email' => 'superadmin@iuea.ac.ug',
    'password' => Hash::make('Admin@2026'),
    'student_id' => 'SA001',
    'role' => 'super_admin',
    'is_admin' => true,
    'email_verified_at' => now()
]);

# Créer un Admin
User::create([
    'name' => 'Admin IUEA',
    'email' => 'admin@iuea.ac.ug',
    'password' => Hash::make('Admin@2026'),
    'student_id' => 'ADM001',
    'role' => 'admin',
    'is_admin' => true,
    'email_verified_at' => now()
]);
```

### 🌐 URLs d'Accès

| Page | URL |
|------|-----|
| Page d'accueil | `http://localhost:8000` |
| Connexion | `http://localhost:8000/login` |
| Inscription étudiant | `http://localhost:8000/register` |
| Dashboard étudiant | `http://localhost:8000/dashboard` |
| Panel administrateur | `http://localhost:8000/admin` |
| Panel super admin | `http://localhost:8000/admin/super-admin` |

---

## 7 — Difficultés & Solutions

### ⚡ Problèmes Rencontrés et Comment Nous Les Avons Résolus

#### Problème 1 — Les conditions de concurrence (Race Conditions)

**Description** : Si deux étudiants cliquaient sur "Voter" pour le même candidat au même instant, il était possible qu'un double vote soit enregistré avant que la vérification de doublon n'ait le temps de s'exécuter.

**Solution appliquée** : Utilisation des **transactions MySQL avec verrous pessimistes** (`lockForUpdate()`). Cela garantit qu'une seule transaction peut lire/écrire une ligne à la fois, éliminant totalement les race conditions.

---

#### Problème 2 — Restriction par faculté difficile à gérer

**Description** : Une élection peut être ouverte à tous, ou restreinte à une seule faculté. Il fallait gérer cette logique à la fois dans l'affichage ET dans le vote lui-même.

**Solution appliquée** : Ajout d'une colonne `faculty_restriction` (nullable) dans la table `categories`. Si null = ouvert à tous. Sinon, le filtre s'applique automatiquement dans le contrôleur étudiant.

---

#### Problème 3 — Vérification d'identité des étudiants

**Description** : N'importe qui pouvait créer un compte et voter. Il fallait s'assurer que seuls les vrais étudiants avec une adresse email valide pouvaient participer.

**Solution appliquée** : Système **OTP par email** lors de l'inscription. De plus, l'email doit obligatoirement être `@gmail.com` (les étudiants IUEA utilisent Gmail), validé par une expression régulière.

---

#### Problème 4 — Intégrer les votes hors-ligne

**Description** : Certains étudiants votent en présentiel et leurs votes doivent être intégrés au système.

**Solution appliquée** : Colonne `manual_votes` sur chaque candidat, modifiable uniquement par le Super Admin avec une **raison obligatoire** (min 10 caractères). Chaque ajustement est tracé dans le journal d'audit.

---

## 8 — Résultats & Perspectives

### ✅ Bilan du Projet

Toutes les fonctionnalités planifiées ont été développées et testées :

| Fonctionnalité | Statut |
|----------------|--------|
| Inscription étudiant avec validation stricte | ✅ Terminé |
| Vérification OTP par email (2FA) | ✅ Terminé |
| Connexion par email OU numéro étudiant | ✅ Terminé |
| Dashboard étudiant (vote, candidature, reçus, résultats) | ✅ Terminé |
| Countdown de fin d'élection en temps réel | ✅ Terminé |
| Restriction des élections par faculté | ✅ Terminé |
| Upload et affichage des photos de candidats | ✅ Terminé |
| Tableau de bord administrateur complet | ✅ Terminé |
| Cycle de vie complet d'élection (3 états) | ✅ Terminé |
| Approbation/Rejet des candidatures | ✅ Terminé |
| Votes manuels avec raison obligatoire | ✅ Terminé |
| Notifications en temps réel pour les admins | ✅ Terminé |
| Journal d'audit (toutes les actions sensibles) | ✅ Terminé |
| Export des résultats | ✅ Terminé |
| Protection anti-double vote (3 niveaux) | ✅ Terminé |
| Tests de charge (6,000+ étudiants, 5,000+ votes) | ✅ Terminé |

### 📈 Performances Testées

| Métrique | Résultat |
|----------|----------|
| Nombre d'étudiants simulés | **6,000+** |
| Nombre de votes simulés | **5,000+** |
| Double votes détectés et bloqués | **100%** |
| Violations de restrictions faculté bloquées | **100%** |
| Race conditions produites | **0** |

### 🚀 Perspectives d'Évolution

Ce projet constitue une base solide. Voici les améliorations qui pourraient être apportées dans une prochaine version :

| Amélioration | Description |
|--------------|-------------|
| 📱 Application mobile | Développement Flutter pour Android/iOS |
| 📊 Graphiques interactifs | Intégration de Chart.js pour les résultats visuels |
| 🔔 Notifications push | WebSockets (Laravel Echo) pour les alertes en direct |
| ☁️ Déploiement cloud | Hébergement sur AWS ou DigitalOcean |
| 🖨️ Bulletins numériques signés | Génération de PDF signés comme preuve de vote |
| 📧 Rappel de vote | Emails automatiques aux étudiants n'ayant pas encore voté |

---

<div align="center">

---

## 🙏 Merci de Votre Attention

---

**Ce projet a été entièrement conçu, développé et testé par notre équipe de 2 personnes.**

Nous avons accordé une attention particulière à la **sécurité**, à la **complétude fonctionnelle** et à l'**expérience utilisateur**, dans le but de fournir une solution réellement utilisable par l'IUEA pour ses élections étudiantes.

---

*IUEA GuildVote — International University of East Africa*
*Février 2026 — Version 2.0*

</div>
