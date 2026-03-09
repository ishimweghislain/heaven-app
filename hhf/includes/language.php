<?php
/**
 * Language Handler
 * HHF LTD. Website
 * 
 * This file handles the multilingual functionality of the website
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Get translations from JSON file
 * 
 * @return array Translations array
 */
function getTranslations() {
    $translationsFile = __DIR__ . '/../languages/translations.json';
    
    if (file_exists($translationsFile)) {
        $translationsJson = file_get_contents($translationsFile);
        return json_decode($translationsJson, true);
    }
    
    // Return empty array if file doesn't exist
    return [];
}

/**
 * Get current language
 * 
 * @return string Current language code (en or am)
 */
function getCurrentLanguage() {
    // Check if language is set in session
    if (isset($_SESSION['language'])) {
        return $_SESSION['language'];
    }
    
    // Check if language is set in cookie
    if (isset($_COOKIE['language'])) {
        $_SESSION['language'] = $_COOKIE['language'];
        return $_COOKIE['language'];
    }
    
    // Default to English
    $_SESSION['language'] = 'en';
    return 'en';
}

/**
 * Set current language
 * 
 * @param string $lang Language code (en or rw)
 * @return bool Success status
 */
function setLanguage($lang) {
    // Validate language code
    if ($lang !== 'en' && $lang !== 'rw'&& $lang !== 'fr') {
        return false;
    }
    
    // Set language in session
    $_SESSION['language'] = $lang;
    
    // Set language in cookie (30 days expiration)
    setcookie('language', $lang, time() + (86400 * 30), '/');
    
    return true;
}

/**
 * Translate text based on current language
 * 
 * @param string $key Translation key in dot notation (e.g., 'navigation.home')
 * @param string $default Default text if translation not found
 * @return string Translated text
 */
function translate($key, $default = '') {
    static $translations = null;
    
    // Load translations if not already loaded
    if ($translations === null) {
        $translations = getTranslations();
    }
    
    // Get current language
    $lang = getCurrentLanguage();
    
    // Split key into parts
    $keyParts = explode('.', $key);
    
    // Navigate through translations array
    $value = $translations[$lang];
    foreach ($keyParts as $part) {
        if (!isset($value[$part])) {
            // Return default value if key not found
            return $default ?: $key;
        }
        $value = $value[$part];
    }
    
    return $value;
}

/**
 * Output translated text (shorthand function)
 * 
 * @param string $key Translation key
 * @param string $default Default text if translation not found
 */
function t($key, $default = '') {
    echo translate($key, $default);
}

// Handle language change request
if (isset($_GET['lang']) && ($_GET['lang'] === 'en' || $_GET['lang'] === 'rw'|| $_GET['lang'] === 'fr')) {
    setLanguage($_GET['lang']);
    
    // Redirect to remove query string
    $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?');
    header("Location: $redirectUrl");
    exit;
}
