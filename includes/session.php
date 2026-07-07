<?php
/**
 * HRFlowSn - Session Management
 * Gestion des sessions et démarrage de session sécurisé
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Définir une variable de session
 */
function session_set($key, $value) {
    $_SESSION[$key] = $value;
}

/**
 * Récupérer une variable de session
 */
function session_get($key, $default = null) {
    return $_SESSION[$key] ?? $default;
}

/**
 * Vérifier si une variable de session existe
 */
function session_has($key) {
    return isset($_SESSION[$key]);
}

/**
 * Supprimer une variable de session
 */
function session_remove($key) {
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

/**
 * Détruire la session
 */
function session_destroy_all() {
    $_SESSION = [];
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }
    session_destroy();
}

/**
 * Vérifier si l'utilisateur est connecté
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Obtenir l'ID de l'utilisateur connecté
 */
function get_current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Obtenir le rôle de l'utilisateur connecté
 */
function get_current_user_role() {
    return $_SESSION['user_role'] ?? null;
}

/**
 * Définir un message flash
 */
function set_flash_message($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Récupérer et effacer le message flash
 */
function get_flash_message() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Vérifier si un message flash existe
 */
function has_flash_message() {
    return isset($_SESSION['flash']);
}

/**
 * Sauvegarder les données du formulaire en session
 */
function set_old_input($data) {
    $_SESSION['old_input'] = $data;
}

/**
 * Récupérer une ancienne valeur de formulaire
 */
function get_old_input($key, $default = '') {
    return $_SESSION['old_input'][$key] ?? $default;
}

/**
 * Effacer les anciennes valeurs de formulaire
 */
function clear_old_input() {
    if (isset($_SESSION['old_input'])) {
        unset($_SESSION['old_input']);
    }
}
