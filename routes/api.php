<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user(); // Récupère l'utilisateur authentifié
})->middleware('auth:sanctum');

Route::prefix("auth")->group(function () {
    Route::post('/login', [AuthController::class, 'login']); // Authentifie un utilisateur
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.auth'); // Déconnecte un utilisateur
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']); // Rafraîchit le token d'authentification
});

Route::middleware(['jwt.auth'])->group(function () {

    // Route pour la ressource User
    Route::prefix('users')->group(function () {
        Route::post('/create-agent-admin', [UserController::class, 'createAgentOrAdmin']); // Crée un agent ou un administrateur
        Route::post('/create-client', [UserController::class, 'createClientByAgent']); // Crée un client par un agent
        Route::get('/{id}', [UserController::class, 'getUserById'])->where('id', '[0-9a-fA-F-]+'); // Récupère un utilisateur par son ID
        Route::get('/', [UserController::class, 'listUsers']); // Liste tous les utilisateurs
        Route::delete('/{id}', [UserController::class, 'deleteUser'])->where('id', '[0-9a-fA-F-]+'); // Supprime un utilisateur par ID

        Route::middleware([RoleMiddleware::class . ':VENDOR,CLIENT'])->group(function () {
            Route::get('/current', [UserController::class, 'getCurrentUser']); // Récupère l'utilisateur actuellement authentifié
        });
    });

    // Route pour la ressource Transaction
    Route::prefix("transactions")->group(function () {
        Route::middleware([RoleMiddleware::class . ':ADMIN,AGENT'])->group(function () {
            Route::get("/", [RoleMiddleware::class, 'getAllTransactions']); // Récupère toutes les transactions
        });

        Route::middleware([RoleMiddleware::class . ':VENDOR,CLIENT'])->group(function () {
            Route::get("/current", [TransactionController::class, "getTransactionsByUser"]); // Récupère les transactions de l'utilisateur authentifié
            Route::post("/transfer", [TransactionController::class, "transfer"]); // Effectue un transfert de fonds
            Route::post("/purchase", [TransactionController::class, "purchase"]); // Effectue un achat
        });

        Route::middleware([RoleMiddleware::class . ':VENDOR'])->group(function(){
            Route::post("/withdraw", [TransactionController::class, "withdraw"]); // Retire des fonds
            Route::post("/deposit", [TransactionController::class, "deposit"]); // Dépose des fonds
        });

        Route::get("/{id}", [TransactionController::class, "getTransaction"]); // Récupère une transaction par ID
    });

    // Route pour tous les bills ou factures
    Route::prefix("bills")->group(function () {
        Route::middleware([RoleMiddleware::class . ':VENDOR,CLIENT'])->group(function () {
            Route::get("/current", [BillController::class, "current"]); // Récupère les factures de l'utilisateur authentifié
            Route::post("payer", [BillController::class, "store"]); // Effectue un paiement de facture
        });

        Route::middleware([RoleMiddleware::class . ':ADMIN,AGENT'])->group(function () {
            Route::get("/", [RoleMiddleware::class, 'index']); // Récupère toutes les factures
        });

        Route::get("/{id}", [BillController::class, "show"]); // Récupère une facture par ID
    });

    // Route pour tous les company
    Route::prefix("company")->group(function () {
        Route::get("/", [CompanyController::class, "index"]); // Liste toutes les entreprises
        Route::get("/{id}", [CompanyController::class, "show"]); // Récupère une entreprise par ID
    });

    // Route pours tous les contacts
    Route::prefix("contacts")->group(function () {
        Route::middleware([RoleMiddleware::class . ':ADMIN,AGENT'])->group(function () {
            Route::get("/", [ContactController::class, "index"]); // Liste tous les contacts
        });

        Route::middleware([RoleMiddleware::class . ':CLIENT'])->group(function () {
            Route::get("/current", [ContactController::class, "current"]); // Récupère les contacts de l'utilisateur authentifié
            Route::get("/{id}/toggle-favorite", [ContactController::class, "toggleFavorite"]); // Bascule le statut favori d'un contact
            Route::get("/{id}", [ContactController::class, "show"]); // Récupère un contact par ID
            Route::post("/", [ContactController::class, "store"]); // Crée un nouveau contact
            Route::post("/phone", [ContactController::class, "phone"]); // Gère les numéros de téléphone
        });
    });

});
