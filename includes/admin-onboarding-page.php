<?php
/**
 * KnittNet → Onboarding admin page (linear wizard).
 *
 * Plan: plan-knittnet-20260527-905439 — SUPERSEDES the 704e25 checklist UI.
 *
 * Five-step wizard:
 *   1. Choose chat provider + model + API key
 *   2. Choose embedding provider + model + API key (dedup if same as chat)
 *   3. Seed the knowledge base (live-poll the KB row count)
 *   4. Try Actions (optional)
 *   5. Congrats + PRO add-ons (auto-graduates the menu item)
 *
 * Preserved from plan-f7c7d4: menu item, "Onboarding" naming, dismiss button,
 * auto-graduate via knittnet_onboarding_dismissed, 301 from old slug, Stuck?
 * Get help footer URLs, brand tokens, responsive 1280x800 / 390x844 quality bar.
 *
 * State storage:
 *   knittnet_onboarding_progress   — assoc array { chat_model, embedding_model,
 *                                  knowledge_base, actions } booleans. Drives
 *                                  resume-where-you-left-off on reload.
 *   knittnet_onboarding_dismissed  — reused from f7c7d4. Set when the user
 *                                  lands on Step 5 (auto-graduate) OR clicks
 *                                  Dismiss anywhere.
 *   knittnet_onboarding_auto_graduated — companion flag from f7c7d4; set when
 *                                  graduation was automatic rather than
 *                                  user-initiated.
 *   Provider/model/key options are the SAME ones Settings already uses
 *   (knittnet_options sub-keys: api_key for OpenAI, claude_api_key,
 *   xai_api_key, deepseek_api_key, gemini_api_key, openrouter_api_key,
 *   voyage_api_key, custom_provider_api_key + perplexity_api_key if/when
 *   that provider lands). No parallel key storage.
 *
 * Admin-chrome surgery (Onboarding page only): adds body class
 * `knittnet-onboarding-focused` from class-knittnet-admin.php's admin_body_class
 * hook so admin-onboarding-wizard.css can collapse the WP admin sidebar and
 * suppress the bottom dashboard cards on THIS page only.
 *
 * @package KnittNet
 * @since   3.2.7
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Read the persistent onboarding-dismissed flag.
 */
function knittnet_onboarding_is_dismissed() {
    return (bool) get_option('knittnet_onboarding_dismissed', 0);
}

/**
 * Was the dismiss caused by auto-graduation rather than a user click?
 */
function knittnet_onboarding_is_auto_graduated() {
    return (bool) get_option('knittnet_onboarding_auto_graduated', 0);
}

/**
 * Read the wizard progress state. Returns the four step booleans.
 */
function knittnet_onboarding_get_progress() {
    $defaults = array(
        'chat_model'      => false,
        'behavior'        => false,
        'embedding_model' => false,
        'knowledge_base'  => false,
        'actions'         => false,
    );
    $stored = get_option('knittnet_onboarding_progress', array());
    if (!is_array($stored)) {
        $stored = array();
    }
    $out = array();
    foreach ($defaults as $k => $v) {
        $out[$k] = isset($stored[$k]) ? (bool) $stored[$k] : $v;
    }
    return $out;
}

/**
 * Update one or more wizard progress flags. Merges into the stored array.
 */
function knittnet_onboarding_set_progress($changes) {
    $current = knittnet_onboarding_get_progress();
    foreach ((array) $changes as $k => $v) {
        if (array_key_exists($k, $current)) {
            $current[$k] = (bool) $v;
        }
    }
    update_option('knittnet_onboarding_progress', $current);
    return $current;
}

/**
 * Existing AJAX: user-initiated dismiss. Preserved verbatim from f7c7d4.
 */
add_action('wp_ajax_knittnet_dismiss_onboarding', 'knittnet_handle_dismiss_onboarding');
function knittnet_handle_dismiss_onboarding() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission to do this.', 'knittnet')), 403);
    }
    check_ajax_referer('knittnet_dismiss_onboarding', 'nonce');

    update_option('knittnet_onboarding_dismissed', 1);
    update_option('knittnet_onboarding_auto_graduated', 0);

    wp_send_json_success(array('dismissed' => true));
}

/**
 * admin_post: "Show KnittNet Onboarding again" — clears both dismissed flag
 * AND wizard progress so the user gets a fresh wizard. Preserved from f7c7d4
 * with the added progress-reset.
 */
add_action('admin_post_knittnet_unhide_onboarding', 'knittnet_handle_unhide_onboarding');
function knittnet_handle_unhide_onboarding() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to do this.', 'knittnet'));
    }
    check_admin_referer('knittnet_unhide_onboarding');

    delete_option('knittnet_onboarding_dismissed');
    delete_option('knittnet_onboarding_auto_graduated');

    wp_safe_redirect(admin_url('admin.php?page=knittnet-onboarding'));
    exit;
}

/**
 * admin_init: legacy slug 301 redirect (preserved from f7c7d4).
 */
add_action('admin_init', 'knittnet_onboarding_legacy_slug_redirect');
function knittnet_onboarding_legacy_slug_redirect() {
    if (!isset($_GET['page'])) {
        return;
    }
    if ($_GET['page'] !== 'knittnet-dashboard') {
        return;
    }
    wp_safe_redirect(admin_url('admin.php?page=knittnet-onboarding'), 301);
    exit;
}

/**
 * Provider catalog used by Steps 1 and 2. Wraps the canonical catalog in
 * includes/class-knittnet-model-catalog.php (plan-d14e89) so a single edit
 * there flows through to the Onboarding wizard's dropdowns. Shape preserved
 * for back-compat:
 *   [ slug => [ label, key_option, chat_models{}, embedding_models{} ] ]
 * Plus an extra `requires_key_to_load_models` flag per provider (true for
 * OpenRouter) so the wizard can disable that provider in its picker.
 */
function knittnet_onboarding_provider_catalog() {
    if (!class_exists('KnittNet_Model_Catalog')) {
        require_once plugin_dir_path(__FILE__) . 'class-knittnet-model-catalog.php';
    }
    $chat = KnittNet_Model_Catalog::chat_models();
    $emb  = KnittNet_Model_Catalog::embedding_models();

    $out = array();
    $all_slugs = array_unique(array_merge(array_keys($chat), array_keys($emb)));
    foreach ($all_slugs as $slug) {
        $chat_models  = array();
        $requires_key = false;
        $disable_onb  = false;
        if (isset($chat[$slug])) {
            foreach ($chat[$slug]['models'] as $id => $entry) {
                $chat_models[$id] = $entry['label'];
            }
            $requires_key = !empty($chat[$slug]['requires_key_to_load_models']);
            $disable_onb  = !empty($chat[$slug]['disable_in_onboarding']);
        }
        $embed_models = array();
        if (isset($emb[$slug])) {
            foreach ($emb[$slug]['models'] as $id => $entry) {
                $embed_models[$id] = $entry['label'];
            }
        }
        $out[$slug] = array(
            'label'                       => KnittNet_Model_Catalog::provider_label($slug),
            'key_option'                  => KnittNet_Model_Catalog::key_option_for_provider($slug),
            'chat_models'                 => $chat_models,
            'embedding_models'            => $embed_models,
            'requires_key_to_load_models' => $requires_key,
            'disable_in_onboarding'       => ($disable_onb || $requires_key),
        );
    }
    return $out;
}

/**
 * Infer the provider slug from a chat model id, mirroring the rule of thumb
 * the model picker uses. Returns 'openai' / 'claude' / 'gemini' / 'xai' /
 * 'deepseek' / 'openrouter' / 'custom' or '' if unknown.
 */
function knittnet_onboarding_provider_from_model($model_id) {
    $m = strtolower((string) $model_id);
    if ($m === '') return '';
    if (strpos($m, 'gpt-') === 0 || strpos($m, 'o1-') === 0 || strpos($m, 'o3-') === 0) return 'openai';
    if (strpos($m, 'claude-') === 0) return 'claude';
    if (strpos($m, 'gemini-') === 0) return 'gemini';
    if (strpos($m, 'grok') === 0) return 'xai';
    if (strpos($m, 'deepseek') === 0) return 'deepseek';
    if (strpos($m, 'sonar') === 0 || strpos($m, 'pplx-') === 0) return 'perplexity';
    if ($m === 'openrouter' || strpos($m, '/') !== false) return 'openrouter';
    if (strpos($m, 'custom') === 0) return 'custom';
    return '';
}

/**
 * Mirror for embedding models.
 */
function knittnet_onboarding_provider_from_embedding_model($model_id) {
    $m = strtolower((string) $model_id);
    if ($m === '') return '';
    if (strpos($m, 'text-embedding-') === 0) return 'openai';
    if (strpos($m, 'voyage') === 0) return 'voyage';
    if (strpos($m, 'gemini-embedding') === 0) return 'gemini';
    return '';
}

/**
 * Whether the given provider has a non-empty API key saved.
 */
function knittnet_onboarding_provider_has_key($provider_slug) {
    $catalog = knittnet_onboarding_provider_catalog();
    if (!isset($catalog[$provider_slug])) return false;
    $opts = get_option('knittnet_options', array());
    if (!is_array($opts)) $opts = array();
    $field = $catalog[$provider_slug]['key_option'];
    return !empty($opts[$field]) && trim((string) $opts[$field]) !== '';
}

/**
 * KB row count. The canonical KB content table is
 * {$wpdb->prefix}knittnet_system_prompt_content — see knittnet-basic.php:716
 * where the table is created. An earlier draft of this function queried
 * knittnet_embeddings (which doesn't exist on most installs), causing Step 3
 * to always report "No knowledge base items yet" even after items had been
 * added. Plan-d14e89 Issue 6.
 */
function knittnet_onboarding_kb_count() {
    global $wpdb;
    $table = $wpdb->prefix . 'knittnet_system_prompt_content';
    $exists = (bool) $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table));
    if (!$exists) return 0;
    return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table}");
}

/**
 * AJAX: live KB status poll for Step 3. Returns { count: N }.
 * Nonce-protected per plan spec (use per-request nonce path from 6a68c9 —
 * here the nonce is issued fresh in the page renderer, NOT cached, since
 * admin pages aren't page-cached).
 */
add_action('wp_ajax_knittnet_onboarding_kb_status', 'knittnet_onboarding_ajax_kb_status');
function knittnet_onboarding_ajax_kb_status() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission.', 'knittnet')), 403);
    }
    check_ajax_referer('knittnet_onboarding_nonce', 'nonce');
    wp_send_json_success(array('count' => knittnet_onboarding_kb_count()));
}

/**
 * AJAX: save Step 1 (chat) or Step 2 (embedding) selection. Persists
 * provider/model/key into the SAME knittnet_options sub-keys Settings uses
 * (no parallel storage), then flips the corresponding progress flag.
 *
 * Expected POST: which=chat|embedding, provider=<slug>, model=<id>,
 * api_key=<string optional — only set if a fresh key was entered>.
 */
add_action('wp_ajax_knittnet_onboarding_save_step', 'knittnet_onboarding_ajax_save_step');
function knittnet_onboarding_ajax_save_step() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission.', 'knittnet')), 403);
    }
    check_ajax_referer('knittnet_onboarding_nonce', 'nonce');

    $which    = isset($_POST['which']) ? sanitize_key($_POST['which']) : '';

    // Behavior step branch (plan-a2e4d6) — saves system_prompt_instructions
    // into the SAME knittnet_options sub-key Settings uses, no parallel storage.
    if ($which === 'behavior') {
        $instructions = isset($_POST['system_prompt_instructions'])
            ? sanitize_textarea_field(wp_unslash($_POST['system_prompt_instructions']))
            : '';
        $opts = get_option('knittnet_options', array());
        if (!is_array($opts)) $opts = array();
        $opts['system_prompt_instructions'] = $instructions;
        update_option('knittnet_options', $opts);
        knittnet_onboarding_set_progress(array('behavior' => true));
        wp_send_json_success(array(
            'progress' => knittnet_onboarding_get_progress(),
        ));
    }

    $provider = isset($_POST['provider']) ? sanitize_key($_POST['provider']) : '';
    $model    = isset($_POST['model']) ? sanitize_text_field(wp_unslash($_POST['model'])) : '';
    $api_key  = isset($_POST['api_key']) ? sanitize_text_field(wp_unslash($_POST['api_key'])) : '';

    $catalog = knittnet_onboarding_provider_catalog();
    if (!in_array($which, array('chat', 'embedding'), true)) {
        wp_send_json_error(array('message' => __('Invalid step.', 'knittnet')), 400);
    }
    if (!isset($catalog[$provider])) {
        wp_send_json_error(array('message' => __('Unknown provider.', 'knittnet')), 400);
    }
    $model_list = $which === 'chat' ? $catalog[$provider]['chat_models'] : $catalog[$provider]['embedding_models'];
    if (!isset($model_list[$model])) {
        wp_send_json_error(array('message' => __('Model not valid for this provider.', 'knittnet')), 400);
    }

    $opts = get_option('knittnet_options', array());
    if (!is_array($opts)) $opts = array();

    // Persist the key if a fresh one was sent. Empty string means "no change"
    // (the UI sends '' when the user is reusing an already-saved key).
    if ($api_key !== '') {
        $opts[$catalog[$provider]['key_option']] = $api_key;
    }

    if ($which === 'chat') {
        $opts['model'] = $model;
    } else {
        $opts['embedding_model'] = $model;
    }
    update_option('knittnet_options', $opts);

    $progress_key = $which === 'chat' ? 'chat_model' : 'embedding_model';
    knittnet_onboarding_set_progress(array($progress_key => true));

    wp_send_json_success(array(
        'progress' => knittnet_onboarding_get_progress(),
    ));
}

/**
 * AJAX: mark a step done (used by Step 4 actions Skip/Continue, and Step 3
 * Continue once KB is populated). No-op if the step name is unknown.
 */
add_action('wp_ajax_knittnet_onboarding_mark_step', 'knittnet_onboarding_ajax_mark_step');
function knittnet_onboarding_ajax_mark_step() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission.', 'knittnet')), 403);
    }
    check_ajax_referer('knittnet_onboarding_nonce', 'nonce');

    $step  = isset($_POST['step']) ? sanitize_key($_POST['step']) : '';
    $valid = array('chat_model', 'behavior', 'embedding_model', 'knowledge_base', 'actions');
    if (!in_array($step, $valid, true)) {
        wp_send_json_error(array('message' => __('Unknown step.', 'knittnet')), 400);
    }
    knittnet_onboarding_set_progress(array($step => true));
    wp_send_json_success(array('progress' => knittnet_onboarding_get_progress()));
}

/**
 * AJAX: auto-graduate on Step 5 land. Sets dismissed + auto_graduated flags
 * (same flags f7c7d4 used) so the menu item disappears on next admin nav.
 */
add_action('wp_ajax_knittnet_onboarding_auto_graduate', 'knittnet_onboarding_ajax_auto_graduate');
function knittnet_onboarding_ajax_auto_graduate() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission.', 'knittnet')), 403);
    }
    check_ajax_referer('knittnet_onboarding_nonce', 'nonce');

    update_option('knittnet_onboarding_dismissed', 1);
    update_option('knittnet_onboarding_auto_graduated', 1);
    wp_send_json_success(array('graduated' => true));
}

/**
 * Render the Onboarding page — wizard layout. Entry point wired from
 * class-knittnet-admin.php.
 */
function knittnet_render_onboarding_page() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to access this page.', 'knittnet'));
    }

    $opts = get_option('knittnet_options', array());
    if (!is_array($opts)) $opts = array();

    $progress = knittnet_onboarding_get_progress();
    $catalog  = knittnet_onboarding_provider_catalog();

    // Compute saved-key state per provider so the JS can short-circuit the
    // API-key input to the "already saved" checkmark on initial render.
    $key_state = array();
    foreach ($catalog as $slug => $entry) {
        $key_state[$slug] = knittnet_onboarding_provider_has_key($slug);
    }

    // Current selections (pre-populate the wizard if user already had values
    // in Settings before opening Onboarding).
    $current_chat_model      = isset($opts['model']) ? (string) $opts['model'] : '';
    $current_chat_provider   = knittnet_onboarding_provider_from_model($current_chat_model);
    $current_embed_model     = isset($opts['embedding_model']) ? (string) $opts['embedding_model'] : '';
    $current_embed_provider  = knittnet_onboarding_provider_from_embedding_model($current_embed_model);

    // Pre-credit logic — run BEFORE deciding the initial step so the landing
    // step reflects pre-existing Settings values. The wizard is six steps now
    // (plan-a2e4d6 inserted "AI Behavior" at position 2):
    //   1. Chat model    → progress.chat_model
    //   2. AI Behavior   → progress.behavior  (NEW)
    //   3. Embedding     → progress.embedding_model
    //   4. Knowledge base→ progress.knowledge_base
    //   5. Actions       → progress.actions
    //   6. Congrats      → no progress flag (all-five-complete view)

    // Pre-credit Step 1 (chat) if a chat model + key are already saved.
    if (!$progress['chat_model'] && $current_chat_model !== '' && $current_chat_provider !== '' && !empty($key_state[$current_chat_provider])) {
        knittnet_onboarding_set_progress(array('chat_model' => true));
        $progress['chat_model'] = true;
    }
    // Pre-credit Step 2 (behavior) if system_prompt_instructions is non-empty
    // — the user already set this in Settings before opening Onboarding.
    $current_instructions = isset($opts['system_prompt_instructions']) ? (string) $opts['system_prompt_instructions'] : '';
    if (!$progress['behavior'] && trim($current_instructions) !== '') {
        knittnet_onboarding_set_progress(array('behavior' => true));
        $progress['behavior'] = true;
    }
    // Pre-credit Step 3 (embedding) if model + key already saved.
    if (!$progress['embedding_model'] && $current_embed_model !== '' && $current_embed_provider !== '' && !empty($key_state[$current_embed_provider])) {
        knittnet_onboarding_set_progress(array('embedding_model' => true));
        $progress['embedding_model'] = true;
    }
    // Pre-credit Step 4 (KB) if rows already exist.
    if (!$progress['knowledge_base'] && knittnet_onboarding_kb_count() > 0) {
        knittnet_onboarding_set_progress(array('knowledge_base' => true));
        $progress['knowledge_base'] = true;
    }

    // First-not-done step is where we land on load. With all five flags true,
    // we land on Step 6 (Congrats).
    if (!$progress['chat_model'])           $initial_step = 1;
    elseif (!$progress['behavior'])         $initial_step = 2;
    elseif (!$progress['embedding_model'])  $initial_step = 3;
    elseif (!$progress['knowledge_base'])   $initial_step = 4;
    elseif (!$progress['actions'])          $initial_step = 5;
    else                                    $initial_step = 6;

    $plugin_url    = plugin_dir_url(dirname(__FILE__));
    $dismiss_nonce = wp_create_nonce('knittnet_dismiss_onboarding');
    $wizard_nonce  = wp_create_nonce('knittnet_onboarding_nonce');

    $current_slug = isset($_GET['page']) ? sanitize_key($_GET['page']) : 'knittnet-onboarding';
    $nav_items = array(
        array('slug' => 'knittnet-onboarding',  'label' => __('Onboarding',      'knittnet'), 'svg' => '<polyline points="3 9 12 2 21 9 21 21 14 21 14 14 10 14 10 21 3 21 3 9"/>'),
        array('slug' => 'knittnet-settings',    'label' => __('Settings',        'knittnet'), 'svg' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.01a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>'),
        array('slug' => 'knittnet-prompts',     'label' => __('Knowledge',       'knittnet'), 'svg' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>'),
        array('slug' => 'knittnet-transcripts', 'label' => __('Transcripts',     'knittnet'), 'svg' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'),
        array('slug' => 'knittnet-actions',     'label' => __('Actions',         'knittnet'), 'svg' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>'),
        array('slug' => 'knittnet-content',     'label' => __('Content',         'knittnet'), 'svg' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>'),
        array('slug' => 'knittnet-api-access',  'label' => __('API Access',      'knittnet'), 'svg' => '<path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/>'),
        array('slug' => 'knittnet-activation',  'label' => __('Pro & Extensions','knittnet'), 'svg' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>'),
    );

    // Build a JS-shaped catalog for the wizard's client-side step machine.
    $js_catalog = array();
    foreach ($catalog as $slug => $entry) {
        $js_catalog[$slug] = array(
            'label'                   => $entry['label'],
            'chatModels'              => $entry['chat_models'],
            'embeddingModels'         => $entry['embedding_models'],
            'hasKey'                  => $key_state[$slug],
            'requiresKeyToLoadModels' => !empty($entry['requires_key_to_load_models']),
        );
    }

    // Step metadata for the clickable pill indicator (plan-a2e4d6). Each entry:
    //   - id:      progress flag this step gates (null for Congrats)
    //   - label:   short pill label
    //   - title:   full step heading (also exposed for the legacy "Step N of M"
    //              text-fallback)
    $steps_meta = array(
        array('num' => 1, 'flag' => 'chat_model',      'label' => __('Chat model',  'knittnet'), 'title' => __('Choose your chat model',           'knittnet')),
        array('num' => 2, 'flag' => 'behavior',        'label' => __('Behavior',    'knittnet'), 'title' => __('Tell your chatbot how to behave',  'knittnet')),
        array('num' => 3, 'flag' => 'embedding_model', 'label' => __('Embedding',   'knittnet'), 'title' => __('Choose your embedding model',      'knittnet')),
        array('num' => 4, 'flag' => 'knowledge_base',  'label' => __('Knowledge',   'knittnet'), 'title' => __('Add to your knowledge base',       'knittnet')),
        array('num' => 5, 'flag' => 'actions',         'label' => __('Actions',     'knittnet'), 'title' => __('Try KnittNet Actions',               'knittnet')),
        array('num' => 6, 'flag' => null,              'label' => __('Done',        'knittnet'), 'title' => __('You\'re set up',                   'knittnet')),
    );

    $wizard_config = array(
        'ajaxUrl'             => admin_url('admin-ajax.php'),
        'nonce'               => $wizard_nonce,
        'catalog'             => $js_catalog,
        'initialStep'         => (int) $initial_step,
        'progress'            => $progress,
        'currentChatProvider' => $current_chat_provider,
        'currentChatModel'    => $current_chat_model,
        'currentEmbedProvider'=> $current_embed_provider,
        'currentEmbedModel'   => $current_embed_model,
        'currentInstructions' => $current_instructions,
        'kbCount'             => knittnet_onboarding_kb_count(),
        'stepsMeta'           => $steps_meta,
        'urls'                => array(
            'kb'                 => admin_url('admin.php?page=knittnet-prompts'),
            'actions'            => admin_url('admin.php?page=knittnet-actions'),
            'apiKeys'            => admin_url('admin.php?page=knittnet-settings&tab=api-keys'),
            'homepage'           => home_url('/'),
            'proAddons'          => 'https://knittnet.ai/add-ons/',
            'settingsTesting'    => admin_url('admin.php?page=knittnet-settings#testing'),
            'settingsDisplay'    => admin_url('admin.php?page=knittnet-settings#chatbot-display'),
        ),
        'strings'             => array(
            'keyAlreadySaved'   => __('%s API key already saved.', 'knittnet'),
            'embedKeyReused'    => __('You\'ve already added your %s API key — using it for embeddings too.', 'knittnet'),
            'replaceKey'        => __('Replace key', 'knittnet'),
            'saveKey'           => __('Save key', 'knittnet'),
            'saving'            => __('Saving…', 'knittnet'),
            'saved'             => __('Saved', 'knittnet'),
            'saveError'         => __('Save failed. Try again or set the key in Settings → API Keys.', 'knittnet'),
            'kbNone'            => __('No knowledge base items yet.', 'knittnet'),
            /* translators: %d = number of KB items */
            'kbFoundOne'        => __('Found 1 knowledge base item.', 'knittnet'),
            /* translators: %d = number of KB items */
            'kbFoundMany'       => __('Found %d knowledge base items.', 'knittnet'),
            'manageAllKeys'     => __('You can also manage all your keys later in API Keys settings.', 'knittnet'),
            'selectProvider'    => __('— Select a provider —', 'knittnet'),
            'selectModel'       => __('— Select a model —', 'knittnet'),
            'disabledHintOpenrouter' => __('OpenRouter requires setup in the main Settings page — finish onboarding, then configure it there.', 'knittnet'),
            'disabledHintCustom'     => __('Custom (OpenAI-compatible) providers need a Base URL and model configured in Settings → API Keys — finish onboarding, then set it up there.', 'knittnet'),
            'shortcodeCopied'        => __('Copied!', 'knittnet'),
            'shortcodeCopy'          => __('Copy', 'knittnet'),
        ),
    );
    ?>
    <div class="knet-onboarding-wrapper knet-onboarding-wizard-wrapper">

        <main class="knet-content">

            <div class="knet-section active">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Onboarding', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle">
                        <?php esc_html_e('Six quick steps to get your KnittNet chatbot live.', 'knittnet'); ?>
                    </p>
                </div>

                <div class="knet-card knet-wizard-card" id="knet-onboarding-wizard">
                    <div class="knet-wizard-progress" aria-hidden="false">
                        <!-- Clickable pill step indicator (plan-a2e4d6). The JS attaches
                             click handlers + sets aria-current="step" on the active pill +
                             toggles .is-complete / .is-current / .is-future as the user
                             advances. Future steps are non-clickable. -->
                        <nav class="knet-wizard-pillnav" role="tablist" aria-label="<?php esc_attr_e('Onboarding steps', 'knittnet'); ?>">
                            <?php foreach ($steps_meta as $sm):
                                $is_complete = ($sm['flag'] !== null && !empty($progress[$sm['flag']]))
                                              || ($sm['flag'] === null && (int) $initial_step >= 6);
                                $is_current  = ((int) $initial_step === (int) $sm['num']);
                                $cls = 'knet-wizard-pill';
                                if ($is_complete && !$is_current) $cls .= ' is-complete';
                                if ($is_current) $cls .= ' is-current';
                                if (!$is_complete && !$is_current) $cls .= ' is-future';
                                ?>
                                <button type="button"
                                        class="<?php echo esc_attr($cls); ?>"
                                        data-knet-pill-step="<?php echo (int) $sm['num']; ?>"
                                        <?php if ($is_current) echo 'aria-current="step"'; ?>
                                        <?php if (!$is_complete && !$is_current) echo 'disabled aria-disabled="true"'; ?>>
                                    <span class="knet-wizard-pill-num" aria-hidden="true">
                                        <svg class="knet-wizard-pill-check" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span class="knet-wizard-pill-num-text"><?php echo (int) $sm['num']; ?></span>
                                    </span>
                                    <span class="knet-wizard-pill-label"><?php echo esc_html($sm['label']); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </nav>
                        <div class="knet-wizard-progress-label">
                            <span class="knet-wizard-step-label"><?php esc_html_e('Step', 'knittnet'); ?> <span data-knet-current-step>1</span> <?php esc_html_e('of', 'knittnet'); ?> 6</span>
                            <span class="knet-wizard-step-name" data-knet-step-name></span>
                        </div>
                        <div class="knet-wizard-progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                            <div class="knet-wizard-progress-fill" data-knet-progress-fill></div>
                        </div>
                    </div>

                    <!-- STEP 1 — chat provider + model + key -->
                    <section class="knet-wizard-step" data-step="1" hidden>
                        <h2 class="knet-wizard-step-title"><?php esc_html_e('Choose your chat model', 'knittnet'); ?></h2>
                        <p class="knet-wizard-step-explainer">
                            <?php esc_html_e('This is the AI that will write replies in your chatbot. Different providers have different strengths and pricing. You can change this any time.', 'knittnet'); ?>
                        </p>

                        <div class="knet-field">
                            <label class="knet-field-label" for="knet-wiz-chat-provider"><?php esc_html_e('Provider', 'knittnet'); ?></label>
                            <select class="knet-wiz-select" id="knet-wiz-chat-provider" data-knet-which="chat" data-knet-role="provider">
                                <option value=""><?php echo esc_html($wizard_config['strings']['selectProvider']); ?></option>
                                <?php foreach ($catalog as $slug => $entry):
                                    if (empty($entry['chat_models'])) continue;
                                    // Plan-23987f — OpenRouter (requires-key flow) AND
                                    // Custom (needs Base URL + model id in API Keys tab)
                                    // are both disabled in the wizard's static dropdown
                                    // via the unified `disable_in_onboarding` flag.
                                    $opt_disabled = !empty($entry['disable_in_onboarding']);
                                    $opt_label    = $entry['label'];
                                    if ($opt_disabled) {
                                        /* translators: %s = provider label, e.g. OpenRouter */
                                        $opt_label = sprintf(__('%s — configure in Settings', 'knittnet'), $entry['label']);
                                    }
                                    ?>
                                    <option value="<?php echo esc_attr($slug); ?>" data-knet-disabled-onboarding="<?php echo $opt_disabled ? '1' : '0'; ?>" <?php selected($current_chat_provider, $slug); ?><?php echo $opt_disabled ? ' disabled' : ''; ?>><?php echo esc_html($opt_label); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="knet-field-hint knet-wiz-disabled-provider-hint">
                                <?php esc_html_e('OpenRouter and Custom (OpenAI-compatible) providers need extra configuration — finish onboarding, then set them up in Settings → API Keys.', 'knittnet'); ?>
                            </p>
                        </div>

                        <div class="knet-field knet-wiz-model-field" data-knet-which="chat" hidden>
                            <label class="knet-field-label" for="knet-wiz-chat-model"><?php esc_html_e('Model', 'knittnet'); ?></label>
                            <select class="knet-wiz-select" id="knet-wiz-chat-model" data-knet-which="chat" data-knet-role="model"></select>
                        </div>

                        <div class="knet-field knet-wiz-key-field" data-knet-which="chat" hidden>
                            <label class="knet-field-label" data-knet-key-label></label>
                            <p class="knet-field-description"><?php esc_html_e('An API key is a password from your AI provider that lets KnittNet talk to them.', 'knittnet'); ?></p>
                            <div class="knet-wiz-key-row">
                                <input type="password" class="knet-wiz-key-input" data-knet-which="chat" autocomplete="new-password" data-lpignore="true" />
                                <button type="button" class="knet-btn knet-btn-primary knet-btn-sm knet-wiz-key-save" data-knet-which="chat"><?php echo esc_html($wizard_config['strings']['saveKey']); ?></button>
                            </div>
                            <p class="knet-field-hint"><?php echo wp_kses_post(sprintf(
                                /* translators: %s = link to API Keys settings */
                                __('You can also manage all your keys later in %s.', 'knittnet'),
                                '<a href="' . esc_url($wizard_config['urls']['apiKeys']) . '">' . esc_html__('API Keys settings', 'knittnet') . '</a>'
                            )); ?></p>
                        </div>

                        <div class="knet-wiz-key-saved" data-knet-which="chat" hidden>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <span data-knet-key-saved-text></span>
                            <button type="button" class="knet-wiz-replace-key-link" data-knet-which="chat"><?php echo esc_html($wizard_config['strings']['replaceKey']); ?></button>
                        </div>

                        <div class="knet-wiz-error" data-knet-which="chat" hidden></div>

                        <div class="knet-wizard-actions">
                            <button type="button" class="knet-btn knet-btn-primary knet-wiz-continue" data-knet-from-step="1" disabled><?php esc_html_e('Continue', 'knittnet'); ?></button>
                        </div>
                    </section>

                    <!-- STEP 2 — AI Behavior (system_prompt_instructions) — plan-a2e4d6.
                         Re-uses the SAME knittnet_options['system_prompt_instructions']
                         binding Settings → Behavior uses. No parallel storage. The
                         Sample Instructions modal is rendered once at the bottom of
                         this page (after the wizard card) and shares the same DOM
                         ids (knittnetViewSampleBtn / knittnetSampleModal) so the existing
                         modal CSS in admin-style.css just works. -->
                    <section class="knet-wizard-step" data-step="2" hidden>
                        <h2 class="knet-wizard-step-title"><?php esc_html_e('Tell your chatbot how to behave', 'knittnet'); ?></h2>
                        <p class="knet-wizard-step-explainer">
                            <?php esc_html_e('These are the instructions your AI follows for every reply. Think of it as the chatbot\'s job description — what role it plays, what tone it uses, what topics it stays on or avoids. You can edit this any time from Settings → Behavior.', 'knittnet'); ?>
                        </p>

                        <div class="knet-field">
                            <label class="knet-field-label" for="knet-wiz-behavior-textarea"><?php esc_html_e('AI Instructions', 'knittnet'); ?></label>
                            <textarea id="knet-wiz-behavior-textarea" class="knet-wiz-behavior-textarea" rows="6" placeholder="<?php esc_attr_e('Describe how your chatbot should respond — its role, tone, and topics to focus on or avoid.', 'knittnet'); ?>"><?php echo esc_textarea($current_instructions); ?></textarea>
                            <p class="knet-field-hint">
                                <?php esc_html_e('Optional — leave blank to use sensible defaults. You can refine this later.', 'knittnet'); ?>
                            </p>
                        </div>

                        <div class="knet-wiz-behavior-actions-row">
                            <button type="button" class="knittnet-instructions-btn" id="knittnetViewSampleBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                </svg>
                                <?php esc_html_e('View Sample Instructions', 'knittnet'); ?>
                            </button>
                        </div>

                        <p class="knet-wiz-behavior-examples-label">
                            <?php esc_html_e('Or start from one of these:', 'knittnet'); ?>
                        </p>
                        <div class="knet-wiz-behavior-examples">
                            <button type="button" class="knet-wiz-behavior-example" data-knet-example="helpful">
                                <span class="knet-wiz-behavior-example-title"><?php esc_html_e('Helpful website assistant', 'knittnet'); ?></span>
                                <span class="knet-wiz-behavior-example-desc"><?php esc_html_e('General-purpose, concise, sticks to your site\'s content.', 'knittnet'); ?></span>
                            </button>
                            <button type="button" class="knet-wiz-behavior-example" data-knet-example="sales">
                                <span class="knet-wiz-behavior-example-title"><?php esc_html_e('Sales-focused product expert', 'knittnet'); ?></span>
                                <span class="knet-wiz-behavior-example-desc"><?php esc_html_e('Highlights features, suggests products, drives toward CTAs.', 'knittnet'); ?></span>
                            </button>
                            <button type="button" class="knet-wiz-behavior-example" data-knet-example="support">
                                <span class="knet-wiz-behavior-example-title"><?php esc_html_e('Friendly customer support agent', 'knittnet'); ?></span>
                                <span class="knet-wiz-behavior-example-desc"><?php esc_html_e('Patient, empathetic, troubleshoots, escalates when stuck.', 'knittnet'); ?></span>
                            </button>
                        </div>

                        <div class="knet-wiz-error" data-knet-which="behavior" hidden></div>

                        <div class="knet-wizard-actions">
                            <button type="button" class="knet-btn knet-btn-secondary knet-wiz-back" data-knet-from-step="2"><?php esc_html_e('Back', 'knittnet'); ?></button>
                            <button type="button" class="knet-btn knet-btn-primary knet-wiz-continue" data-knet-from-step="2"><?php esc_html_e('Continue', 'knittnet'); ?></button>
                        </div>
                    </section>

                    <!-- STEP 3 — embedding provider + model + (maybe) key -->
                    <section class="knet-wizard-step" data-step="3" hidden>
                        <h2 class="knet-wizard-step-title"><?php esc_html_e('Choose your embedding model', 'knittnet'); ?></h2>
                        <p class="knet-wizard-step-explainer">
                            <?php esc_html_e('Embeddings turn your website content and knowledge base into a format KnittNet can search through — this is what makes your chatbot "know" your stuff. You only need this if you\'ll use a knowledge base, but most users do.', 'knittnet'); ?>
                        </p>

                        <div class="knet-field">
                            <label class="knet-field-label" for="knet-wiz-embed-provider"><?php esc_html_e('Provider', 'knittnet'); ?></label>
                            <select class="knet-wiz-select" id="knet-wiz-embed-provider" data-knet-which="embedding" data-knet-role="provider">
                                <option value=""><?php echo esc_html($wizard_config['strings']['selectProvider']); ?></option>
                                <?php foreach ($catalog as $slug => $entry):
                                    if (empty($entry['embedding_models'])) continue; ?>
                                    <option value="<?php echo esc_attr($slug); ?>" <?php selected($current_embed_provider, $slug); ?>><?php echo esc_html($entry['label']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="knet-field knet-wiz-model-field" data-knet-which="embedding" hidden>
                            <label class="knet-field-label" for="knet-wiz-embed-model"><?php esc_html_e('Model', 'knittnet'); ?></label>
                            <select class="knet-wiz-select" id="knet-wiz-embed-model" data-knet-which="embedding" data-knet-role="model"></select>
                        </div>

                        <!-- Dedup case: same provider as Step 1 + key already saved -->
                        <div class="knet-wiz-key-dedup" data-knet-which="embedding" hidden>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <span data-knet-dedup-text></span>
                        </div>

                        <div class="knet-field knet-wiz-key-field" data-knet-which="embedding" hidden>
                            <label class="knet-field-label" data-knet-key-label></label>
                            <p class="knet-field-description"><?php esc_html_e('An API key is a password from your AI provider that lets KnittNet talk to them.', 'knittnet'); ?></p>
                            <div class="knet-wiz-key-row">
                                <input type="password" class="knet-wiz-key-input" data-knet-which="embedding" autocomplete="new-password" data-lpignore="true" />
                                <button type="button" class="knet-btn knet-btn-primary knet-btn-sm knet-wiz-key-save" data-knet-which="embedding"><?php echo esc_html($wizard_config['strings']['saveKey']); ?></button>
                            </div>
                            <p class="knet-field-hint"><?php echo wp_kses_post(sprintf(
                                __('You can also manage all your keys later in %s.', 'knittnet'),
                                '<a href="' . esc_url($wizard_config['urls']['apiKeys']) . '">' . esc_html__('API Keys settings', 'knittnet') . '</a>'
                            )); ?></p>
                        </div>

                        <div class="knet-wiz-key-saved" data-knet-which="embedding" hidden>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <span data-knet-key-saved-text></span>
                            <button type="button" class="knet-wiz-replace-key-link" data-knet-which="embedding"><?php echo esc_html($wizard_config['strings']['replaceKey']); ?></button>
                        </div>

                        <div class="knet-wiz-error" data-knet-which="embedding" hidden></div>

                        <!-- Vector-store informational note (plan-23987f). Pinecone and
                             OpenAI Vector Store are alternative *databases* (not embedding
                             models) — configured under Knowledge after onboarding. -->
                        <div class="knet-wiz-vector-store-note">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo wp_kses_post(sprintf(
                                /* translators: %s = link to Knowledge page */
                                __('By default KnittNet stores embeddings in your WordPress database. After onboarding you can switch to %s for larger knowledge bases.', 'knittnet'),
                                '<a href="' . esc_url($wizard_config['urls']['kb']) . '" target="_blank" rel="noopener noreferrer">' . esc_html__('Pinecone or OpenAI Vector Store', 'knittnet') . '</a>'
                            )); ?></span>
                        </div>

                        <div class="knet-wizard-actions">
                            <button type="button" class="knet-btn knet-btn-secondary knet-wiz-back" data-knet-from-step="3"><?php esc_html_e('Back', 'knittnet'); ?></button>
                            <button type="button" class="knet-btn knet-btn-primary knet-wiz-continue" data-knet-from-step="3" disabled><?php esc_html_e('Continue', 'knittnet'); ?></button>
                        </div>
                    </section>

                    <!-- STEP 4 — KB seed (optional, mirrors Actions) -->
                    <section class="knet-wizard-step" data-step="4" hidden>
                        <h2 class="knet-wizard-step-title"><?php esc_html_e('Add something to your knowledge base', 'knittnet'); ?></h2>
                        <p class="knet-wizard-step-explainer">
                            <?php esc_html_e('Your knowledge base is the content KnittNet uses to answer questions about your site — pages, posts, PDFs, or custom text. Add some now if you\'d like, or skip this and build it later from the Knowledge page.', 'knittnet'); ?>
                        </p>

                        <div class="knet-wiz-cta-row">
                            <a class="knet-btn knet-btn-primary" href="<?php echo esc_url($wizard_config['urls']['kb']); ?>" target="_blank" rel="noopener noreferrer">
                                <?php esc_html_e('Open Knowledge Base', 'knittnet'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left:6px;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                        </div>

                        <div class="knet-wizard-actions">
                            <button type="button" class="knet-btn knet-btn-secondary knet-wiz-back" data-knet-from-step="4"><?php esc_html_e('Back', 'knittnet'); ?></button>
                            <button type="button" class="knet-btn knet-btn-secondary knet-wiz-continue" data-knet-from-step="4"><?php esc_html_e('I\'ll skip this for now — Continue', 'knittnet'); ?></button>
                        </div>
                    </section>

                    <!-- STEP 5 — Actions (optional) -->
                    <section class="knet-wizard-step" data-step="5" hidden>
                        <h2 class="knet-wizard-step-title"><?php esc_html_e('Try KnittNet Actions', 'knittnet'); ?></h2>
                        <p class="knet-wizard-step-explainer">
                            <?php esc_html_e('Actions let your chatbot do things, not just answer. When a visitor\'s message matches phrases you choose, KnittNet can capture their email to a Loops list, search the web and images with Brave, generate an image with OpenAI or Gemini, answer questions about a PDF you\'ve uploaded, or hand the conversation off to a human agent on Slack or Telegram. This step is optional — skip it now and set up Actions any time.', 'knittnet'); ?>
                        </p>

                        <div class="knet-wiz-cta-row">
                            <a class="knet-btn knet-btn-primary" href="<?php echo esc_url($wizard_config['urls']['actions']); ?>" target="_blank" rel="noopener noreferrer">
                                <?php esc_html_e('Open Actions', 'knittnet'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left:6px;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                        </div>

                        <div class="knet-wizard-actions">
                            <button type="button" class="knet-btn knet-btn-secondary knet-wiz-back" data-knet-from-step="5"><?php esc_html_e('Back', 'knittnet'); ?></button>
                            <button type="button" class="knet-btn knet-btn-secondary knet-wiz-continue" data-knet-from-step="5"><?php esc_html_e('I\'ll skip this for now — Continue', 'knittnet'); ?></button>
                        </div>
                    </section>

                    <!-- STEP 6 — Congrats (plan-23987f rebuild: 3 next-step cards) -->
                    <section class="knet-wizard-step knet-wizard-step-congrats" data-step="6" hidden>
                        <div class="knet-wiz-congrats-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <h2 class="knet-wizard-step-title knet-wizard-step-title-congrats">🎉 <?php esc_html_e('You\'re set up.', 'knittnet'); ?></h2>

                        <div class="knet-wiz-nextsteps-grid">

                            <!-- Card 1 — Test on Settings → Testing tab -->
                            <a class="knet-wiz-nextstep-card" href="<?php echo esc_url($wizard_config['urls']['settingsTesting']); ?>">
                                <div class="knet-wiz-nextstep-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                </div>
                                <h3 class="knet-wiz-nextstep-title"><?php esc_html_e('Test your chatbot', 'knittnet'); ?></h3>
                                <p class="knet-wiz-nextstep-desc"><?php esc_html_e('Try it right inside your dashboard — no need to visit your site.', 'knittnet'); ?></p>
                                <span class="knet-wiz-nextstep-cta"><?php esc_html_e('Open Testing tab', 'knittnet'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </span>
                            </a>

                            <!-- Card 2 — Show it on the site -->
                            <a class="knet-wiz-nextstep-card" href="<?php echo esc_url($wizard_config['urls']['settingsDisplay']); ?>">
                                <div class="knet-wiz-nextstep-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                </div>
                                <h3 class="knet-wiz-nextstep-title"><?php esc_html_e('Show it on your site', 'knittnet'); ?></h3>
                                <p class="knet-wiz-nextstep-desc"><?php esc_html_e('Turn the chatbot on for all pages, or hide it — your choice.', 'knittnet'); ?></p>
                                <span class="knet-wiz-nextstep-cta"><?php esc_html_e('Open Display settings', 'knittnet'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </span>
                            </a>

                            <!-- Card 3 — Embed code shortcodes -->
                            <div class="knet-wiz-nextstep-card knet-wiz-nextstep-card-embed">
                                <div class="knet-wiz-nextstep-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                                </div>
                                <h3 class="knet-wiz-nextstep-title"><?php esc_html_e('Or embed it manually', 'knittnet'); ?></h3>
                                <p class="knet-wiz-nextstep-desc"><?php esc_html_e('Prefer to place it yourself? Paste a shortcode anywhere.', 'knittnet'); ?></p>

                                <div class="knet-wiz-shortcode-row">
                                    <code class="knet-wiz-shortcode" data-knet-shortcode='[knittnet_chatbot floating="yes"]'>[knittnet_chatbot floating="yes"]</code>
                                    <button type="button" class="knet-wiz-shortcode-copy" data-knet-copy-shortcode='[knittnet_chatbot floating="yes"]' aria-label="<?php esc_attr_e('Copy floating widget shortcode', 'knittnet'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                        <span class="knet-wiz-shortcode-copy-text"><?php esc_html_e('Copy', 'knittnet'); ?></span>
                                    </button>
                                </div>
                                <p class="knet-wiz-shortcode-caption"><?php esc_html_e('Floating widget (bottom corner)', 'knittnet'); ?></p>

                                <div class="knet-wiz-shortcode-row">
                                    <code class="knet-wiz-shortcode" data-knet-shortcode='[knittnet_chatbot floating="no"]'>[knittnet_chatbot floating="no"]</code>
                                    <button type="button" class="knet-wiz-shortcode-copy" data-knet-copy-shortcode='[knittnet_chatbot floating="no"]' aria-label="<?php esc_attr_e('Copy inline widget shortcode', 'knittnet'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                        <span class="knet-wiz-shortcode-copy-text"><?php esc_html_e('Copy', 'knittnet'); ?></span>
                                    </button>
                                </div>
                                <p class="knet-wiz-shortcode-caption"><?php esc_html_e('Inline embedded chat', 'knittnet'); ?></p>
                            </div>

                        </div>

                        <!-- PRO add-ons conversion block (plan-9451be) -->
                        <a class="knet-wiz-pro-promo" href="<?php echo esc_url($wizard_config['urls']['proAddons']); ?>" target="_blank" rel="noopener noreferrer">
                            <span class="knet-wiz-pro-promo-badge"><?php esc_html_e('PRO add-ons', 'knittnet'); ?></span>
                            <div class="knet-wiz-pro-promo-body">
                                <h3 class="knet-wiz-pro-promo-title"><?php esc_html_e('Do more with PRO add-ons', 'knittnet'); ?></h3>
                                <p class="knet-wiz-pro-promo-desc">
                                    <?php esc_html_e('Sell directly in chat with WooCommerce, run multiple specialized bots on one site, generate full SEO-ready posts with Advanced Content, and more.', 'knittnet'); ?>
                                </p>
                            </div>
                            <span class="knet-wiz-pro-promo-cta">
                                <?php esc_html_e('Explore PRO add-ons', 'knittnet'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            </span>
                        </a>

                        <p class="knet-wiz-congrats-graduated" id="knet-wiz-graduated-line">
                            <?php
                            $knet_wiz_tutorials_url = esc_url(admin_url('admin.php?page=knittnet-settings#tutorials'));
                            printf(
                                /* translators: %s: bold link to "Settings → Tutorials" */
                                wp_kses(
                                    __('This setup guide moves out of the way once you\'re done — reopen it any time under %s.', 'knittnet'),
                                    array('strong' => array(), 'a' => array('href' => array()))
                                ),
                                '<strong><a href="' . $knet_wiz_tutorials_url . '">' . esc_html__('Settings → Tutorials', 'knittnet') . '</a></strong>'
                            );
                            ?>
                        </p>

                        <div class="knet-wiz-congrats-footer">
                            <button type="button" class="knet-wiz-congrats-footer-link knet-wiz-back" data-knet-from-step="6"><?php esc_html_e('Review earlier steps', 'knittnet'); ?></button>
                            <a class="knet-wiz-congrats-footer-link" href="<?php echo esc_url($wizard_config['urls']['homepage']); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Open my live site', 'knittnet'); ?></a>
                        </div>
                    </section>

                </div>

                <!-- Need help? — escape hatch (preserved from f7c7d4) -->
                <div class="knet-card knet-onboarding-help" id="knet-onboarding-help">
                    <div class="knet-card-body">
                        <div class="knet-help-row">
                            <div>
                                <h3 class="knet-help-title"><?php esc_html_e('Stuck? Get help.', 'knittnet'); ?></h3>
                                <p class="knet-help-subtitle"><?php esc_html_e('Ask the docs bot for an answer, or open a ticket if something\'s broken.', 'knittnet'); ?></p>
                            </div>
                            <div class="knet-help-buttons">
                                <a class="knet-btn knet-btn-secondary knet-btn-sm" href="https://knittnet.ai/documentation-bot/" target="_blank" rel="noopener noreferrer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                    <?php esc_html_e('Ask the docs bot', 'knittnet'); ?>
                                </a>
                                <a class="knet-btn knet-btn-secondary knet-btn-sm" href="https://wordpress.org/support/plugin/knittnet-basic/" target="_blank" rel="noopener noreferrer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;"><path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2v5Z"/><path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"/></svg>
                                    <?php esc_html_e('Open a support ticket', 'knittnet'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dismiss onboarding — bottom of page, ghost-styled, low visual weight
                     so users don't tap it by accident. Plan-d14e89 Issue 7. -->
                <p class="knet-onboarding-dismiss-row">
                    <button type="button"
                            class="knet-onboarding-dismiss-link knet-onboarding-dismiss"
                            data-knet-dismiss-nonce="<?php echo esc_attr($dismiss_nonce); ?>"
                            aria-describedby="knet-onboarding-dismiss-hint">
                        <?php esc_html_e('Dismiss onboarding', 'knittnet'); ?>
                    </button>
                    <span class="knet-onboarding-dismiss-hint" id="knet-onboarding-dismiss-hint">
                        <?php esc_html_e('Hide the setup guide from the menu — reopen it any time under Settings → Tutorials.', 'knittnet'); ?>
                    </span>
                </p>
            </div>

        </main>
    </div>

    <?php
    // Render the Sample Instructions modal markup once at the end of the page,
    // matching the Settings modal exactly (same DOM ids → same admin-style.css
    // styling + same UX expectations). admin-onboarding-wizard.js wires the
    // show/hide handlers locally so we don't need to enqueue the heavy
    // knittnet-admin.js on this page just for the modal.
    // Content kept verbatim with class-knittnet-admin.php::render_sample_instructions_modal()
    // so users see the same sample no matter where they invoked the modal from.
    ?>
    <div class="knittnet-instructions-modal-overlay" id="knittnetSampleModal">
        <div class="knittnet-instructions-modal-content">
            <div class="knittnet-instructions-modal-header">
                <h3 class="knittnet-instructions-modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                    <?php esc_html_e('Sample AI Instructions', 'knittnet'); ?>
                </h3>
                <button type="button" class="knittnet-instructions-modal-close" id="knittnetModalClose">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="knittnet-instructions-modal-body">
                <div class="knittnet-instructions-content"><?php echo esc_html('You are an AI Chatbot assistant for this website. Your main goal is to assist visitors with questions and provide helpful information. Here are your key guidelines:

# Response Style - CRITICALLY IMPORTANT
- MAXIMUM LENGTH: 1-3 short sentences per response
- Ultra-concise: Get straight to the answer with no filler
- No introductions like "Sure!" or "I\'d be happy to help"
- No phrases like "based on my knowledge" or "according to information"
- No explanatory text before giving the answer
- No summaries or repetition
- Hyperlink all URLs
- Respond in user\'s language
- Minor chit chat or conversation is okay, but try to keep it focused on [insert topic]

# Knowledge Base Requirements - PREVENT HALLUCINATIONS
- ONLY answer using information explicitly provided in OFFICIAL KNOWLEDGE DATABASE CONTENT sections marked with ===== delimiters
- If required information is NOT in the knowledge database: "I don\'t have enough information in my knowledge base to answer that question accurately."
- NEVER invent or hallucinate URLs, links, product specs, procedures, dates, statistics, names, contacts, or company information
- When knowledge base information is unclear or contradictory, acknowledge the limitation rather than guessing
- Better to admit insufficient information than provide inaccurate answers'); ?></div>
                <button type="button" class="knittnet-instructions-copy-btn" id="knittnetCopyBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                    </svg>
                    <?php esc_html_e('Copy to clipboard', 'knittnet'); ?>
                </button>
            </div>
        </div>
    </div>

    <script>
        window.KnittNetOnboardingWizard = <?php echo wp_json_encode($wizard_config); ?>;
    </script>
    <?php
}
