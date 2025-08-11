<?php
/**
 * Immomig Contact Form API Handler
 * Handles contact form submissions for property inquiries
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register REST API endpoint for contact form
 */
function register_immomig_contact_endpoint() {
    register_rest_route('immomig/v1', '/contact', array(
        'methods' => 'POST',
        'callback' => 'handle_immomig_contact_submission',
        'permission_callback' => '__return_true',
        'args' => array(
            'contact_name' => array(
                'required' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => function($param, $request, $key) {
                    return !empty(trim($param)) && strlen(trim($param)) >= 2;
                }
            ),
            'contact_email' => array(
                'required' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => function($param, $request, $key) {
                    return !empty(trim($param)) && filter_var($param, FILTER_VALIDATE_EMAIL);
                }
            ),
            'contact_phone' => array(
                'required' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => function($param, $request, $key) {
                    // Check if phone number is only numbers with optional "+" at the beginning
                    return preg_match('/^\+?[0-9]+$/', trim($param));
                }
            ),
            'contact_message' => array(
                'required' => false,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
                'validate_callback' => function($param, $request, $key) {
                    return strlen(trim($param)) <= 1000; // Max 1000 characters
                }
            ),
            'property_id' => array(
                'required' => false,
                'type' => 'integer',
                'sanitize_callback' => 'absint',
                'validate_callback' => function($param, $request, $key) {
                    return $param > 0 && get_post($param);
                }
            ),
            'property_reference' => array(
                'required' => false,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field'
            ),
            'property_address' => array(
                'required' => false,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field'
            )
        )
    ));
}
add_action('rest_api_init', 'register_immomig_contact_endpoint');

/**
 * Handle contact form submission
 */
function handle_immomig_contact_submission($request) {
    error_log('=== CONTACT FORM SUBMISSION START ===');
    error_log('Raw request parameters: ' . json_encode($request->get_params()));
    
    // Get validated and sanitized data
    $name = $request->get_param('contact_name');
    $email = $request->get_param('contact_email');
    $phone = $request->get_param('contact_phone');
    $message = $request->get_param('contact_message');
    $property_id = $request->get_param('property_id');
    $property_reference = $request->get_param('property_reference');
    $property_address = $request->get_param('property_address');
    
    error_log('Extracted parameters:');
    error_log('- Name: ' . $name);
    error_log('- Email: ' . $email);
    error_log('- Phone: ' . $phone);
    error_log('- Message: ' . $message);
    error_log('- Property ID: ' . $property_id);
    error_log('- Property Reference: ' . $property_reference);

    // Additional validation
    $errors = array();

    // Name validation
    if (empty(trim($name)) || strlen(trim($name)) < 2) {
        $errors[] = 'Le nom doit contenir au moins 2 caractères.';
    }

    // Email validation
    if (empty(trim($email)) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'L\'adresse email n\'est pas valide.';
    }

    // Phone validation
    if (!preg_match('/^\+?[0-9]+$/', trim($phone))) {
        $errors[] = 'Le numéro de téléphone ne doit contenir que des chiffres sans espaces(avec un "+" optionnel au début).';
    }



    // Message validation (optional but if provided, check length)
    if (!empty($message) && strlen(trim($message)) > 1000) {
        $errors[] = 'Le message ne peut pas dépasser 1000 caractères.';
    }

    // If there are validation errors, return them
    if (!empty($errors)) {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Erreurs de validation',
            'errors' => $errors
        ), 400);
    }

    // Prepare data for logging/storage
    $contact_data = array(
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'message' => $message,
        'property_id' => $property_id,
        'property_reference' => $property_reference,
        'property_address' => $property_address,
        'submitted_at' => current_time('mysql'),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    );

    // For now, just log the data (you can extend this later)
    error_log('=== CONTACT FORM SUBMISSION START ===');
    error_log('Immomig Contact Form Submission: ' . json_encode($contact_data));

    // Send to Immomig API
    error_log('Calling send_to_immomig_api...');
    $immomig_response = send_to_immomig_api($contact_data);
    error_log('Immomig API Response: ' . json_encode($immomig_response));
    
    if ($immomig_response['success']) {
        return new WP_REST_Response(array(
            'success' => true,
            'message' => 'Votre message a été envoyé avec succès. Nous vous contacterons bientôt.',
            'data' => array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'message' => $message,
                'property_id' => $property_id,
                'property_reference' => $property_reference,
                'property_address' => $property_address,
                'submitted_at' => current_time('mysql'),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'immomig_response' => $immomig_response['data']
            )
        ), 200);
    } else {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Erreur lors de l\'envoi. Veuillez réessayer.',
            'errors' => array($immomig_response['error'])
        ), 500);
    }
}

/**
 * Add CORS headers for the API endpoint
 */
function add_immomig_cors_headers() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        status_header(200);
        exit();
    }
}

// Add CORS headers for our endpoint
add_action('init', function() {
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/wp-json/immomig/v1/contact') !== false) {
        add_immomig_cors_headers();
    }
}); 


/**
 * Immomig API Configuration
 */
define('IMMOMIG_BASE_URL', 'https://api2.myimmomig.com');

// Initialize API configuration after ACF is ready
function init_immomig_config() {
    if (function_exists('get_field')) {
        if (!defined('IMMOMIG_API_KEY')) {
            define('IMMOMIG_API_KEY', get_field('immomig_api_key', 'option') ?: '');
        }
        if (!defined('IMMOMIG_SHARED_SECRET')) {
            define('IMMOMIG_SHARED_SECRET', get_field('immomig_api_secret', 'option') ?: '');
        }
    } else {
        if (!defined('IMMOMIG_API_KEY')) {
            define('IMMOMIG_API_KEY', '');
        }
        if (!defined('IMMOMIG_SHARED_SECRET')) {
            define('IMMOMIG_SHARED_SECRET', '');
        }
    }
}
add_action('acf/init', 'init_immomig_config', 20); // Run with lower priority to avoid conflicts

/**
 * Get Immomig API token
 */
function get_immomig_token() {
    $url = IMMOMIG_BASE_URL . '/login';
    
    error_log('=== IMMOMIG TOKEN REQUEST START ===');
    error_log('URL: ' . $url);
    error_log('API Key: ' . IMMOMIG_API_KEY);
    error_log('Shared Secret: ' . substr(IMMOMIG_SHARED_SECRET, 0, 8) . '...');
    
    $body = array(
        'api_key' => IMMOMIG_API_KEY,
        'shared_secret' => IMMOMIG_SHARED_SECRET
    );
    
    error_log('Request Body: ' . json_encode($body));
    
    $response = wp_remote_post($url, array(
        'headers' => array(
            'Content-Type' => 'application/x-www-form-urlencoded'
        ),
        'body' => $body,
        'timeout' => 30
    ));
    
    if (is_wp_error($response)) {
        error_log('Immomig API Login Error: ' . $response->get_error_message());
        error_log('=== IMMOMIG TOKEN REQUEST END (ERROR) ===');
        return false;
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    $response_headers = wp_remote_retrieve_headers($response);
    $body = wp_remote_retrieve_body($response);
    
    error_log('Response Code: ' . $response_code);
    error_log('Response Headers: ' . json_encode($response_headers));
    error_log('Response Body: ' . $body);
    
    $data = json_decode($body, true);
    
    if (isset($data['token'])) {
        error_log('Token received successfully: ' . substr($data['token'], 0, 20) . '...');
        error_log('=== IMMOMIG TOKEN REQUEST END (SUCCESS) ===');
        return $data['token'];
    }
    
    error_log('Immomig API Login Error: No token received. Response: ' . $body);
    error_log('=== IMMOMIG TOKEN REQUEST END (NO TOKEN) ===');
    return false;
}

/**
 * Send contact request to Immomig API
 */
function send_to_immomig_api($contact_data) {
    error_log('=== IMMOMIG CONTACT REQUEST START ===');
    error_log('Original Contact Data: ' . json_encode($contact_data));
    
    // Using Basic Authentication with API key and shared secret
    error_log('Using Basic Authentication with API key');
    
    // Split name into firstname and lastname
    $name_parts = explode(' ', trim($contact_data['name']), 2);
    $firstname = $name_parts[0];
    $lastname = isset($name_parts[1]) ? $name_parts[1] : '';
    
    error_log('Name split - Firstname: ' . $firstname . ', Lastname: ' . $lastname);
    
    // Prepare contact data for Immomig API
    $immomig_data = array(
        'contact' => array(
            'contact_type' => 1,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'address' => $contact_data['property_address'] ?? '',
            'postalcode' => '',
            'city' => '',
            'country' => 'ch',
            'main_language' => 'fr',
            'email' => $contact_data['email'],
            'phone' => $contact_data['phone']
        ),
        'comment' => $contact_data['message'] ?? 'Demande d\'information via le site web'
    );
    
    // Add property reference as object if available
    if (!empty($contact_data['property_reference'])) {
        $immomig_data['object'] = array(
            'reference' => $contact_data['property_reference']
        );
    }
    
    error_log('Prepared Immomig Data: ' . json_encode($immomig_data));
    
    $url = IMMOMIG_BASE_URL . '/contacts/request';
    error_log('Contact Request URL: ' . $url);
    error_log('Using Basic Auth with API Key: ' . substr(IMMOMIG_API_KEY, 0, 8) . '...');
    
    $request_headers = array(
        'Content-Type' => 'application/json',
        'Authorization' => 'Basic ' . base64_encode(IMMOMIG_API_KEY . ':' . IMMOMIG_SHARED_SECRET)
    );
    
    error_log('Request Headers: ' . json_encode($request_headers));
    
    $request_body = json_encode($immomig_data);
    error_log('Request Body: ' . $request_body);
    
    $response = wp_remote_post($url, array(
        'headers' => $request_headers,
        'body' => $request_body,
        'timeout' => 30
    ));
    
    if (is_wp_error($response)) {
        error_log('Immomig API Contact Error: ' . $response->get_error_message());
        error_log('=== IMMOMIG CONTACT REQUEST END (WP ERROR) ===');
        return array(
            'success' => false,
            'error' => 'Erreur de communication avec l\'API Immomig'
        );
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    $response_headers = wp_remote_retrieve_headers($response);
    $body = wp_remote_retrieve_body($response);
    
    error_log('Response Code: ' . $response_code);
    error_log('Response Headers: ' . json_encode($response_headers));
    error_log('Response Body: ' . $body);
    
    $data = json_decode($body, true);
    
    if ($response_code === 200) {
        error_log('Contact request successful');
        error_log('=== IMMOMIG CONTACT REQUEST END (SUCCESS) ===');
        return array(
            'success' => true,
            'data' => $data
        );
    } else {
        error_log('Contact request failed with code: ' . $response_code);
        error_log('=== IMMOMIG CONTACT REQUEST END (FAILED) ===');
        return array(
            'success' => false,
            'error' => 'Erreur lors de l\'envoi à l\'API Immomig (Code: ' . $response_code . '): ' . $body
        );
    }
}