<?php
/**
 * KnittNet → API Access admin page.
 *
 * Lets the site owner generate, view, and revoke the bearer token used by
 * the REST endpoints in class-rest-api.php. The token is stored in
 * wp_options under `knittnet_api_token` and is empty by default — until the
 * owner clicks "Generate Token", all REST endpoints refuse with 401.
 *
 * @package KnittNet
 * @since   3.2.5
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('KNITTNET_API_TOKEN_OPTION')) {
    define('KNITTNET_API_TOKEN_OPTION', 'knittnet_api_token');
}

/**
 * Register the submenu under the existing KnittNet menu.
 * Priority 20 so it lands after the menu is already set up.
 */
add_action('admin_menu', 'knittnet_register_api_admin_page', 20);
function knittnet_register_api_admin_page() {
    add_submenu_page(
        'knittnet-max',
        esc_html__('KnittNet API Access', 'knittnet'),
        esc_html__('API Access', 'knittnet'),
        'manage_options',
        'knittnet-api-access',
        'knittnet_render_api_admin_page'
    );
}

/**
 * Generate a new token and store it.
 */
add_action('admin_post_knittnet_rotate_api_token', 'knittnet_handle_rotate_api_token');
function knittnet_handle_rotate_api_token() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to do this.', 'knittnet'));
    }
    check_admin_referer('knittnet_rotate_api_token');

    $token = wp_generate_password(48, false, false);
    update_option(KNITTNET_API_TOKEN_OPTION, $token, false);
    update_option('knittnet_api_token_rotated_at', time(), false);

    set_transient('knittnet_api_token_just_rotated', $token, 60);
    wp_safe_redirect(add_query_arg(array('page' => 'knittnet-api-access', 'rotated' => 1), admin_url('admin.php')));
    exit;
}

/**
 * Revoke the existing token (sets the option to empty string).
 */
add_action('admin_post_knittnet_revoke_api_token', 'knittnet_handle_revoke_api_token');
function knittnet_handle_revoke_api_token() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to do this.', 'knittnet'));
    }
    check_admin_referer('knittnet_revoke_api_token');

    update_option(KNITTNET_API_TOKEN_OPTION, '', false);
    delete_option('knittnet_api_token_rotated_at');

    wp_safe_redirect(add_query_arg(array('page' => 'knittnet-api-access', 'revoked' => 1), admin_url('admin.php')));
    exit;
}

/**
 * Render the API Access admin page.
 */
function knittnet_render_api_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to access this page.', 'knittnet'));
    }

    $stored_token  = (string) get_option(KNITTNET_API_TOKEN_OPTION, '');
    $token_set     = $stored_token !== '';
    $just_rotated  = get_transient('knittnet_api_token_just_rotated');
    delete_transient('knittnet_api_token_just_rotated');
    $rotated_query = isset($_GET['rotated']) ? (bool) $_GET['rotated'] : false;
    $revoked_query = isset($_GET['revoked']) ? (bool) $_GET['revoked'] : false;
    $rotated_at    = (int) get_option('knittnet_api_token_rotated_at', 0);

    $base_url      = trailingslashit(get_rest_url(null, 'knittnet/v1'));
    $health_url    = $base_url . 'health';
    $transcripts_u = $base_url . 'transcripts';
    $knowledge_url = $base_url . 'knowledge';

    $plugin_url    = plugin_dir_url(dirname(__FILE__));
    $masked        = $token_set
        ? substr($stored_token, 0, 4) . str_repeat('•', 24) . substr($stored_token, -4)
        : '';
    $rotated_human = $rotated_at > 0
        ? sprintf(
            /* translators: %s: human-readable elapsed time */
            esc_html__('%s ago', 'knittnet'),
            human_time_diff($rotated_at, current_time('timestamp'))
        )
        : esc_html__('Never', 'knittnet');
    ?>
    <div class="knet-admin-wrapper knet-api-wrapper">

        <!-- Mobile Header -->
        <header class="knet-mobile-header">
            <a href="#" class="knet-mobile-logo">
                <div class="knet-mobile-logo-icon">
                    <img src="<?php echo esc_url($plugin_url . 'images/icon-128x128.png'); ?>" alt="KnittNet">
                </div>
                <span class="knet-mobile-logo-text">KnittNet</span>
            </a>
            <button type="button" class="knet-mobile-menu-btn" aria-label="<?php esc_attr_e('Open menu', 'knittnet'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
            </button>
        </header>

        <!-- Mobile Menu Overlay -->
        <div class="knet-mobile-overlay"></div>

        <!-- Mobile Menu Modal -->
        <div class="knet-mobile-menu">
            <div class="knet-mobile-menu-header">
                <span class="knet-mobile-menu-title"><?php esc_html_e('API Access', 'knittnet'); ?></span>
                <button type="button" class="knet-mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'knittnet'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <nav class="knet-mobile-menu-nav">
                <div class="knet-mobile-nav-section">
                    <div class="knet-mobile-nav-section-title"><?php esc_html_e('REST API', 'knittnet'); ?></div>
                    <button class="knet-mobile-nav-link active" data-target="api-token">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                        <span><?php esc_html_e('Token', 'knittnet'); ?></span>
                    </button>
                    <button class="knet-mobile-nav-link" data-target="api-endpoints">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                        <span><?php esc_html_e('Endpoints', 'knittnet'); ?></span>
                    </button>
                    <button class="knet-mobile-nav-link" data-target="api-examples">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
                        <span><?php esc_html_e('Examples', 'knittnet'); ?></span>
                    </button>
                    <button class="knet-mobile-nav-link" data-target="api-privacy">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span><?php esc_html_e('Privacy', 'knittnet'); ?></span>
                    </button>
                </div>
            </nav>
        </div>

        <!-- Sidebar Navigation -->
        <aside class="knet-sidebar">
            <div class="knet-sidebar-header">
                <a href="#" class="knet-sidebar-logo">
                    <div class="knet-sidebar-logo-icon">
                        <img src="<?php echo esc_url($plugin_url . 'images/icon-128x128.png'); ?>" alt="KnittNet">
                    </div>
                    <span class="knet-sidebar-logo-text">KnittNet</span>
                    <span class="knet-sidebar-version">v<?php echo esc_html(KNITTNET_VERSION ?? '2.7.0'); ?></span>
                </a>
            </div>

            <nav class="knet-sidebar-nav">
                <div class="knet-nav-section">
                    <div class="knet-nav-section-title"><?php esc_html_e('REST API', 'knittnet'); ?></div>

                    <div class="knet-nav-item" data-section="api-token">
                        <button class="knet-nav-link active" data-target="api-token">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Token', 'knittnet'); ?></span>
                            <?php if ($token_set): ?>
                                <span class="knet-nav-link-badge knet-active-badge"><?php esc_html_e('Active', 'knittnet'); ?></span>
                            <?php endif; ?>
                        </button>
                    </div>

                    <div class="knet-nav-item" data-section="api-endpoints">
                        <button class="knet-nav-link" data-target="api-endpoints">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Endpoints', 'knittnet'); ?></span>
                        </button>
                    </div>

                    <div class="knet-nav-item" data-section="api-examples">
                        <button class="knet-nav-link" data-target="api-examples">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Examples', 'knittnet'); ?></span>
                        </button>
                    </div>

                    <div class="knet-nav-item" data-section="api-privacy">
                        <button class="knet-nav-link" data-target="api-privacy">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Privacy', 'knittnet'); ?></span>
                        </button>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="knet-content">

            <?php if ($rotated_query && $just_rotated): ?>
                <div class="notice notice-success is-dismissible knet-api-flash">
                    <p>
                        <strong><?php esc_html_e('New API token generated.', 'knittnet'); ?></strong>
                        <?php esc_html_e('Copy it now — for security, the full value will not be shown again after you leave this page.', 'knittnet'); ?>
                    </p>
                    <p>
                        <code class="knet-api-token-reveal" data-knet-copy-target><?php echo esc_html($just_rotated); ?></code>
                        <button type="button" class="knet-btn knet-btn-secondary knet-btn-sm knet-api-copy-btn" data-knet-copy="<?php echo esc_attr($just_rotated); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            <span><?php esc_html_e('Copy', 'knittnet'); ?></span>
                        </button>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($revoked_query): ?>
                <div class="notice notice-warning is-dismissible knet-api-flash">
                    <p>
                        <strong><?php esc_html_e('API token revoked.', 'knittnet'); ?></strong>
                        <?php esc_html_e('All REST endpoints will return 401 until a new token is generated.', 'knittnet'); ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Token Section -->
            <div id="api-token" class="knet-section active">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('REST API', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle">
                        <?php esc_html_e('Bearer-token-authenticated endpoints for reading transcripts and pushing content into the knowledge base. Disabled until you generate a token below.', 'knittnet'); ?>
                    </p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                            <?php esc_html_e('Token', 'knittnet'); ?>
                        </h3>
                        <?php if ($token_set): ?>
                            <span class="knet-status-pill knet-status-pill-active">
                                <span class="knet-status-dot"></span>
                                <?php esc_html_e('Active', 'knittnet'); ?>
                            </span>
                        <?php else: ?>
                            <span class="knet-status-pill knet-status-pill-disabled">
                                <span class="knet-status-dot"></span>
                                <?php esc_html_e('Disabled', 'knittnet'); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="knet-card-body">
                        <?php if ($token_set): ?>
                            <div class="knet-field">
                                <div class="knet-field-label"><?php esc_html_e('Current token', 'knittnet'); ?></div>
                                <code class="knet-api-token-masked"><?php echo esc_html($masked); ?></code>
                                <p class="knet-field-description">
                                    <?php esc_html_e('Last rotated:', 'knittnet'); ?>
                                    <strong><?php echo esc_html($rotated_human); ?></strong>
                                </p>
                            </div>
                        <?php else: ?>
                            <p class="knet-field-description">
                                <?php esc_html_e('No token is currently set. Generate one below to enable the authenticated REST endpoints.', 'knittnet'); ?>
                            </p>
                        <?php endif; ?>

                        <div class="knet-api-actions">
                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="knet-api-form">
                                <input type="hidden" name="action" value="knittnet_rotate_api_token" />
                                <?php wp_nonce_field('knittnet_rotate_api_token'); ?>
                                <button type="submit" class="knet-btn knet-btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                                    <?php echo $token_set ? esc_html__('Regenerate Token', 'knittnet') : esc_html__('Generate Token', 'knittnet'); ?>
                                </button>
                            </form>

                            <?php if ($token_set): ?>
                                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="knet-api-form"
                                      onsubmit="return confirm('<?php echo esc_js(__('Revoke the API token? Any external tool using the current token will stop working immediately.', 'knittnet')); ?>');">
                                    <input type="hidden" name="action" value="knittnet_revoke_api_token" />
                                    <?php wp_nonce_field('knittnet_revoke_api_token'); ?>
                                    <button type="submit" class="knet-btn knet-btn-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        <?php esc_html_e('Revoke Token', 'knittnet'); ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>

                        <p class="knet-field-hint">
                            <?php esc_html_e('Regenerating immediately invalidates the old token. Save the new value somewhere safe (password manager) — only the masked version is shown after you leave this page.', 'knittnet'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Endpoints Section -->
            <div id="api-endpoints" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Endpoints', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('All endpoints live under the WordPress REST namespace knittnet/v1.', 'knittnet'); ?></p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-body">
                        <table class="knet-api-endpoints-table">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Method', 'knittnet'); ?></th>
                                    <th><?php esc_html_e('URL', 'knittnet'); ?></th>
                                    <th><?php esc_html_e('Auth', 'knittnet'); ?></th>
                                    <th><?php esc_html_e('Purpose', 'knittnet'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="knet-api-method knet-api-method-get">GET</span></td>
                                    <td><code><?php echo esc_html($health_url); ?></code></td>
                                    <td><span class="knet-api-auth knet-api-auth-none"><?php esc_html_e('None', 'knittnet'); ?></span></td>
                                    <td><?php esc_html_e('Connectivity check; reports plugin version and whether a token is set.', 'knittnet'); ?></td>
                                </tr>
                                <tr>
                                    <td><span class="knet-api-method knet-api-method-get">GET</span></td>
                                    <td><code><?php echo esc_html($transcripts_u); ?></code></td>
                                    <td><span class="knet-api-auth knet-api-auth-bearer"><?php esc_html_e('Bearer', 'knittnet'); ?></span></td>
                                    <td><?php esc_html_e('Read chat transcripts. Filterable by since, until, session_id, role, has_rag_context; supports limit + offset pagination.', 'knittnet'); ?></td>
                                </tr>
                                <tr>
                                    <td><span class="knet-api-method knet-api-method-post">POST</span></td>
                                    <td><code><?php echo esc_html($knowledge_url); ?></code></td>
                                    <td><span class="knet-api-auth knet-api-auth-bearer"><?php esc_html_e('Bearer', 'knittnet'); ?></span></td>
                                    <td><?php esc_html_e('Push content into the knowledge base. Body: content, source_url, optional bot_id and content_type.', 'knittnet'); ?></td>
                                </tr>
                                <tr>
                                    <td><span class="knet-api-method knet-api-method-delete">DELETE</span></td>
                                    <td><code><?php echo esc_html($transcripts_u); ?></code></td>
                                    <td><span class="knet-api-auth knet-api-auth-bearer"><?php esc_html_e('Bearer', 'knittnet'); ?></span></td>
                                    <td><?php esc_html_e('Bulk-delete chat sessions by session_id, with optional cascade to translations and click-tracking. Capped at 1000 per call.', 'knittnet'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Examples Section -->
            <div id="api-examples" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Examples', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Drop-in cURL snippets — replace YOUR_TOKEN with the value from the Token tab.', 'knittnet'); ?></p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title"><?php esc_html_e('Health check', 'knittnet'); ?></h3>
                        <span class="knet-card-subtitle"><?php esc_html_e('No auth required', 'knittnet'); ?></span>
                    </div>
                    <div class="knet-card-body">
                        <pre class="knet-api-codeblock"><code>curl <?php echo esc_html($health_url); ?></code></pre>
                    </div>
                </div>

                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title"><?php esc_html_e('Find transcripts where the bot had no knowledge to ground answers', 'knittnet'); ?></h3>
                    </div>
                    <div class="knet-card-body">
                        <pre class="knet-api-codeblock"><code>curl -H "Authorization: Bearer YOUR_TOKEN" \
  "<?php echo esc_html($transcripts_u); ?>?role=user&amp;has_rag_context=no&amp;since=2026-05-01&amp;limit=50"</code></pre>
                    </div>
                </div>

                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title"><?php esc_html_e('Push a Q&A entry into the knowledge base', 'knittnet'); ?></h3>
                    </div>
                    <div class="knet-card-body">
                        <pre class="knet-api-codeblock"><code>curl -X POST -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"content":"Q: How do I enable streaming?\nA: ...","source_url":"https://example.com/faq#streaming","content_type":"faq"}' \
  "<?php echo esc_html($knowledge_url); ?>"</code></pre>
                    </div>
                </div>

                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title"><?php esc_html_e('Bulk-delete two chat sessions', 'knittnet'); ?></h3>
                    </div>
                    <div class="knet-card-body">
                        <pre class="knet-api-codeblock"><code>curl -X DELETE -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"session_ids":["sess_abc","sess_def"],"cascade":true}' \
  "<?php echo esc_html($transcripts_u); ?>"</code></pre>
                    </div>
                </div>
            </div>

            <!-- Privacy Section -->
            <div id="api-privacy" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Privacy & Safety', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('What the API exposes — and how to keep it safe.', 'knittnet'); ?></p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-body">
                        <ul class="knet-api-privacy-list">
                            <li>
                                <svg class="knet-api-bullet" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                <span><?php esc_html_e('No data leaves your site unsolicited. Endpoints only respond to requests carrying your token.', 'knittnet'); ?></span>
                            </li>
                            <li>
                                <svg class="knet-api-bullet" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                <span><?php esc_html_e('The /transcripts endpoint may return user-submitted chat data including emails and names — treat your API token as sensitive.', 'knittnet'); ?></span>
                            </li>
                            <li>
                                <svg class="knet-api-bullet" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                <span><?php esc_html_e('Use HTTPS only. Do not include the token in URL query strings.', 'knittnet'); ?></span>
                            </li>
                            <li>
                                <svg class="knet-api-bullet" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                <span><?php esc_html_e('Rotate the token if you suspect it was leaked. Old token is invalidated immediately.', 'knittnet'); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <style>
        /* Page-specific touches that build on admin-sidebar.css */
        .knet-api-flash { margin: 0 0 var(--knet-spacing-lg, 16px) 0; }
        .knet-api-token-reveal {
            display: inline-block;
            padding: 8px 12px;
            background: var(--knet-primary-lighter, #f0f6fc);
            border: 1px solid var(--knet-card-border, #c3c4c7);
            border-radius: var(--knet-radius-md, 6px);
            font-size: 13px;
            user-select: all;
            word-break: break-all;
            margin-right: 8px;
        }
        .knet-api-token-masked {
            display: inline-block;
            padding: 6px 10px;
            background: #f6f7f7;
            border: 1px solid var(--knet-card-border, #e2e4e9);
            border-radius: var(--knet-radius-sm, 4px);
            font-size: 13px;
            color: var(--knet-text-primary, #1d2327);
        }
        .knet-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }
        .knet-status-pill .knet-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        .knet-status-pill-active { background: #e6f4ea; color: #1d7a3a; }
        .knet-status-pill-active .knet-status-dot { background: #1d7a3a; }
        .knet-status-pill-disabled { background: #fef3e2; color: #a04a00; }
        .knet-status-pill-disabled .knet-status-dot { background: #a04a00; }

        .knet-api-actions { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 16px; }
        .knet-api-form { display: inline; margin: 0; }

        .knet-api-endpoints-table { width: 100%; border-collapse: collapse; }
        .knet-api-endpoints-table th,
        .knet-api-endpoints-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--knet-card-border, #e2e4e9);
            vertical-align: top;
            font-size: 13px;
        }
        .knet-api-endpoints-table th {
            font-weight: 600;
            color: var(--knet-text-secondary, #50575e);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 11px;
            background: #fafbfc;
        }
        .knet-api-endpoints-table tr:last-child td { border-bottom: none; }
        .knet-api-endpoints-table code {
            background: #f6f7f7;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 12px;
        }

        .knet-api-method {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .knet-api-method-get    { background: #e7f0fb; color: #1a56b4; }
        .knet-api-method-post   { background: #e6f4ea; color: #1d7a3a; }
        .knet-api-method-delete { background: #fce4e4; color: #b32020; }

        .knet-api-auth {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        .knet-api-auth-none   { background: #f0f0f0; color: #50575e; }
        .knet-api-auth-bearer { background: #fff4e0; color: #8a5a00; }

        .knet-api-codeblock {
            margin: 0;
            background: #1f2328;
            color: #e6edf3;
            padding: 14px 16px;
            border-radius: var(--knet-radius-md, 6px);
            overflow-x: auto;
            font-size: 12.5px;
            line-height: 1.55;
        }
        .knet-api-codeblock code { background: transparent; color: inherit; padding: 0; font-size: inherit; }

        .knet-api-privacy-list { list-style: none; margin: 0; padding: 0; }
        .knet-api-privacy-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            font-size: 14px;
            color: var(--knet-text-primary, #1d2327);
            line-height: 1.5;
        }
        .knet-api-privacy-list li + li { border-top: 1px solid var(--knet-card-border, #e2e4e9); }
        .knet-api-bullet { flex-shrink: 0; margin-top: 2px; color: var(--knet-primary, #7873f5); }

        .knet-card-subtitle {
            font-size: 12px;
            color: var(--knet-text-secondary, #50575e);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
    <?php
    // Tab switcher / mobile menu / copy-to-clipboard are wired by the shared
    // knittnet-basic/js/admin-sidebar.js, enqueued in class-knittnet-admin.php for
    // the knittnet-api-access screen.
}
