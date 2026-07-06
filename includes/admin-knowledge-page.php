<?php
/**
 * KnittNet Knowledge Base Page - Redesigned with Sidebar Navigation
 *
 * @package KnittNet
 * @since 2.7.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the new sidebar-based Knowledge Base page
 */
function knittnet_render_knowledge_page($admin_instance, $knowledge_manager, $page_data) {
    $is_activated = $admin_instance->is_activated();
    $plugin_url = plugin_dir_url(dirname(__FILE__));

    // Check if integrations are active
    $pinecone_options = get_option('knittnet_pinecone_addon_options', array());
    $is_pinecone_active = ($pinecone_options['knittnet_use_pinecone'] ?? '0') === '1';

    $vectorstore_options = get_option('knittnet_openai_vectorstore_options', array());
    $is_vectorstore_active = ($vectorstore_options['knittnet_use_openai_vectorstore'] ?? '0') === '1';

    // Extract page data
    extract($page_data);
    ?>
    <div class="mxch-admin-wrapper">
        <!-- Mobile Header -->
        <header class="mxch-mobile-header">
            <a href="#" class="mxch-mobile-logo">
                <div class="mxch-mobile-logo-icon">
                    <img src="<?php echo esc_url($plugin_url . 'images/icon-128x128.png'); ?>" alt="KnittNet">
                </div>
                <span class="mxch-mobile-logo-text">KnittNet</span>
            </a>
            <button type="button" class="mxch-mobile-menu-btn" aria-label="<?php esc_attr_e('Open menu', 'knittnet'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
            </button>
        </header>

        <!-- Mobile Menu Overlay -->
        <div class="mxch-mobile-overlay"></div>

        <!-- Mobile Menu Modal -->
        <div class="mxch-mobile-menu">
            <div class="mxch-mobile-menu-header">
                <span class="mxch-mobile-menu-title"><?php esc_html_e('Knowledge Base', 'knittnet'); ?></span>
                <button type="button" class="mxch-mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'knittnet'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <nav class="mxch-mobile-menu-nav">
                <!-- Import Section -->
                <div class="mxch-mobile-nav-section">
                    <div class="mxch-mobile-nav-section-title"><?php esc_html_e('Import', 'knittnet'); ?></div>
                    <button class="mxch-mobile-nav-link active" data-target="import-options">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <span><?php esc_html_e('Import Options', 'knittnet'); ?></span>
                    </button>
                    <button class="mxch-mobile-nav-link" data-target="knowledge-base">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
                        <span><?php esc_html_e('Knowledge Base', 'knittnet'); ?></span>
                    </button>
                </div>
                <!-- Settings Section -->
                <div class="mxch-mobile-nav-section">
                    <div class="mxch-mobile-nav-section-title"><?php esc_html_e('Settings', 'knittnet'); ?></div>
                    <button class="mxch-mobile-nav-link" data-target="auto-sync">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                        <span><?php esc_html_e('Auto-Sync', 'knittnet'); ?></span>
                    </button>
                    <button class="mxch-mobile-nav-link" data-target="chunking">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        <span><?php esc_html_e('Content Chunking', 'knittnet'); ?></span>
                    </button>
                    <button class="mxch-mobile-nav-link" data-target="role-restrictions">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span><?php esc_html_e('Role Restrictions', 'knittnet'); ?></span>
                    </button>
                    <button class="mxch-mobile-nav-link" data-target="acf-fields">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                        <span><?php esc_html_e('ACF Fields', 'knittnet'); ?></span>
                    </button>
                    <button class="mxch-mobile-nav-link" data-target="custom-meta">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/></svg>
                        <span><?php esc_html_e('Custom Meta', 'knittnet'); ?></span>
                    </button>
                </div>
                <!-- Integrations Section -->
                <div class="mxch-mobile-nav-section">
                    <div class="mxch-mobile-nav-section-title"><?php esc_html_e('Integrations', 'knittnet'); ?></div>
                    <button class="mxch-mobile-nav-link" data-target="pinecone">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>
                        <span><?php esc_html_e('Pinecone', 'knittnet'); ?></span>
                        <?php if ($is_pinecone_active): ?>
                        <span class="mxch-nav-link-badge mxch-active-badge"><?php esc_html_e('Active', 'knittnet'); ?></span>
                        <?php endif; ?>
                    </button>
                    <button class="mxch-mobile-nav-link" data-target="openai-vectorstore">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M11 8v6"/><path d="M8 11h6"/></svg>
                        <span><?php esc_html_e('OpenAI Vector Store', 'knittnet'); ?></span>
                        <?php if ($is_vectorstore_active): ?>
                        <span class="mxch-nav-link-badge mxch-active-badge"><?php esc_html_e('Active', 'knittnet'); ?></span>
                        <?php endif; ?>
                    </button>
                </div>
                <?php if (!$is_activated): ?>
                <div class="mxch-mobile-menu-footer">
                    <a href="https://knittnet.ai/" target="_blank" class="mxch-mobile-upgrade-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        <?php esc_html_e('Upgrade to Pro', 'knittnet'); ?>
                    </a>
                </div>
                <?php endif; ?>
            </nav>
        </div>

        <!-- Sidebar Navigation -->
        <aside class="mxch-sidebar">
            <div class="mxch-sidebar-header">
                <a href="#" class="mxch-sidebar-logo">
                    <div class="mxch-sidebar-logo-icon">
                        <img src="<?php echo esc_url($plugin_url . 'images/icon-128x128.png'); ?>" alt="KnittNet">
                    </div>
                    <span class="mxch-sidebar-logo-text">KnittNet</span>
                    <span class="mxch-sidebar-version">v<?php echo esc_html(KNITTNET_VERSION ?? '2.7.0'); ?></span>
                </a>
            </div>

            <nav class="mxch-sidebar-nav">
                <!-- Import Section -->
                <div class="mxch-nav-section">
                    <div class="mxch-nav-section-title"><?php esc_html_e('Import', 'knittnet'); ?></div>

                    <div class="mxch-nav-item" data-section="import-options">
                        <button class="mxch-nav-link active" data-target="import-options">
                            <span class="mxch-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            </span>
                            <span class="mxch-nav-link-text"><?php esc_html_e('Import Options', 'knittnet'); ?></span>
                        </button>
                    </div>

                    <div class="mxch-nav-item" data-section="knowledge-base">
                        <button class="mxch-nav-link" data-target="knowledge-base">
                            <span class="mxch-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
                            </span>
                            <span class="mxch-nav-link-text"><?php esc_html_e('Knowledge Base', 'knittnet'); ?></span>
                            <span id="knittnet-sidebar-count" class="mxch-nav-link-badge"><?php echo esc_html($total_records); ?></span>
                        </button>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="mxch-nav-section">
                    <div class="mxch-nav-section-title"><?php esc_html_e('Settings', 'knittnet'); ?></div>

                    <div class="mxch-nav-item" data-section="auto-sync">
                        <button class="mxch-nav-link" data-target="auto-sync">
                            <span class="mxch-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                            </span>
                            <span class="mxch-nav-link-text"><?php esc_html_e('Auto-Sync', 'knittnet'); ?></span>
                        </button>
                    </div>

                    <div class="mxch-nav-item" data-section="chunking">
                        <button class="mxch-nav-link" data-target="chunking">
                            <span class="mxch-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            </span>
                            <span class="mxch-nav-link-text"><?php esc_html_e('Content Chunking', 'knittnet'); ?></span>
                        </button>
                    </div>

                    <div class="mxch-nav-item" data-section="role-restrictions">
                        <button class="mxch-nav-link" data-target="role-restrictions">
                            <span class="mxch-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </span>
                            <span class="mxch-nav-link-text"><?php esc_html_e('Role Restrictions', 'knittnet'); ?></span>
                        </button>
                    </div>

                    <div class="mxch-nav-item" data-section="acf-fields">
                        <button class="mxch-nav-link" data-target="acf-fields">
                            <span class="mxch-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                            </span>
                            <span class="mxch-nav-link-text"><?php esc_html_e('ACF Fields', 'knittnet'); ?></span>
                        </button>
                    </div>

                    <div class="mxch-nav-item" data-section="custom-meta">
                        <button class="mxch-nav-link" data-target="custom-meta">
                            <span class="mxch-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/></svg>
                            </span>
                            <span class="mxch-nav-link-text"><?php esc_html_e('Custom Meta', 'knittnet'); ?></span>
                        </button>
                    </div>
                </div>

                <!-- Integrations Section -->
                <div class="mxch-nav-section">
                    <div class="mxch-nav-section-title"><?php esc_html_e('Integrations', 'knittnet'); ?></div>

                    <div class="mxch-nav-item" data-section="pinecone">
                        <button class="mxch-nav-link" data-target="pinecone">
                            <span class="mxch-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>
                            </span>
                            <span class="mxch-nav-link-text"><?php esc_html_e('Pinecone', 'knittnet'); ?></span>
                            <?php if ($is_pinecone_active): ?>
                            <span class="mxch-nav-link-badge mxch-active-badge"><?php esc_html_e('Active', 'knittnet'); ?></span>
                            <?php endif; ?>
                        </button>
                    </div>

                    <div class="mxch-nav-item" data-section="openai-vectorstore">
                        <button class="mxch-nav-link" data-target="openai-vectorstore">
                            <span class="mxch-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M11 8v6"/><path d="M8 11h6"/></svg>
                            </span>
                            <span class="mxch-nav-link-text"><?php esc_html_e('OpenAI Vector Store', 'knittnet'); ?></span>
                            <?php if ($is_vectorstore_active): ?>
                            <span class="mxch-nav-link-badge mxch-active-badge"><?php esc_html_e('Active', 'knittnet'); ?></span>
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </nav>

            <?php if (!$is_activated): ?>
            <div class="mxch-sidebar-footer">
                <a href="https://knittnet.ai/" target="_blank" class="mxch-sidebar-upgrade-v2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <?php esc_html_e('Pro Upgrade', 'knittnet'); ?>
                </a>
            </div>
            <?php endif; ?>
        </aside>

        <!-- Main Content Area -->
        <main class="mxch-content">
            <?php
            // Multi-Bot Selector
            knittnet_render_knowledge_bot_selector($multibot_active, $current_bot_id);

            // Global Processing Status - Always visible when processing
            knittnet_render_global_processing_status($page_data, $knowledge_manager);

            // Render content sections
            knittnet_render_import_options_section($admin_instance, $knowledge_manager, $page_data);
            knittnet_render_knowledge_base_section($admin_instance, $knowledge_manager, $page_data);
            knittnet_render_auto_sync_section($knowledge_manager);
            knittnet_render_chunking_section();
            knittnet_render_role_restrictions_section($knowledge_manager);
            knittnet_render_acf_fields_section($knowledge_manager);
            knittnet_render_custom_meta_section();
            knittnet_render_pinecone_section();
            knittnet_render_openai_vectorstore_section();
            ?>

        </main>
    </div>

    <?php
    // Render the Content Selector Modal
    knittnet_render_content_selector_modal();

    // Render the Edit Entry Modal
    knittnet_render_edit_entry_modal();

    // Render the read-only Knowledge Inspector modal (plan-d8cb4b)
    knittnet_render_inspect_entry_modal();

    // Render the navigation JavaScript
    knittnet_render_knowledge_page_scripts();
}

/**
 * Render Multi-Bot Selector
 */
function knittnet_render_knowledge_bot_selector($multibot_active, $current_bot_id) {
    if (!$multibot_active || !class_exists('KnittNet_Multi_Bot_Manager')) {
        return;
    }

    $multi_bot_manager = KnittNet_Multi_Bot_Core_Manager::get_instance();
    $available_bots = $multi_bot_manager->get_available_bots();
    ?>
    <div class="mxch-card" style="margin-bottom: 24px;">
        <div class="mxch-card-body">
            <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                <label for="knittnet-bot-selector" style="font-weight: 600; white-space: nowrap;">
                    <?php esc_html_e('Select Bot Database:', 'knittnet'); ?>
                </label>
                <select id="knittnet-bot-selector" class="mxch-select" style="min-width: 200px; max-width: 300px;">
                    <?php foreach ($available_bots as $bot_id => $bot_name) : ?>
                        <option value="<?php echo esc_attr($bot_id); ?>" <?php selected($current_bot_id, $bot_id); ?>>
                            <?php echo esc_html($bot_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span style="color: var(--mxch-text-secondary); font-size: 13px;">
                    <?php esc_html_e('Content will be added to the selected bot\'s knowledge base', 'knittnet'); ?>
                </span>
                <span id="knittnet-bot-save-status" style="display: none; color: var(--mxch-success); font-size: 13px;">
                    ✓ <?php esc_html_e('Saved', 'knittnet'); ?>
                </span>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Global Processing Status - Always visible when processing is active
 */
function knittnet_render_global_processing_status($page_data, $knowledge_manager) {
    extract($page_data);

    // Only show if there's active processing
    if (!$is_processing) {
        return;
    }
    ?>
    <div class="mxch-processing-status-global knittnet-import-section" style="margin-bottom: 24px;">
        <?php
        // PDF Processing Status - Using knittnet-status-card class for JavaScript compatibility
        if ($pdf_status && $pdf_status['status'] === 'processing') : ?>
            <div class="knittnet-status-card mxch-card" data-card-type="pdf" data-queue-id="<?php echo esc_attr(get_transient('knittnet_active_queue_pdf')); ?>" style="border-left: 4px solid var(--mxch-primary); margin-bottom: 16px;">
                <div class="knittnet-status-header mxch-card-header" style="background: var(--mxch-primary-light);">
                    <h4 style="margin: 0; display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <?php esc_html_e('PDF Processing Status', 'knittnet'); ?>
                    </h4>
                    <div class="knittnet-status-warning" style="background: #fff3cd; color: #856404; padding: 8px 12px; border-radius: 4px; font-size: 13px; margin: 10px 0;">
                        <?php esc_html_e('Keep this tab open - Processing runs in your browser', 'knittnet'); ?>
                    </div>
                    <form method="post" class="knittnet-stop-form" action="<?php echo esc_url(admin_url('admin-post.php?action=knittnet_stop_processing')); ?>">
                        <?php wp_nonce_field('knittnet_stop_processing_action', 'knittnet_stop_processing_nonce'); ?>
                        <button type="submit" name="stop_processing" class="knittnet-button-secondary mxch-btn mxch-btn-secondary mxch-btn-sm">
                            <?php esc_html_e('Stop Processing', 'knittnet'); ?>
                        </button>
                    </form>
                </div>
                <div class="knittnet-progress-bar" style="height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; margin: 0;">
                    <div class="knittnet-progress-fill" style="height: 100%; background: var(--mxch-primary); width: <?php echo esc_attr($pdf_status['percentage']); ?>%; transition: width 0.3s;"></div>
                </div>
                <div class="knittnet-status-details mxch-card-body">
                    <p><?php printf(esc_html__('Progress: %1$d of %2$d pages (%3$d%%)', 'knittnet'), absint($pdf_status['processed_pages']), absint($pdf_status['total_pages']), absint($pdf_status['percentage'])); ?></p>
                    <?php if (!empty($pdf_status['failed_pages']) && $pdf_status['failed_pages'] > 0) : ?>
                        <p class="error-count" style="color: var(--mxch-error);"><strong><?php esc_html_e('Failed pages:', 'knittnet'); ?></strong> <?php echo esc_html($pdf_status['failed_pages']); ?></p>
                    <?php endif; ?>
                    <p><strong><?php esc_html_e('Status:', 'knittnet'); ?></strong> <?php esc_html_e('Processing', 'knittnet'); ?></p>
                </div>
            </div>
        <?php endif;

        // Sitemap Processing Status - Using knittnet-status-card class for JavaScript compatibility
        if ($sitemap_status && $sitemap_status['status'] === 'processing') : ?>
            <div class="knittnet-status-card mxch-card" data-card-type="sitemap" data-queue-id="<?php echo esc_attr(get_transient('knittnet_active_queue_sitemap')); ?>" style="border-left: 4px solid var(--mxch-primary); margin-bottom: 16px;">
                <div class="knittnet-status-header mxch-card-header" style="background: var(--mxch-primary-light);">
                    <h4 style="margin: 0; display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        <?php esc_html_e('Sitemap Processing Status', 'knittnet'); ?>
                    </h4>
                    <div class="knittnet-status-warning" style="background: #fff3cd; color: #856404; padding: 8px 12px; border-radius: 4px; font-size: 13px; margin: 10px 0;">
                        <?php esc_html_e('Keep this tab open - Processing runs in your browser', 'knittnet'); ?>
                    </div>
                    <form method="post" class="knittnet-stop-form" action="<?php echo esc_url(admin_url('admin-post.php?action=knittnet_stop_processing')); ?>">
                        <?php wp_nonce_field('knittnet_stop_processing_action', 'knittnet_stop_processing_nonce'); ?>
                        <button type="submit" name="stop_processing" class="knittnet-button-secondary mxch-btn mxch-btn-secondary mxch-btn-sm">
                            <?php esc_html_e('Stop Processing', 'knittnet'); ?>
                        </button>
                    </form>
                </div>
                <div class="knittnet-progress-bar" style="height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; margin: 0;">
                    <div class="knittnet-progress-fill" style="height: 100%; background: var(--mxch-primary); width: <?php echo esc_attr($sitemap_status['percentage']); ?>%; transition: width 0.3s;"></div>
                </div>
                <div class="knittnet-status-details mxch-card-body">
                    <p><?php printf(esc_html__('Progress: %1$d of %2$d URLs (%3$d%%)', 'knittnet'), absint($sitemap_status['processed_urls']), absint($sitemap_status['total_urls']), absint($sitemap_status['percentage'])); ?></p>
                    <?php if (!empty($sitemap_status['failed_urls']) && $sitemap_status['failed_urls'] > 0) : ?>
                        <p class="error-count" style="color: var(--mxch-error);"><strong><?php esc_html_e('Failed URLs:', 'knittnet'); ?></strong> <?php echo esc_html($sitemap_status['failed_urls']); ?></p>
                    <?php endif; ?>
                    <p><strong><?php esc_html_e('Status:', 'knittnet'); ?></strong> <?php esc_html_e('Processing', 'knittnet'); ?></p>
                </div>
            </div>
        <?php endif;

        // Completed status cards
        echo $knowledge_manager->knittnet_render_completed_status_cards();
        ?>
    </div>
    <?php
}

/**
 * Render Import Options Section
 */
function knittnet_render_import_options_section($admin_instance, $knowledge_manager, $page_data) {
    extract($page_data);
    $options = $admin_instance->options ?? get_option('knittnet_options', array());

    // Check if user is using WordPress database (not Pinecone or OpenAI Vector Store)
    $pinecone_options = get_option('knittnet_pinecone_addon_options', array());
    $is_pinecone_active = ($pinecone_options['knittnet_use_pinecone'] ?? '0') === '1';
    $vectorstore_options = get_option('knittnet_openai_vectorstore_options', array());
    $is_vectorstore_active = ($vectorstore_options['knittnet_use_openai_vectorstore'] ?? '0') === '1';
    $is_using_wordpress_db = !$is_pinecone_active && !$is_vectorstore_active;

    // Check embedding API key
    $embedding_model = isset($options['embedding_model']) ? esc_attr($options['embedding_model']) : 'text-embedding-ada-002';
    $has_openai_key = !empty($options['api_key']);
    $has_voyage_key = !empty($options['voyage_api_key']);
    $has_gemini_key = !empty($options['gemini_api_key']);

    $has_required_key = false;
    $required_key_type = '';

    if (strpos($embedding_model, 'text-embedding-') !== false && $has_openai_key) {
        $has_required_key = true;
        $required_key_type = 'OpenAI';
    } elseif (strpos($embedding_model, 'voyage-') !== false && $has_voyage_key) {
        $has_required_key = true;
        $required_key_type = 'Voyage AI';
    } elseif (strpos($embedding_model, 'gemini-embedding-') !== false && $has_gemini_key) {
        $has_required_key = true;
        $required_key_type = 'Google Gemini';
    } elseif (strpos($embedding_model, 'text-embedding-') !== false) {
        $required_key_type = 'OpenAI';
    } elseif (strpos($embedding_model, 'voyage-') !== false) {
        $required_key_type = 'Voyage AI';
    } elseif (strpos($embedding_model, 'gemini-embedding-') !== false) {
        $required_key_type = 'Google Gemini';
    }
    ?>
    <div id="import-options" class="mxch-section active">
        <div class="mxch-content-header">
            <h1 class="mxch-content-title"><?php esc_html_e('Import Options', 'knittnet'); ?></h1>
            <p class="mxch-content-subtitle"><?php esc_html_e('Import content to your knowledge base from various sources.', 'knittnet'); ?></p>
        </div>

        <!-- API Key Status -->
        <div class="mxch-notice <?php echo $has_required_key ? 'mxch-notice-success' : 'mxch-notice-warning'; ?>" style="margin-bottom: 24px;">
            <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <?php if ($has_required_key): ?>
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                <?php else: ?>
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                <?php endif; ?>
            </svg>
            <div>
                <?php if ($has_required_key): ?>
                    <?php echo wp_kses_post(sprintf(__('We detected your %s API key. <strong>Remember to add credits to your %s account</strong> before using the knowledgebase.', 'knittnet'), $required_key_type, $required_key_type)); ?>
                <?php else: ?>
                    <strong><?php esc_html_e('Important:', 'knittnet'); ?></strong>
                    <?php echo sprintf(esc_html__('Before importing knowledge, you must add a %s API key with sufficient credits in the Chatbot settings.', 'knittnet'), $required_key_type); ?>
                    <a href="<?php echo admin_url('admin.php?page=knittnet-max#api-keys'); ?>"><?php esc_html_e('Go to API Key Settings', 'knittnet'); ?></a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($is_using_wordpress_db): ?>
        <!-- WordPress Database Caution Notice -->
        <div class="mxch-notice mxch-notice-warning" style="margin-bottom: 24px;">
            <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <div>
                <strong><?php esc_html_e('Using WordPress Database:', 'knittnet'); ?></strong>
                <?php esc_html_e('The WordPress database is not optimized for vector search with large datasets. If you plan to have more than 500 knowledge entries, we highly recommend using Pinecone for better performance and scalability.', 'knittnet'); ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="mxch-card">
            <div class="mxch-card-header">
                <h3 class="mxch-card-title">
                    <svg class="mxch-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <?php esc_html_e('Choose Import Method', 'knittnet'); ?>
                </h3>
            </div>
            <div class="mxch-card-body">
                <!-- Import Options Grid -->
                <div class="knittnet-import-options">
                    <!-- WordPress Import Option -->
                    <button type="button" id="knittnet-open-content-selector" class="knittnet-import-box knittnet-import-wordpress" data-option="wordpress">
                        <div class="knittnet-import-icon">
                            <span class="dashicons dashicons-wordpress"></span>
                        </div>
                        <div class="knittnet-import-content">
                            <h4><?php esc_html_e('WordPress Content', 'knittnet'); ?></h4>
                            <p><?php esc_html_e('Import specific posts and pages to your knowledge base.', 'knittnet'); ?></p>
                        </div>
                        <div class="knittnet-recommended-tag"><?php esc_html_e('Recommended', 'knittnet'); ?></div>
                    </button>

                    <!-- Sitemap Import Option -->
                    <button type="button" class="knittnet-import-box" data-option="sitemap" data-placeholder="<?php esc_attr_e('Enter sitemap URL here', 'knittnet'); ?>" data-type="sitemap">
                        <div class="knittnet-import-icon">
                            <span class="dashicons dashicons-admin-site-alt"></span>
                        </div>
                        <div class="knittnet-import-content">
                            <h4><?php esc_html_e('Sitemap Import', 'knittnet'); ?></h4>
                            <p><?php esc_html_e('Use a content-specific sub-sitemap, not the sitemap index.', 'knittnet'); ?></p>
                        </div>
                    </button>

                    <!-- Direct URL Import Option -->
                    <button type="button" class="knittnet-import-box" data-option="url" data-placeholder="<?php esc_attr_e('Enter webpage URL here', 'knittnet'); ?>" data-type="url">
                        <div class="knittnet-import-icon">
                            <span class="dashicons dashicons-admin-links"></span>
                        </div>
                        <div class="knittnet-import-content">
                            <h4><?php esc_html_e('Direct URL', 'knittnet'); ?></h4>
                            <p><?php esc_html_e('Import content from any webpage.', 'knittnet'); ?></p>
                        </div>
                    </button>

                    <!-- Direct Content Import Option -->
                    <button type="button" class="knittnet-import-box" data-option="content" data-type="content">
                        <div class="knittnet-import-icon">
                            <span class="dashicons dashicons-editor-paste-text"></span>
                        </div>
                        <div class="knittnet-import-content">
                            <h4><?php esc_html_e('Direct Content', 'knittnet'); ?></h4>
                            <p><?php esc_html_e('Submit content to be vectorized.', 'knittnet'); ?></p>
                        </div>
                    </button>

                    <!-- PDF Import Option (URL) -->
                    <button type="button" class="knittnet-import-box" data-option="pdf-url" data-placeholder="<?php esc_attr_e('Enter PDF URL here', 'knittnet'); ?>" data-type="pdf">
                        <div class="knittnet-import-icon">
                            <span class="dashicons dashicons-media-document"></span>
                        </div>
                        <div class="knittnet-import-content">
                            <h4><?php esc_html_e('PDF Import', 'knittnet'); ?></h4>
                            <p><?php esc_html_e('Import knowledge from PDF URL.', 'knittnet'); ?></p>
                        </div>
                    </button>

                    <!-- PDF File Upload Option -->
                    <button type="button" class="knittnet-import-box" data-option="pdf-upload" data-type="pdf-upload">
                        <div class="knittnet-import-icon">
                            <span class="dashicons dashicons-upload"></span>
                        </div>
                        <div class="knittnet-import-content">
                            <h4><?php esc_html_e('PDF Upload', 'knittnet'); ?></h4>
                            <p><?php esc_html_e('Upload a PDF file from your computer.', 'knittnet'); ?></p>
                        </div>
                    </button>
                </div>

                <!-- Detected Sitemaps Section (hidden by default, shown when Sitemap Import is clicked) -->
                <div id="knittnet-detected-sitemaps" class="knittnet-detected-sitemaps" style="display: none;">
                    <div class="knittnet-sitemaps-header">
                        <h4>
                            <span class="dashicons dashicons-admin-site-alt"></span>
                            <?php esc_html_e('Detected Sitemaps', 'knittnet'); ?>
                        </h4>
                        <button type="button" id="knittnet-refresh-sitemaps" class="mxch-btn mxch-btn-ghost mxch-btn-sm" title="<?php esc_attr_e('Refresh', 'knittnet'); ?>">
                            <span class="dashicons dashicons-update"></span>
                        </button>
                    </div>
                    <div id="knittnet-sitemaps-list" class="knittnet-sitemaps-list">
                        <!-- Sitemaps will be loaded here via AJAX -->
                    </div>
                    <input type="hidden" id="knittnet-detect-sitemaps-nonce" value="<?php echo wp_create_nonce('knittnet_detect_sitemaps_nonce'); ?>">
                    <?php if ($multibot_active && $current_bot_id !== 'default') : ?>
                        <input type="hidden" id="knittnet-sitemap-bot-id" value="<?php echo esc_attr($current_bot_id); ?>">
                    <?php endif; ?>
                </div>

                <!-- No Sitemaps Found Message (hidden by default) -->
                <div id="knittnet-no-sitemaps" class="knittnet-no-sitemaps" style="display: none;">
                    <span class="dashicons dashicons-info"></span>
                    <p>
                        <?php esc_html_e('No sitemaps detected. You can manually enter a sitemap URL using the input field above.', 'knittnet'); ?>
                    </p>
                </div>

                <!-- Sitemaps Loading State (hidden by default) -->
                <div id="knittnet-sitemaps-loading" class="knittnet-sitemaps-loading" style="display: none;">
                    <span class="dashicons dashicons-update spin"></span>
                    <p><?php esc_html_e('Detecting sitemaps...', 'knittnet'); ?></p>
                </div>

                <!-- Input Areas for URL and Content -->
                <?php if (!$is_processing) : ?>
                <div class="knittnet-import-input-area" id="knittnet-url-input-area" style="display: none; margin-top: 20px;">
                    <form id="knittnet-url-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=knittnet_submit_sitemap')); ?>">
                        <?php wp_nonce_field('knittnet_submit_sitemap_action', 'knittnet_submit_sitemap_nonce'); ?>
                        <input type="hidden" name="import_type" id="import_type" value="url">
                        <?php if ($multibot_active && $current_bot_id !== 'default') : ?>
                            <input type="hidden" name="bot_id" value="<?php echo esc_attr($current_bot_id); ?>">
                        <?php endif; ?>
                        <div style="display: flex; gap: 10px;">
                            <input type="url" name="sitemap_url" id="sitemap_url" class="mxch-input" placeholder="<?php esc_attr_e('Enter URL here', 'knittnet'); ?>" required style="flex: 1;" />
                            <button type="submit" name="submit_sitemap" class="mxch-btn mxch-btn-primary">
                                <?php esc_html_e('Import', 'knittnet'); ?>
                            </button>
                        </div>
                        <p class="mxch-field-description" id="url-description-text"></p>
                    </form>
                </div>

                <div class="knittnet-import-input-area" id="knittnet-content-input-area" style="display: none; margin-top: 20px;">
                    <form id="knittnet-content-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=knittnet_submit_content')); ?>">
                        <?php wp_nonce_field('knittnet_submit_content_action', 'knittnet_submit_content_nonce'); ?>
                        <?php if ($multibot_active && $current_bot_id !== 'default') : ?>
                            <input type="hidden" name="bot_id" value="<?php echo esc_attr($current_bot_id); ?>">
                        <?php endif; ?>
                        <div class="mxch-field">
                            <textarea name="article_content" id="article_content" class="mxch-textarea" placeholder="<?php esc_attr_e('Enter your content here...', 'knittnet'); ?>" required rows="6"></textarea>
                        </div>
                        <div class="mxch-field">
                            <input type="url" name="article_url" id="article_url" class="mxch-input" placeholder="<?php esc_attr_e('Enter source URL (Optional)', 'knittnet'); ?>">
                            <div style="margin-top: 8px; display: flex; align-items: center; gap: 8px;">
                                <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--mxch-text-secondary); cursor: pointer;">
                                    <input type="checkbox" id="knittnet-unique-url-toggle" style="margin: 0;">
                                    <?php esc_html_e('Generate unique URL for duplicate content', 'knittnet'); ?>
                                </label>
                                <button type="button" id="knittnet-generate-unique-url" class="mxch-btn mxch-btn-secondary mxch-btn-sm" style="display: none;">
                                    <span class="dashicons dashicons-randomize" style="font-size: 14px; margin-top: 2px;"></span>
                                    <?php esc_html_e('Generate Unique', 'knittnet'); ?>
                                </button>
                            </div>
                            <p class="mxch-field-description">
                                <?php esc_html_e('Enable this option to submit multiple entries with the same base URL. A unique reference will be appended.', 'knittnet'); ?>
                            </p>
                        </div>
                        <button type="submit" name="submit_content" class="mxch-btn mxch-btn-primary">
                            <?php esc_html_e('Import Content', 'knittnet'); ?>
                        </button>
                    </form>
                </div>

                <div class="knittnet-import-input-area" id="knittnet-pdf-upload-area" style="display: none; margin-top: 20px;">
                    <form id="knittnet-pdf-upload-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=knittnet_submit_pdf_file')); ?>" enctype="multipart/form-data">
                        <?php wp_nonce_field('knittnet_submit_pdf_file_action', 'knittnet_submit_pdf_file_nonce'); ?>
                        <?php if ($multibot_active && $current_bot_id !== 'default') : ?>
                            <input type="hidden" name="bot_id" value="<?php echo esc_attr($current_bot_id); ?>">
                        <?php endif; ?>
                        <div class="mxch-field">
                            <input type="file" name="pdf_file" id="knittnet-pdf-file-input" accept=".pdf" required>
                        </div>
                        <p class="mxch-field-description">
                            <?php esc_html_e('Select a PDF file from your computer to import into the knowledge base. Maximum file size depends on your server settings.', 'knittnet'); ?>
                        </p>
                        <button type="submit" name="submit_pdf_file" class="mxch-btn mxch-btn-primary">
                            <?php esc_html_e('Import PDF', 'knittnet'); ?>
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Processing Status Cards (legacy - kept for reference)
 * Note: Processing status is now rendered globally via knittnet_render_global_processing_status()
 */
function knittnet_render_processing_status($page_data, $knowledge_manager) {
    extract($page_data);

    // PDF Processing Status
    if ($pdf_status && $pdf_status['status'] === 'processing') : ?>
        <div class="mxch-card" style="margin-top: 20px;">
            <div class="mxch-card-header" style="background: var(--mxch-primary-light);">
                <h3 class="mxch-card-title"><?php esc_html_e('PDF Processing Status', 'knittnet'); ?></h3>
                <div style="display: flex; gap: 10px;">
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=knittnet_stop_processing')); ?>">
                        <?php wp_nonce_field('knittnet_stop_processing_action', 'knittnet_stop_processing_nonce'); ?>
                        <button type="submit" name="stop_processing" class="mxch-btn mxch-btn-secondary mxch-btn-sm">
                            <?php esc_html_e('Stop Processing', 'knittnet'); ?>
                        </button>
                    </form>
                    <button type="button" class="mxch-btn mxch-btn-primary mxch-btn-sm knittnet-manual-batch-btn" data-process-type="pdf" data-url="<?php echo esc_attr(get_transient('knittnet_last_pdf_url')); ?>">
                        <?php esc_html_e('Process Batch', 'knittnet'); ?>
                    </button>
                </div>
            </div>
            <div class="mxch-card-body">
                <div class="knittnet-progress-bar" style="height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; margin-bottom: 15px;">
                    <div class="knittnet-progress-fill" style="height: 100%; background: var(--mxch-primary); width: <?php echo esc_attr($pdf_status['percentage']); ?>%; transition: width 0.3s;"></div>
                </div>
                <p><?php printf(esc_html__('Progress: %1$d of %2$d pages (%3$d%%)', 'knittnet'), absint($pdf_status['processed_pages']), absint($pdf_status['total_pages']), absint($pdf_status['percentage'])); ?></p>
                <?php if (!empty($pdf_status['failed_pages']) && $pdf_status['failed_pages'] > 0) : ?>
                    <p><strong><?php esc_html_e('Failed pages:', 'knittnet'); ?></strong> <?php echo esc_html($pdf_status['failed_pages']); ?></p>
                <?php endif; ?>
                <p><strong><?php esc_html_e('Last update:', 'knittnet'); ?></strong> <?php echo esc_html($pdf_status['last_update']); ?></p>
            </div>
        </div>
    <?php endif;

    // Sitemap Processing Status
    if ($sitemap_status && $sitemap_status['status'] === 'processing') : ?>
        <div class="mxch-card" style="margin-top: 20px;">
            <div class="mxch-card-header" style="background: var(--mxch-primary-light);">
                <h3 class="mxch-card-title"><?php esc_html_e('Sitemap Processing Status', 'knittnet'); ?></h3>
                <div style="display: flex; gap: 10px;">
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=knittnet_stop_processing')); ?>">
                        <?php wp_nonce_field('knittnet_stop_processing_action', 'knittnet_stop_processing_nonce'); ?>
                        <button type="submit" name="stop_processing" class="mxch-btn mxch-btn-secondary mxch-btn-sm">
                            <?php esc_html_e('Stop Processing', 'knittnet'); ?>
                        </button>
                    </form>
                    <button type="button" class="mxch-btn mxch-btn-primary mxch-btn-sm knittnet-manual-batch-btn" data-process-type="sitemap" data-url="<?php echo esc_attr(get_transient('knittnet_last_sitemap_url')); ?>">
                        <?php esc_html_e('Process Batch', 'knittnet'); ?>
                    </button>
                </div>
            </div>
            <div class="mxch-card-body">
                <div class="knittnet-progress-bar" style="height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; margin-bottom: 15px;">
                    <div class="knittnet-progress-fill" style="height: 100%; background: var(--mxch-primary); width: <?php echo esc_attr($sitemap_status['percentage']); ?>%; transition: width 0.3s;"></div>
                </div>
                <p><?php printf(esc_html__('Progress: %1$d of %2$d URLs (%3$d%%)', 'knittnet'), absint($sitemap_status['processed_urls']), absint($sitemap_status['total_urls']), absint($sitemap_status['percentage'])); ?></p>
                <?php if (!empty($sitemap_status['failed_urls']) && $sitemap_status['failed_urls'] > 0) : ?>
                    <p><strong><?php esc_html_e('Failed URLs:', 'knittnet'); ?></strong> <?php echo esc_html($sitemap_status['failed_urls']); ?></p>
                <?php endif; ?>
                <p><strong><?php esc_html_e('Last update:', 'knittnet'); ?></strong> <?php echo esc_html($sitemap_status['last_update']); ?></p>
            </div>
        </div>
    <?php endif;

    // Completed status cards
    echo $knowledge_manager->knittnet_render_completed_status_cards();
}

/**
 * Render Knowledge Base Section (table)
 */
function knittnet_render_knowledge_base_section($admin_instance, $knowledge_manager, $page_data) {
    extract($page_data);

    // Group prompts by source_url for chunked content display
    $grouped_prompts = array();

    if ($prompts) {
        foreach ($prompts as $prompt) {
            $source_url = $prompt->source_url ?? '';

            // Check if chunk metadata exists on the record itself (Pinecone)
            if (isset($prompt->chunk_index) && $prompt->chunk_index !== null) {
                // Pinecone: metadata is already on the record object
                $prompt->chunk_metadata = array(
                    'chunk_index' => intval($prompt->chunk_index),
                    'total_chunks' => isset($prompt->total_chunks) ? intval($prompt->total_chunks) : null,
                    'is_chunked' => isset($prompt->is_chunked) ? (bool) $prompt->is_chunked : true
                );
                $prompt->display_content = $prompt->article_content; // Pinecone content is already clean
            } else {
                // WordPress: Parse chunk metadata from JSON prefix in article_content
                if (class_exists('KnittNet_Chunker')) {
                    $chunk_meta = KnittNet_Chunker::parse_stored_chunk($prompt->article_content);
                    $prompt->chunk_metadata = $chunk_meta['metadata'];
                    $prompt->display_content = $chunk_meta['text']; // Store clean content without JSON prefix
                } else {
                    $prompt->chunk_metadata = array();
                    $prompt->display_content = $prompt->article_content;
                }
            }

            if (!empty($source_url)) {
                if (!isset($grouped_prompts[$source_url])) {
                    $grouped_prompts[$source_url] = array();
                }
                $grouped_prompts[$source_url][] = $prompt;
            } else {
                // Use a unique key for ungrouped prompts
                $grouped_prompts['_ungrouped_' . $prompt->id] = array($prompt);
            }
        }

        // Sort each group by chunk_index from metadata
        foreach ($grouped_prompts as $source_url => &$group) {
            usort($group, function($a, $b) {
                $index_a = isset($a->chunk_metadata['chunk_index']) ? intval($a->chunk_metadata['chunk_index']) : 0;
                $index_b = isset($b->chunk_metadata['chunk_index']) ? intval($b->chunk_metadata['chunk_index']) : 0;
                return $index_a - $index_b;
            });
        }
        unset($group); // Break reference
    }
    $display_index = 0;
    ?>
    <div id="knowledge-base" class="mxch-section">
        <div class="mxch-content-header">
            <h1 class="mxch-content-title"><?php esc_html_e('Knowledge Base', 'knittnet'); ?></h1>
            <p class="mxch-content-subtitle"><?php esc_html_e('View and manage your imported knowledge entries.', 'knittnet'); ?></p>
        </div>

        <?php if (!empty($use_vectorstore)) : ?>
        <div class="mxch-notice mxch-notice-info" style="margin-bottom: 20px;">
            <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <span><strong><?php esc_html_e('OpenAI Vector Store is Active', 'knittnet'); ?></strong> - <?php esc_html_e('Your chatbot is using OpenAI\'s hosted Vector Store for knowledge retrieval. The entries below are stored locally and are not being searched. To use this local database, disable Vector Store in the Integrations settings.', 'knittnet'); ?></span>
        </div>
        <?php endif; ?>

        <div class="mxch-card">
            <div class="mxch-card-header">
                <h3 class="mxch-card-title">
                    <?php esc_html_e('Knowledge Entries', 'knittnet'); ?>
                    <span id="knittnet-entry-count" style="font-weight: normal; color: var(--mxch-text-secondary);">(<?php echo esc_html($total_records); ?>)</span>
                    <?php if (!empty($use_vectorstore)) : ?>
                        <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; margin-left: 8px; background: #fff3e0; color: #e65100;">
                            <span class="dashicons dashicons-database" style="font-size: 14px;"></span>
                            <?php esc_html_e('Not in use', 'knittnet'); ?>
                        </span>
                    <?php elseif ($use_pinecone) : ?>
                        <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; margin-left: 8px; background: #e3f2fd; color: #1976d2;">
                            <span class="dashicons dashicons-cloud" style="font-size: 14px;"></span>
                            <?php esc_html_e('Pinecone', 'knittnet'); ?>
                        </span>
                    <?php else : ?>
                        <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; margin-left: 8px; background: #f3e5f5; color: #7b1fa2;">
                            <span class="dashicons dashicons-database-view" style="font-size: 14px;"></span>
                            <?php esc_html_e('WordPress DB', 'knittnet'); ?>
                        </span>
                    <?php endif; ?>
                </h3>
                <div class="mxch-kb-header-controls">
                    <form method="get" id="knowledge-search" class="mxch-kb-search-form">
                        <?php wp_nonce_field('knittnet_prompts_search_nonce'); ?>
                        <input type="hidden" name="page" value="knittnet-prompts" />
                        <?php if ($multibot_active && !empty($current_bot_id) && $current_bot_id !== 'default') : ?>
                            <input type="hidden" name="bot_id" value="<?php echo esc_attr($current_bot_id); ?>" />
                        <?php endif; ?>
                        <input type="text" name="search" class="mxch-input mxch-input-sm mxch-kb-search-input" placeholder="<?php esc_attr_e('Search...', 'knittnet'); ?>" value="<?php echo esc_attr($search_query); ?>" />
                        <select name="content_type" class="mxch-select mxch-kb-type-filter" onchange="this.form.submit()">
                            <option value=""><?php esc_html_e('All Types', 'knittnet'); ?></option>
                            <?php
                            $post_types = get_post_types(array('public' => true), 'objects');
                            foreach ($post_types as $post_type) {
                                echo '<option value="' . esc_attr($post_type->name) . '" ' . selected($content_type_filter, $post_type->name, false) . '>' . esc_html($post_type->label) . '</option>';
                            }
                            ?>
                            <option value="pdf" <?php selected($content_type_filter, 'pdf'); ?>><?php esc_html_e('PDFs', 'knittnet'); ?></option>
                            <option value="url" <?php selected($content_type_filter, 'url'); ?>><?php esc_html_e('URLs', 'knittnet'); ?></option>
                        </select>
                    </form>
                    <!-- Delete Button Container - transforms between Delete All and Delete Selected -->
                    <div class="knittnet-delete-container" id="knittnet-delete-container">
                        <!-- Delete All Form (shown when nothing selected) -->
                        <?php
                        // Build confirm message and button text based on active filter
                        $delete_all_label = __('Delete All', 'knittnet');
                        $delete_all_confirm = __('Are you sure you want to delete all knowledge?', 'knittnet');
                        if (!empty($content_type_filter)) {
                            $filter_display = $content_type_filter;
                            // Try to get a human-readable label
                            $pt_obj = get_post_type_object($content_type_filter);
                            if ($pt_obj) {
                                $filter_display = $pt_obj->label;
                            } elseif ($content_type_filter === 'pdf') {
                                $filter_display = 'PDFs';
                            } elseif ($content_type_filter === 'url') {
                                $filter_display = 'URLs';
                            }
                            /* translators: %s: content type label (e.g. "Pages", "PDFs") */
                            $delete_all_label = sprintf(__('Delete All %s', 'knittnet'), $filter_display);
                            /* translators: %s: content type label */
                            $delete_all_confirm = sprintf(__('Are you sure you want to delete all %s from the knowledge base?', 'knittnet'), $filter_display);
                        }
                        ?>
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=knittnet_delete_all_prompts')); ?>" id="knittnet-delete-all-form" onsubmit="return confirm('<?php echo esc_attr($delete_all_confirm); ?>');">
                            <?php wp_nonce_field('knittnet_delete_all_prompts_action', 'knittnet_delete_all_prompts_nonce'); ?>
                            <input type="hidden" name="data_source" value="<?php echo esc_attr($data_source); ?>" />
                            <input type="hidden" name="bot_id" value="<?php echo esc_attr($current_bot_id); ?>" />
                            <?php if (!empty($content_type_filter)) : ?>
                                <input type="hidden" name="content_type_filter" value="<?php echo esc_attr($content_type_filter); ?>" />
                            <?php endif; ?>
                            <button type="submit" class="mxch-btn mxch-btn-secondary mxch-btn-sm" style="color: var(--mxch-error);">
                                <span class="dashicons dashicons-trash" style="font-size: 14px;"></span>
                                <?php echo esc_html($delete_all_label); ?>
                            </button>
                        </form>
                        <!-- Delete Selected Button (shown when items selected) -->
                        <button type="button"
                                class="mxch-btn mxch-btn-secondary mxch-btn-sm knittnet-bulk-delete"
                                id="knittnet-delete-selected-entries"
                                style="display: none; color: var(--mxch-error);"
                                data-nonce="<?php echo wp_create_nonce('knittnet_bulk_delete_knowledge_nonce'); ?>"
                                data-bot-id="<?php echo esc_attr($current_bot_id); ?>"
                                data-data-source="<?php echo esc_attr($data_source); ?>">
                            <span class="dashicons dashicons-trash" style="font-size: 14px;"></span>
                            <span class="knittnet-bulk-delete-text"><?php esc_html_e('Delete Selected', 'knittnet'); ?></span>
                            <span class="knittnet-selected-count" id="knittnet-selected-entry-count"></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mxch-card-body" style="padding: 0;">
                <?php if ($use_pinecone) : ?>
                    <div class="mxch-notice mxch-notice-info mxch-pinecone-notice" style="margin: 16px; border-radius: var(--mxch-radius-md); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span><?php esc_html_e('Changes may take up to 60 seconds to process in Pinecone. Click "Refresh Entries" to see updates.', 'knittnet'); ?></span>
                        </div>
                        <button type="button" id="knittnet-refresh-pinecone-entries" class="mxch-btn mxch-btn-secondary mxch-btn-sm">
                            <span class="dashicons dashicons-update"></span>
                            <?php esc_html_e('Refresh Entries', 'knittnet'); ?>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="knittnet-table-wrapper" style="overflow-x: auto;">
                    <table class="knittnet-records-table" id="knittnet-records-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid var(--mxch-card-border);">
                                <th style="padding: 12px 16px; width: 40px; text-align: center;">
                                    <input type="checkbox" id="knittnet-select-all-entries" class="knittnet-entry-checkbox-all" title="<?php esc_attr_e('Select All', 'knittnet'); ?>">
                                </th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: var(--mxch-text-secondary); font-size: 12px; text-transform: uppercase;"><?php esc_html_e('ID', 'knittnet'); ?></th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: var(--mxch-text-secondary); font-size: 12px; text-transform: uppercase;"><?php esc_html_e('Content', 'knittnet'); ?></th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: var(--mxch-text-secondary); font-size: 12px; text-transform: uppercase;"><?php esc_html_e('Source', 'knittnet'); ?></th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: var(--mxch-text-secondary); font-size: 12px; text-transform: uppercase;"><?php esc_html_e('Actions', 'knittnet'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="knittnet-entries-tbody">
                            <?php if (empty($grouped_prompts)) : ?>
                                <tr>
                                    <td colspan="5" style="padding: 40px; text-align: center; color: var(--mxch-text-muted);">
                                        <?php esc_html_e('No knowledge entries found. Use the Import Options to add content.', 'knittnet'); ?>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php
                                // Display grouped prompts
                                foreach ($grouped_prompts as $source_url => $group) :
                                    $chunk_count = count($group);
                                    $first_prompt = $group[0];
                                    $display_index++;

                                    if ($chunk_count > 1) :
                                        // Multiple chunks - show grouped row with expand button
                                        $group_id = 'group-' . md5($source_url);
                                ?>
                                        <tr id="prompt-<?php echo esc_attr($first_prompt->id); ?>"
                                            class="knittnet-chunk-group-header"
                                            data-source="<?php echo esc_attr($data_source); ?>"
                                            data-group-id="<?php echo esc_attr($group_id); ?>"
                                            style="border-bottom: 1px solid var(--mxch-card-border); <?php if ($data_source === 'pinecone') echo 'background: rgba(33, 150, 243, 0.02);'; ?>">
                                            <td style="padding: 12px 16px; text-align: center;">
                                                <input type="checkbox"
                                                       class="knittnet-entry-checkbox"
                                                       data-entry-id="<?php echo esc_attr($first_prompt->id); ?>"
                                                       data-source="<?php echo esc_attr($data_source); ?>"
                                                       data-source-url="<?php echo esc_attr($source_url); ?>"
                                                       data-is-group="true"
                                                       data-chunk-count="<?php echo esc_attr($chunk_count); ?>">
                                            </td>
                                            <td style="padding: 12px 16px; font-size: 13px;">
                                                <?php if ($data_source === 'pinecone') : ?>
                                                    <?php echo esc_html($display_index + (($current_page - 1) * $per_page)); ?>
                                                <?php else : ?>
                                                    <?php echo esc_html($first_prompt->id); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="knittnet-content-cell" style="padding: 12px 16px; font-size: 13px;">
                                                <div class="knittnet-chunk-group-info">
                                                    <button type="button" class="knittnet-chunk-toggle" data-group-id="<?php echo esc_attr($group_id); ?>">
                                                        <span class="dashicons dashicons-arrow-right-alt2"></span>
                                                    </button>
                                                    <span class="knittnet-chunk-badge"><?php echo esc_html($chunk_count); ?> <?php esc_html_e('chunks', 'knittnet'); ?></span>
                                                    <span class="knittnet-chunk-preview">
                                                        <?php
                                                        $parent_content = isset($first_prompt->display_content) ? $first_prompt->display_content : $first_prompt->article_content;
                                                        $content_preview = mb_substr($parent_content, 0, 100);
                                                        echo esc_html($content_preview . '...');
                                                        ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="knittnet-url-cell" style="padding: 12px 16px; font-size: 13px;">
                                                <?php if (!empty($source_url) && strpos($source_url, 'knittnet://') !== 0 && strpos($source_url, '_ungrouped_') !== 0) : ?>
                                                    <a href="<?php echo esc_url($source_url); ?>" target="_blank" style="color: var(--mxch-primary); text-decoration: none;">
                                                        <span class="dashicons dashicons-external" style="font-size: 14px;"></span>
                                                        <?php esc_html_e('View Source', 'knittnet'); ?>
                                                    </a>
                                                <?php else : ?>
                                                    <span style="color: var(--mxch-text-muted);"><?php esc_html_e('Manual Content', 'knittnet'); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="knittnet-actions-cell" style="padding: 12px 16px; white-space: nowrap;">
                                                <button type="button"
                                                        class="mxch-btn mxch-btn-ghost mxch-btn-sm knittnet-inspect-entry-btn"
                                                        data-source-url="<?php echo esc_attr($source_url); ?>"
                                                        data-entry-id="<?php echo esc_attr($first_prompt->id); ?>"
                                                        data-data-source="<?php echo esc_attr($data_source); ?>"
                                                        data-bot-id="<?php echo esc_attr($current_bot_id); ?>"
                                                        data-nonce="<?php echo wp_create_nonce('knittnet_inspect_entry_nonce'); ?>"
                                                        title="<?php esc_attr_e('View indexed content', 'knittnet'); ?>">
                                                    <span class="dashicons dashicons-visibility" style="font-size: 14px;"></span>
                                                </button>
                                                <?php if ($data_source !== 'pinecone') : ?>
                                                <button type="button"
                                                        class="mxch-btn mxch-btn-ghost mxch-btn-sm knittnet-edit-entry-btn"
                                                        data-source-url="<?php echo esc_attr($source_url); ?>"
                                                        data-entry-id="<?php echo esc_attr($first_prompt->id); ?>"
                                                        data-data-source="<?php echo esc_attr($data_source); ?>"
                                                        data-bot-id="<?php echo esc_attr($current_bot_id); ?>"
                                                        data-nonce="<?php echo wp_create_nonce('knittnet_edit_entry_nonce'); ?>"
                                                        title="<?php esc_attr_e('Edit content', 'knittnet'); ?>">
                                                    <span class="dashicons dashicons-edit" style="font-size: 14px;"></span>
                                                </button>
                                                <?php endif; ?>
                                                <button type="button"
                                                        class="mxch-btn mxch-btn-ghost mxch-btn-sm delete-button-group"
                                                        data-source-url="<?php echo esc_attr($source_url); ?>"
                                                        data-chunk-count="<?php echo esc_attr($chunk_count); ?>"
                                                        data-data-source="<?php echo esc_attr($data_source); ?>"
                                                        data-bot-id="<?php echo esc_attr($current_bot_id); ?>"
                                                        data-nonce="<?php echo wp_create_nonce('knittnet_delete_chunks_nonce'); ?>"
                                                        style="color: var(--mxch-error);"
                                                        title="<?php esc_attr_e('Delete all chunks', 'knittnet'); ?>">
                                                    <span class="dashicons dashicons-trash" style="font-size: 14px;"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                        // Render hidden chunk rows
                                        foreach ($group as $chunk_index => $prompt) :
                                            $meta_chunk_index = isset($prompt->chunk_metadata['chunk_index']) ? intval($prompt->chunk_metadata['chunk_index']) : $chunk_index;
                                            $meta_total_chunks = isset($prompt->chunk_metadata['total_chunks']) ? intval($prompt->chunk_metadata['total_chunks']) : $chunk_count;
                                            $content = isset($prompt->display_content) ? $prompt->display_content : $prompt->article_content;
                                            $preview_length = 150;
                                            $content_preview = mb_strlen($content) > $preview_length
                                                ? mb_substr($content, 0, $preview_length) . '...'
                                                : $content;
                                        ?>
                                        <tr id="prompt-<?php echo esc_attr($prompt->id); ?>"
                                            class="knittnet-chunk-row <?php echo esc_attr($group_id); ?>"
                                            data-source="<?php echo esc_attr($data_source); ?>"
                                            style="display: none; background: #f8f9fa; border-bottom: 1px solid var(--mxch-card-border);">
                                            <td style="padding: 12px 16px; text-align: center;">
                                                <!-- Checkbox placeholder for chunk rows -->
                                            </td>
                                            <td style="padding: 12px 16px 12px 30px; font-size: 13px;">
                                                <!-- Hidden ID column for chunks -->
                                            </td>
                                            <td class="knittnet-content-cell" style="padding: 12px 16px; font-size: 13px;">
                                                <div class="knittnet-accordion-wrapper">
                                                    <div class="knittnet-content-preview">
                                                        <span class="knittnet-chunk-indicator" style="margin-right: 10px; color: var(--mxch-text-secondary); font-size: 12px;">
                                                            <?php printf(esc_html__('Chunk %d of %d', 'knittnet'), $meta_chunk_index + 1, $meta_total_chunks); ?>
                                                        </span>
                                                        <span class="preview-text"><?php echo esc_html($content_preview); ?></span>
                                                        <?php if (mb_strlen($content) > $preview_length) : ?>
                                                            <button class="knittnet-expand-toggle" type="button">
                                                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="knittnet-content-full" style="display: none;">
                                                        <div class="content-view">
                                                            <?php
                                                            if (preg_match('/[\x{0590}-\x{05FF}]/u', $content)) {
                                                                echo '<div dir="rtl" lang="he" class="rtl-content">';
                                                                echo wp_kses_post(wpautop($content));
                                                                echo '</div>';
                                                            } else {
                                                                echo wp_kses_post(wpautop($content));
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="knittnet-url-cell" style="padding: 12px 16px; font-size: 13px;">
                                                <span class="knittnet-chunk-label" style="color: var(--mxch-text-muted);"><?php esc_html_e('Same as parent', 'knittnet'); ?></span>
                                            </td>
                                            <td class="knittnet-actions-cell" style="padding: 12px 16px;">
                                                <span class="knittnet-chunk-label" style="color: var(--mxch-text-muted);"><?php esc_html_e('Managed by group', 'knittnet'); ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else :
                                        // Single entry - display normally with accordion
                                        $prompt = $first_prompt;
                                        $content = isset($prompt->display_content) ? $prompt->display_content : $prompt->article_content;
                                        $preview_length = 150;
                                        $content_preview = mb_strlen($content) > $preview_length
                                            ? mb_substr($content, 0, $preview_length) . '...'
                                            : $content;
                                    ?>
                                    <tr id="prompt-<?php echo esc_attr($prompt->id); ?>"
                                        data-source="<?php echo esc_attr($data_source); ?>"
                                        style="border-bottom: 1px solid var(--mxch-card-border); <?php if ($data_source === 'pinecone') echo 'background: rgba(33, 150, 243, 0.02);'; ?>">
                                        <td style="padding: 12px 16px; text-align: center;">
                                            <input type="checkbox"
                                                   class="knittnet-entry-checkbox"
                                                   data-entry-id="<?php echo esc_attr($prompt->id); ?>"
                                                   data-source="<?php echo esc_attr($data_source); ?>"
                                                   data-source-url="<?php echo esc_attr($source_url); ?>"
                                                   data-is-group="false">
                                        </td>
                                        <td style="padding: 12px 16px; font-size: 13px;">
                                            <?php if ($data_source === 'pinecone') : ?>
                                                <?php echo esc_html($display_index + (($current_page - 1) * $per_page)); ?>
                                            <?php else : ?>
                                                <?php echo esc_html($prompt->id); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="knittnet-content-cell" style="padding: 12px 16px; font-size: 13px;">
                                            <div class="knittnet-accordion-wrapper">
                                                <div class="knittnet-content-preview">
                                                    <span class="preview-text"><?php echo esc_html($content_preview); ?></span>
                                                    <?php if (mb_strlen($content) > $preview_length) : ?>
                                                        <button class="knittnet-expand-toggle" type="button">
                                                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="knittnet-content-full" style="display: none;">
                                                    <div class="content-view">
                                                        <?php
                                                        if (preg_match('/[\x{0590}-\x{05FF}]/u', $content)) {
                                                            echo '<div dir="rtl" lang="he" class="rtl-content">';
                                                            echo wp_kses_post(wpautop($content));
                                                            echo '</div>';
                                                        } else {
                                                            echo wp_kses_post(wpautop($content));
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="knittnet-url-cell" style="padding: 12px 16px; font-size: 13px;">
                                            <?php
                                            $actual_source = $source_url;
                                            if (strpos($source_url, '_ungrouped_') === 0) {
                                                $actual_source = $prompt->source_url ?? '';
                                            }
                                            if (!empty($actual_source) && strpos($actual_source, 'knittnet://') !== 0) : ?>
                                                <a href="<?php echo esc_url($actual_source); ?>" target="_blank" style="color: var(--mxch-primary); text-decoration: none;">
                                                    <span class="dashicons dashicons-external" style="font-size: 14px;"></span>
                                                    <?php esc_html_e('View', 'knittnet'); ?>
                                                </a>
                                            <?php else : ?>
                                                <span style="color: var(--mxch-text-muted);"><?php esc_html_e('Manual', 'knittnet'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 12px 16px; white-space: nowrap;">
                                            <button type="button"
                                                    class="mxch-btn mxch-btn-ghost mxch-btn-sm knittnet-inspect-entry-btn"
                                                    data-source-url="<?php echo esc_attr($prompt->source_url ?? ''); ?>"
                                                    data-entry-id="<?php echo esc_attr($prompt->id); ?>"
                                                    data-data-source="<?php echo esc_attr($data_source); ?>"
                                                    data-bot-id="<?php echo esc_attr($current_bot_id); ?>"
                                                    data-nonce="<?php echo wp_create_nonce('knittnet_inspect_entry_nonce'); ?>"
                                                    title="<?php esc_attr_e('View indexed content', 'knittnet'); ?>">
                                                <span class="dashicons dashicons-visibility" style="font-size: 14px;"></span>
                                            </button>
                                            <?php if ($data_source !== 'pinecone') : ?>
                                            <button type="button"
                                                    class="mxch-btn mxch-btn-ghost mxch-btn-sm knittnet-edit-entry-btn"
                                                    data-source-url="<?php echo esc_attr($prompt->source_url ?? ''); ?>"
                                                    data-entry-id="<?php echo esc_attr($prompt->id); ?>"
                                                    data-data-source="<?php echo esc_attr($data_source); ?>"
                                                    data-bot-id="<?php echo esc_attr($current_bot_id); ?>"
                                                    data-nonce="<?php echo wp_create_nonce('knittnet_edit_entry_nonce'); ?>"
                                                    title="<?php esc_attr_e('Edit content', 'knittnet'); ?>">
                                                <span class="dashicons dashicons-edit" style="font-size: 14px;"></span>
                                            </button>
                                            <?php endif; ?>
                                            <?php if ($data_source === 'pinecone') : ?>
                                                <button type="button" class="mxch-btn mxch-btn-ghost mxch-btn-sm delete-button-ajax" data-vector-id="<?php echo esc_attr($prompt->id); ?>" data-bot-id="<?php echo esc_attr($current_bot_id); ?>" data-nonce="<?php echo wp_create_nonce('knittnet_delete_pinecone_prompt_nonce'); ?>" style="color: var(--mxch-error);">
                                                    <span class="dashicons dashicons-trash" style="font-size: 14px;"></span>
                                                </button>
                                            <?php else : ?>
                                                <button type="button" class="mxch-btn mxch-btn-ghost mxch-btn-sm delete-button-wordpress" data-entry-id="<?php echo esc_attr($prompt->id); ?>" data-bot-id="<?php echo esc_attr($current_bot_id); ?>" data-nonce="<?php echo wp_create_nonce('knittnet_delete_wordpress_prompt_nonce'); ?>" style="color: var(--mxch-error);">
                                                    <span class="dashicons dashicons-trash" style="font-size: 14px;"></span>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination wrapper - always present so JS can populate it after processing -->
                <div id="knittnet-kb-pagination" class="knittnet-kb-pagination-wrapper" style="<?php echo $total_pages > 1 ? 'padding: 16px; border-top: 1px solid var(--mxch-card-border); text-align: center;' : ''; ?>" data-current-page="<?php echo esc_attr($current_page); ?>" data-total-pages="<?php echo esc_attr($total_pages); ?>" data-search="<?php echo esc_attr($search_query); ?>" data-content-type="<?php echo esc_attr($content_type_filter); ?>">
                    <?php if ($total_pages > 1) : ?>
                    <div class="knittnet-ajax-pagination" data-current-page="<?php echo esc_attr($current_page); ?>" data-total-pages="<?php echo esc_attr($total_pages); ?>">
                        <?php if ($current_page > 1) : ?>
                            <a href="#" class="knittnet-page-link" data-page="<?php echo ($current_page - 1); ?>"><?php esc_html_e('&laquo; Previous', 'knittnet'); ?></a>
                        <?php endif; ?>

                        <?php
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);

                        if ($start_page > 1) {
                            echo '<a href="#" class="knittnet-page-link" data-page="1">1</a> ';
                            if ($start_page > 2) {
                                echo '<span class="knittnet-page-dots">...</span> ';
                            }
                        }

                        for ($i = $start_page; $i <= $end_page; $i++) {
                            if ($i == $current_page) {
                                echo '<span class="knittnet-page-current">' . $i . '</span> ';
                            } else {
                                echo '<a href="#" class="knittnet-page-link" data-page="' . $i . '">' . $i . '</a> ';
                            }
                        }

                        if ($end_page < $total_pages) {
                            if ($end_page < $total_pages - 1) {
                                echo '<span class="knittnet-page-dots">...</span> ';
                            }
                            echo '<a href="#" class="knittnet-page-link" data-page="' . $total_pages . '">' . $total_pages . '</a> ';
                        }
                        ?>

                        <?php if ($current_page < $total_pages) : ?>
                            <a href="#" class="knittnet-page-link" data-page="<?php echo ($current_page + 1); ?>"><?php esc_html_e('Next &raquo;', 'knittnet'); ?></a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Auto-Sync Section
 */
function knittnet_render_auto_sync_section($knowledge_manager) {
    ?>
    <div id="auto-sync" class="mxch-section">
        <div class="mxch-content-header">
            <h1 class="mxch-content-title"><?php esc_html_e('Auto-Sync Settings', 'knittnet'); ?></h1>
            <p class="mxch-content-subtitle"><?php esc_html_e('Automatically sync WordPress content to your knowledge base when published or updated.', 'knittnet'); ?></p>
        </div>

        <div class="mxch-card">
            <div class="mxch-card-body knittnet-autosave-section">
                <div class="mxch-field">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <label class="knittnet-toggle-switch">
                            <input type="checkbox" name="knittnet_auto_sync_posts" class="knittnet-autosave-field" value="1" data-nonce="<?php echo wp_create_nonce('knittnet_prompts_setting_nonce'); ?>" <?php checked(get_option('knittnet_auto_sync_posts', '0'), '1'); ?>>
                            <span class="knittnet-toggle-slider"></span>
                        </label>
                        <span style="font-weight: 500;"><?php esc_html_e('Auto-sync Posts', 'knittnet'); ?></span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <label class="knittnet-toggle-switch">
                            <input type="checkbox" name="knittnet_auto_sync_pages" class="knittnet-autosave-field" value="1" data-nonce="<?php echo wp_create_nonce('knittnet_prompts_setting_nonce'); ?>" <?php checked(get_option('knittnet_auto_sync_pages', '0'), '1'); ?>>
                            <span class="knittnet-toggle-slider"></span>
                        </label>
                        <span style="font-weight: 500;"><?php esc_html_e('Auto-sync Pages', 'knittnet'); ?></span>
                    </div>
                </div>

                <div class="mxch-field" style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--mxch-card-border);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <label class="knittnet-toggle-switch">
                            <input type="checkbox" name="knittnet_auto_sync_acf_pdfs" class="knittnet-autosave-field" value="1" data-nonce="<?php echo wp_create_nonce('knittnet_prompts_setting_nonce'); ?>" <?php checked(get_option('knittnet_auto_sync_acf_pdfs', '0'), '1'); ?>>
                            <span class="knittnet-toggle-slider"></span>
                        </label>
                        <span style="font-weight: 500;"><?php esc_html_e('Extract text from PDFs in ACF fields on save', 'knittnet'); ?></span>
                    </div>
                    <p class="mxch-field-description" style="margin-top: 8px;"><?php esc_html_e('When ON, editor saves re-extract every PDF referenced in ACF fields. Default OFF — saves stay fast and don\'t re-parse the same PDFs on every edit. The manual content selector has its own per-batch checkbox; this setting only controls the auto-sync path.', 'knittnet'); ?></p>
                </div>

                <div style="margin-top: 24px;">
                    <button id="knittnet-custom-post-types-toggle" class="mxch-btn mxch-btn-secondary">
                        <?php esc_html_e('Advanced Custom Post Sync Settings', 'knittnet'); ?>
                        <span style="margin-left: 5px;">▼</span>
                    </button>
                    <div id="knittnet-custom-post-types-container" style="display: none; margin-top: 16px; padding: 16px; background: #f8fafc; border-radius: var(--mxch-radius-md);">
                        <h4 style="margin: 0 0 12px 0;"><?php esc_html_e('Sync Custom Post Types', 'knittnet'); ?></h4>
                        <?php
                        $post_types = $knowledge_manager->knittnet_get_public_post_types();
                        unset($post_types['post'], $post_types['page']);

                        if (!empty($post_types)) {
                            foreach ($post_types as $post_type => $label) {
                                $option_name = 'knittnet_auto_sync_' . $post_type;
                                $is_enabled = get_option($option_name, '0');
                                ?>
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                    <label class="knittnet-toggle-switch">
                                        <input type="checkbox" name="<?php echo esc_attr($option_name); ?>" class="knittnet-autosave-field" value="1" data-nonce="<?php echo wp_create_nonce('knittnet_prompts_setting_nonce'); ?>" <?php checked($is_enabled, '1'); ?>>
                                        <span class="knittnet-toggle-slider"></span>
                                    </label>
                                    <span><?php echo esc_html($label); ?> (<?php echo esc_html($post_type); ?>)</span>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<p style="color: var(--mxch-text-muted);">' . esc_html__('No custom post types found.', 'knittnet') . '</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Chunking Section
 */
function knittnet_render_chunking_section() {
    $chunking_settings = KnittNet_Chunker::get_settings();
    $chunking_enabled = $chunking_settings['chunking_enabled'];
    $chunk_size = $chunking_settings['chunk_size'];
    ?>
    <div id="chunking" class="mxch-section">
        <div class="mxch-content-header">
            <h1 class="mxch-content-title"><?php esc_html_e('Content Chunking', 'knittnet'); ?></h1>
            <p class="mxch-content-subtitle"><?php esc_html_e('Split large content into smaller segments for more accurate semantic search.', 'knittnet'); ?></p>
        </div>

        <div class="mxch-card">
            <div class="mxch-card-body knittnet-autosave-section">
                <div class="mxch-notice mxch-notice-info" style="margin-bottom: 20px;">
                    <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span><?php esc_html_e('Chunking improves retrieval quality for long documents. All chunks are reassembled before sending to the AI. Only applies to new submissions.', 'knittnet'); ?></span>
                </div>

                <div class="mxch-field">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        <label class="knittnet-toggle-switch">
                            <input type="checkbox" name="knittnet_chunking_enabled" id="knittnet_chunking_enabled" class="knittnet-autosave-field" value="1" data-option-name="knittnet_chunking_enabled" data-nonce="<?php echo wp_create_nonce('knittnet_prompts_setting_nonce'); ?>" <?php checked($chunking_enabled, true); ?>>
                            <span class="knittnet-toggle-slider"></span>
                        </label>
                        <span style="font-weight: 500;"><?php esc_html_e('Enable Content Chunking', 'knittnet'); ?></span>
                    </div>
                </div>

                <div class="mxch-field">
                    <label class="mxch-field-label" for="knittnet_chunk_size"><?php esc_html_e('Chunk Size (characters)', 'knittnet'); ?></label>
                    <input type="number" name="knittnet_chunk_size" id="knittnet_chunk_size" class="mxch-input knittnet-autosave-field" value="<?php echo esc_attr($chunk_size); ?>" min="1000" max="10000" step="500" data-option-name="knittnet_chunk_size" data-nonce="<?php echo wp_create_nonce('knittnet_prompts_setting_nonce'); ?>" style="max-width: 200px;">
                    <p class="mxch-field-description"><?php esc_html_e('Recommended: 4000 characters (~1000 tokens). Range: 1000-10000. Larger chunks preserve more context.', 'knittnet'); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Role Restrictions Section
 */
function knittnet_render_role_restrictions_section($knowledge_manager) {
    $role_options = array(
        'public' => __('Public (Everyone)', 'knittnet'),
        'logged_in' => __('Logged In Users', 'knittnet'),
        'subscriber' => __('Subscribers & Above', 'knittnet'),
        'contributor' => __('Contributors & Above', 'knittnet'),
        'author' => __('Authors & Above', 'knittnet'),
        'editor' => __('Editors & Above', 'knittnet'),
        'administrator' => __('Administrators Only', 'knittnet')
    );
    ?>
    <div id="role-restrictions" class="mxch-section">
        <div class="mxch-content-header">
            <h1 class="mxch-content-title"><?php esc_html_e('Role-Based Content Restrictions', 'knittnet'); ?></h1>
            <p class="mxch-content-subtitle"><?php esc_html_e('Automatically restrict content access based on WordPress tags.', 'knittnet'); ?></p>
        </div>

        <div class="mxch-card">
            <div class="mxch-card-body">
                <div class="mxch-notice mxch-notice-info" style="margin-bottom: 20px;">
                    <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span><strong><?php esc_html_e('How it works:', 'knittnet'); ?></strong> <?php esc_html_e('Add a tag below and select which role should have access. Content with that tag will be restricted to that role level.', 'knittnet'); ?></span>
                </div>

                <h4 style="margin: 0 0 16px 0;"><?php esc_html_e('Add Tag-Role Mapping', 'knittnet'); ?></h4>
                <div style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; margin-bottom: 24px;">
                    <div class="mxch-field" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                        <label class="mxch-field-label" for="knittnet-tag-input"><?php esc_html_e('Tag (name or slug)', 'knittnet'); ?></label>
                        <input type="text" id="knittnet-tag-input" class="mxch-input" placeholder="<?php esc_attr_e('e.g., Premium Content or premium-content', 'knittnet'); ?>">
                        <span class="mxch-field-hint"><?php esc_html_e('Enter an existing post tag by its display name or its slug.', 'knittnet'); ?></span>
                    </div>
                    <div class="mxch-field" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                        <label class="mxch-field-label" for="knittnet-role-select"><?php esc_html_e('Required Role', 'knittnet'); ?></label>
                        <select id="knittnet-role-select" class="mxch-select">
                            <?php foreach ($role_options as $role_key => $role_label) : ?>
                                <option value="<?php echo esc_attr($role_key); ?>"><?php echo esc_html($role_label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="button" id="knittnet-add-tag-role" class="mxch-btn mxch-btn-primary">
                        <span class="dashicons dashicons-plus-alt" style="font-size: 16px;"></span>
                        <?php esc_html_e('Add Mapping', 'knittnet'); ?>
                    </button>
                </div>

                <h4 style="margin: 24px 0 16px 0;"><?php esc_html_e('Current Tag-Role Mappings', 'knittnet'); ?></h4>
                <div id="knittnet-mappings-container">
                    <div class="knittnet-loading-mappings" style="text-align: center; padding: 20px; color: var(--mxch-text-muted);">
                        <?php esc_html_e('Loading mappings...', 'knittnet'); ?>
                    </div>
                </div>
                <div id="knittnet-no-mappings" style="display: none; text-align: center; padding: 40px; color: var(--mxch-text-muted);">
                    <span class="dashicons dashicons-tag" style="font-size: 48px; opacity: 0.3;"></span>
                    <p><?php esc_html_e('No tag-role mappings yet. Add your first mapping above.', 'knittnet'); ?></p>
                </div>

                <div style="margin-top: 30px; padding-top: 24px; border-top: 1px solid var(--mxch-card-border);">
                    <h4 style="margin: 0 0 12px 0;"><?php esc_html_e('Bulk Update Existing Content', 'knittnet'); ?></h4>
                    <p class="mxch-field-description" style="margin-bottom: 16px;"><?php esc_html_e('Apply role restrictions to all existing content that has the mapped tags.', 'knittnet'); ?></p>
                    <button type="button" id="knittnet-bulk-update-roles" class="mxch-btn mxch-btn-secondary">
                        <span class="dashicons dashicons-update" style="font-size: 16px;"></span>
                        <?php esc_html_e('Update All Existing Content', 'knittnet'); ?>
                    </button>
                    <div id="knittnet-bulk-update-progress" style="display: none; margin-top: 15px;">
                        <div style="height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                            <div class="knittnet-progress-fill" style="height: 100%; background: var(--mxch-primary); width: 0%; transition: width 0.3s;"></div>
                        </div>
                        <p class="knittnet-progress-text" style="margin-top: 10px; color: var(--mxch-text-secondary);"><?php esc_html_e('Processing...', 'knittnet'); ?></p>
                    </div>
                    <div id="knittnet-bulk-update-result" style="display: none; margin-top: 15px;"></div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render ACF Fields Section
 */
function knittnet_render_acf_fields_section($knowledge_manager) {
    $excluded_fields = get_option('knittnet_acf_excluded_fields', array());
    if (!is_array($excluded_fields)) {
        $excluded_fields = array();
    }
    ?>
    <div id="acf-fields" class="mxch-section">
        <div class="mxch-content-header">
            <h1 class="mxch-content-title"><?php esc_html_e('ACF Field Settings', 'knittnet'); ?></h1>
            <p class="mxch-content-subtitle"><?php esc_html_e('Control which Advanced Custom Fields are included in knowledge base embeddings.', 'knittnet'); ?></p>
        </div>

        <div class="mxch-card">
            <div class="mxch-card-body knittnet-autosave-section">
                <?php if (!function_exists('acf_get_field_groups')): ?>
                    <div class="mxch-notice mxch-notice-info">
                        <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <span><?php esc_html_e('Advanced Custom Fields (ACF) plugin is not detected. Install and activate ACF to use this feature.', 'knittnet'); ?></span>
                    </div>
                <?php else:
                    $all_acf_fields = $knowledge_manager->knittnet_get_all_acf_fields();

                    if (empty($all_acf_fields)): ?>
                        <div class="mxch-notice mxch-notice-info">
                            <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span><?php esc_html_e('No ACF field groups found. Create field groups in ACF to control which fields are included in embeddings.', 'knittnet'); ?></span>
                        </div>
                    <?php else: ?>
                        <div class="mxch-notice mxch-notice-info" style="margin-bottom: 20px;">
                            <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span><?php esc_html_e('Toggle fields ON to include them in knowledge base embeddings. Toggle OFF to exclude sensitive or irrelevant fields.', 'knittnet'); ?></span>
                        </div>

                        <?php foreach ($all_acf_fields as $group_title => $fields): ?>
                            <div class="knittnet-acf-field-group" style="margin-bottom: 24px;">
                                <h4 style="margin: 0 0 12px 0; color: var(--mxch-text-primary); font-weight: 600;">
                                    <?php echo esc_html($group_title); ?>
                                </h4>
                                <div style="background: #f8fafc; border-radius: var(--mxch-radius-md); padding: 16px;">
                                    <?php foreach ($fields as $field):
                                        $field_name = $field['name'];
                                        $is_enabled = !in_array($field_name, $excluded_fields);
                                    ?>
                                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                            <div>
                                                <span style="font-weight: 500;"><?php echo esc_html($field['label']); ?></span>
                                                <span style="color: var(--mxch-text-muted); font-size: 12px; margin-left: 8px;">(<?php echo esc_html($field_name); ?>)</span>
                                                <span style="color: var(--mxch-text-muted); font-size: 11px; margin-left: 8px; background: #e2e8f0; padding: 2px 6px; border-radius: 4px;"><?php echo esc_html($field['type']); ?></span>
                                            </div>
                                            <label class="knittnet-toggle-switch">
                                                <input type="checkbox"
                                                       name="knittnet_acf_field_<?php echo esc_attr($field_name); ?>"
                                                       class="knittnet-autosave-field"
                                                       value="on"
                                                       data-nonce="<?php echo wp_create_nonce('knittnet_prompts_setting_nonce'); ?>"
                                                       <?php checked($is_enabled, true); ?>>
                                                <span class="knittnet-toggle-slider"></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Custom Meta Section
 */
function knittnet_render_custom_meta_section() {
    $whitelist = get_option('knittnet_custom_meta_whitelist', '');
    ?>
    <div id="custom-meta" class="mxch-section">
        <div class="mxch-content-header">
            <h1 class="mxch-content-title"><?php esc_html_e('Custom Post Meta', 'knittnet'); ?></h1>
            <p class="mxch-content-subtitle"><?php esc_html_e('Include custom post meta fields (non-ACF) in knowledge base embeddings.', 'knittnet'); ?></p>
        </div>

        <div class="mxch-card">
            <div class="mxch-card-body knittnet-autosave-section">
                <div class="mxch-notice mxch-notice-info" style="margin-bottom: 20px;">
                    <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span><?php esc_html_e('Enter post meta keys to include in embeddings. This is useful for OptionTree fields, theme meta boxes, or any custom post meta that is not managed by ACF.', 'knittnet'); ?></span>
                </div>

                <div class="mxch-field">
                    <label class="mxch-field-label" for="knittnet_custom_meta_whitelist">
                        <?php esc_html_e('Meta Key Whitelist', 'knittnet'); ?>
                    </label>
                    <textarea
                        name="knittnet_custom_meta_whitelist"
                        id="knittnet_custom_meta_whitelist"
                        class="mxch-textarea knittnet-autosave-field"
                        rows="6"
                        placeholder="speaker_profession&#10;speaker_company&#10;event_location&#10;_custom_field_key"
                        data-nonce="<?php echo wp_create_nonce('knittnet_prompts_setting_nonce'); ?>"
                        style="font-family: monospace;"
                    ><?php echo esc_textarea($whitelist); ?></textarea>
                    <p class="mxch-field-description">
                        <?php esc_html_e('Enter one meta key per line. These fields will be appended to the content during embedding. Only string values are included.', 'knittnet'); ?>
                    </p>
                </div>

                <div style="margin-top: 20px; padding: 16px; background: #f8fafc; border-radius: var(--mxch-radius-md);">
                    <h4 style="margin: 0 0 12px 0; font-size: 14px;"><?php esc_html_e('How to find meta keys:', 'knittnet'); ?></h4>
                    <ul style="margin: 0; padding-left: 20px; color: var(--mxch-text-secondary); font-size: 13px;">
                        <li><?php esc_html_e('Check your theme documentation for meta key names', 'knittnet'); ?></li>
                        <li><?php esc_html_e('Look in the wp_postmeta database table', 'knittnet'); ?></li>
                        <li><?php esc_html_e('Use a plugin like "Show Post Meta" to view meta keys on any post', 'knittnet'); ?></li>
                        <li><?php esc_html_e('Meta keys starting with underscore (_) are usually hidden/internal', 'knittnet'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Pinecone Section
 */
function knittnet_render_pinecone_section() {
    $pinecone_options = get_option('knittnet_pinecone_addon_options', array());
    $use_pinecone = $pinecone_options['knittnet_use_pinecone'] ?? '0';
    ?>
    <div id="pinecone" class="mxch-section">
        <div class="mxch-content-header">
            <h1 class="mxch-content-title"><?php esc_html_e('Pinecone Vector Database', 'knittnet'); ?></h1>
            <p class="mxch-content-subtitle"><?php esc_html_e('Configure Pinecone for enhanced search performance with larger knowledge bases.', 'knittnet'); ?></p>
        </div>

        <div class="mxch-card">
            <div class="mxch-card-body">
                <div class="mxch-notice mxch-notice-info" style="margin-bottom: 20px;">
                    <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span><strong><?php esc_html_e('Pinecone is optional.', 'knittnet'); ?></strong> <?php esc_html_e('KnittNet works without it. When enabled, content is stored in Pinecone for faster similarity searches.', 'knittnet'); ?></span>
                </div>

                <form method="post" action="options.php">
                    <?php settings_fields('knittnet_pinecone_addon_options'); ?>

                    <div class="mxch-field">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                            <label class="knittnet-toggle-switch">
                                <input type="checkbox" name="knittnet_pinecone_addon_options[knittnet_use_pinecone]" value="1" <?php checked($use_pinecone, '1'); ?>>
                                <span class="knittnet-toggle-slider"></span>
                            </label>
                            <span style="font-weight: 500;"><?php esc_html_e('Enable Pinecone Database', 'knittnet'); ?></span>
                        </div>
                    </div>

                    <div class="knittnet-pinecone-settings" <?php echo $use_pinecone ? '' : 'style="display: none;"'; ?>>
                        <?php if ($use_pinecone) : ?>
                            <div class="mxch-notice mxch-notice-success" style="margin-bottom: 20px;">
                                <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <span><?php esc_html_e('Pinecone is enabled. All new knowledge base content will be stored in Pinecone.', 'knittnet'); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="mxch-field">
                            <label class="mxch-field-label" for="knittnet_pinecone_api_key">
                                <?php esc_html_e('Pinecone API Key', 'knittnet'); ?> <span class="mxch-field-label-required">*</span>
                            </label>
                            <input type="password" id="knittnet_pinecone_api_key" name="knittnet_pinecone_addon_options[knittnet_pinecone_api_key]" value="<?php echo esc_attr($pinecone_options['knittnet_pinecone_api_key'] ?? ''); ?>" class="mxch-input" placeholder="pcsk_...">
                            <p class="mxch-field-description">
                                <?php esc_html_e('Found in your Pinecone dashboard under API Keys.', 'knittnet'); ?>
                                <a href="https://app.pinecone.io/" target="_blank"><?php esc_html_e('Open Pinecone Dashboard', 'knittnet'); ?></a>
                            </p>
                        </div>

                        <div class="mxch-field">
                            <label class="mxch-field-label" for="knittnet_pinecone_environment"><?php esc_html_e('Region', 'knittnet'); ?></label>
                            <input type="text" id="knittnet_pinecone_environment" name="knittnet_pinecone_addon_options[knittnet_pinecone_environment]" value="<?php echo esc_attr($pinecone_options['knittnet_pinecone_environment'] ?? ''); ?>" class="mxch-input" placeholder="e.g., gcp-starter">
                            <p class="mxch-field-description"><?php esc_html_e('Your Pinecone environment/region (e.g., gcp-starter, us-west1-gcp)', 'knittnet'); ?></p>
                        </div>

                        <div class="mxch-field">
                            <label class="mxch-field-label" for="knittnet_pinecone_index">
                                <?php esc_html_e('Index Name', 'knittnet'); ?> <span class="mxch-field-label-required">*</span>
                            </label>
                            <input type="text" id="knittnet_pinecone_index" name="knittnet_pinecone_addon_options[knittnet_pinecone_index]" value="<?php echo esc_attr($pinecone_options['knittnet_pinecone_index'] ?? ''); ?>" class="mxch-input" placeholder="e.g., my-wordpress-vectors">
                            <p class="mxch-field-description"><?php esc_html_e('The name of your Pinecone index. Must be created in your Pinecone dashboard first.', 'knittnet'); ?></p>
                        </div>

                        <div class="mxch-field">
                            <label class="mxch-field-label" for="knittnet_pinecone_host">
                                <?php esc_html_e('Pinecone Host', 'knittnet'); ?> <span class="mxch-field-label-required">*</span>
                            </label>
                            <input type="text" id="knittnet_pinecone_host" name="knittnet_pinecone_addon_options[knittnet_pinecone_host]" value="<?php echo esc_attr($pinecone_options['knittnet_pinecone_host'] ?? ''); ?>" class="mxch-input" placeholder="e.g., my-index-xyz123.svc.pinecone.io">
                            <p class="mxch-field-description"><?php esc_html_e('The hostname from your Pinecone index URL (exclude https://). Found in index details.', 'knittnet'); ?></p>
                        </div>
                    </div>

                    <?php submit_button(__('Save Pinecone Settings', 'knittnet'), 'primary', 'submit', true, array('class' => 'mxch-btn mxch-btn-primary')); ?>
                </form>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render OpenAI Vector Store Section
 */
function knittnet_render_openai_vectorstore_section() {
    $vectorstore_options = get_option('knittnet_openai_vectorstore_options', array());
    $use_vectorstore = $vectorstore_options['knittnet_use_openai_vectorstore'] ?? '0';
    $vectorstore_ids = $vectorstore_options['knittnet_vectorstore_ids'] ?? '';
    $max_results = $vectorstore_options['knittnet_vectorstore_max_results'] ?? 5;

    // Get current chat model to check compatibility
    $knittnet_options = get_option('knittnet_options', array());
    $current_model = $knittnet_options['model'] ?? 'gpt-5.1-chat-latest';
    $is_openai_model = preg_match('/^(gpt-|o1-|o3-)/', $current_model);
    ?>
    <div id="openai-vectorstore" class="mxch-section">
        <div class="mxch-content-header">
            <h1 class="mxch-content-title"><?php esc_html_e('OpenAI Vector Store', 'knittnet'); ?></h1>
            <p class="mxch-content-subtitle"><?php esc_html_e('Use OpenAI\'s hosted vector database for knowledge retrieval. Your documents are stored and searched directly on OpenAI\'s platform.', 'knittnet'); ?></p>
        </div>

        <div class="mxch-card">
            <div class="mxch-card-body">
                <div class="mxch-notice mxch-notice-info" style="margin-bottom: 20px;">
                    <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span><strong><?php esc_html_e('Requires OpenAI Chat Model.', 'knittnet'); ?></strong> <?php esc_html_e('Vector Store search only works with OpenAI models (gpt-5.1-chat-latest, gpt-5-mini, etc.). Create Vector Stores in your OpenAI Dashboard.', 'knittnet'); ?></span>
                </div>

                <?php if (!$is_openai_model && $use_vectorstore === '1'): ?>
                <div class="mxch-notice mxch-notice-warning" style="margin-bottom: 20px;">
                    <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <span><strong><?php esc_html_e('Model Incompatible:', 'knittnet'); ?></strong> <?php printf(esc_html__('Your current chat model (%s) is not an OpenAI model. Vector Store search will not work until you switch to an OpenAI model.', 'knittnet'), esc_html($current_model)); ?></span>
                </div>
                <?php endif; ?>

                <form method="post" action="options.php" id="knittnet-vectorstore-form">
                    <?php settings_fields('knittnet_openai_vectorstore_options'); ?>

                    <div class="mxch-field">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                            <label class="knittnet-toggle-switch">
                                <input type="checkbox" name="knittnet_openai_vectorstore_options[knittnet_use_openai_vectorstore]" value="1" <?php checked($use_vectorstore, '1'); ?> id="knittnet_use_openai_vectorstore">
                                <span class="knittnet-toggle-slider"></span>
                            </label>
                            <span style="font-weight: 500;"><?php esc_html_e('Enable OpenAI Vector Store', 'knittnet'); ?></span>
                        </div>
                    </div>

                    <div class="knittnet-vectorstore-settings" <?php echo $use_vectorstore === '1' ? '' : 'style="display: none;"'; ?>>
                        <?php if ($use_vectorstore === '1'): ?>
                            <div class="mxch-notice mxch-notice-success" style="margin-bottom: 20px;">
                                <svg class="mxch-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <span><?php esc_html_e('OpenAI Vector Store is enabled. Queries will search your Vector Store for relevant content.', 'knittnet'); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="mxch-field">
                            <label class="mxch-field-label" for="knittnet_vectorstore_ids">
                                <?php esc_html_e('Vector Store ID(s)', 'knittnet'); ?> <span class="mxch-field-label-required">*</span>
                            </label>
                            <input type="text" id="knittnet_vectorstore_ids" name="knittnet_openai_vectorstore_options[knittnet_vectorstore_ids]" value="<?php echo esc_attr($vectorstore_ids); ?>" class="mxch-input" placeholder="vs_abc123xyz">
                            <p class="mxch-field-description">
                                <?php esc_html_e('Enter your Vector Store ID from the OpenAI Dashboard. For multiple stores, separate with commas.', 'knittnet'); ?>
                                <a href="https://platform.openai.com/storage/vector_stores" target="_blank"><?php esc_html_e('Open OpenAI Vector Store Dashboard', 'knittnet'); ?></a>
                            </p>
                        </div>

                        <div class="mxch-field">
                            <label class="mxch-field-label" for="knittnet_vectorstore_max_results">
                                <?php esc_html_e('Max Results', 'knittnet'); ?>
                            </label>
                            <select id="knittnet_vectorstore_max_results" name="knittnet_openai_vectorstore_options[knittnet_vectorstore_max_results]" class="mxch-input" style="width: auto;">
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php selected($max_results, $i); ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                            <p class="mxch-field-description"><?php esc_html_e('Maximum number of content chunks to retrieve from the Vector Store.', 'knittnet'); ?></p>
                        </div>
                    </div>

                    <?php submit_button(__('Save Vector Store Settings', 'knittnet'), 'primary', 'submit', true, array('class' => 'mxch-btn mxch-btn-primary')); ?>
                </form>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Content Selector Modal
 */
function knittnet_render_content_selector_modal() {
    ?>
    <div id="knittnet-kb-content-selector-modal" class="knittnet-kb-modal">
        <div class="knittnet-kb-modal-content">
            <div class="knittnet-kb-modal-header">
                <h3>
                    <?php esc_html_e('Select WordPress Content', 'knittnet'); ?><br>
                    <span class="knittnet-kb-header-note"><?php esc_html_e('(Content imported here will be tagged "In Knowledge Base")', 'knittnet'); ?></span>
                </h3>
                <span class="knittnet-kb-modal-close">&times;</span>
            </div>
            <div class="knittnet-kb-modal-filters">
                <div class="knittnet-kb-search-group">
                    <input type="text" id="knittnet-kb-content-search" placeholder="<?php esc_attr_e('Search...', 'knittnet'); ?>">
                </div>
                <div class="knittnet-kb-filter-group">
                    <select id="knittnet-kb-content-type-filter">
                        <option value="all"><?php esc_html_e('All Content Types', 'knittnet'); ?></option>
                        <option value="post"><?php esc_html_e('Posts', 'knittnet'); ?></option>
                        <option value="page"><?php esc_html_e('Pages', 'knittnet'); ?></option>
                        <?php
                        $post_types = get_post_types(array('public' => true), 'objects');
                        foreach ($post_types as $post_type) {
                            if (!in_array($post_type->name, array('post', 'page'))) {
                                echo '<option value="' . esc_attr($post_type->name) . '">' . esc_html($post_type->label) . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <select id="knittnet-kb-content-status-filter">
                        <option value="publish"><?php esc_html_e('Published', 'knittnet'); ?></option>
                        <option value="draft"><?php esc_html_e('Drafts', 'knittnet'); ?></option>
                        <option value="all"><?php esc_html_e('All Statuses', 'knittnet'); ?></option>
                    </select>
                    <select id="knittnet-kb-processed-filter">
                        <option value="all"><?php esc_html_e('All Content', 'knittnet'); ?></option>
                        <option value="processed"><?php esc_html_e('In Knowledge Base', 'knittnet'); ?></option>
                        <option value="unprocessed"><?php esc_html_e('Not In Knowledge Base', 'knittnet'); ?></option>
                    </select>
                </div>
            </div>
            <div class="knittnet-kb-content-selection">
                <div class="knittnet-kb-selection-header">
                    <label>
                        <input type="checkbox" id="knittnet-kb-select-all">
                        <?php esc_html_e('Select All on Page', 'knittnet'); ?>
                    </label>
                    <span class="knittnet-kb-selection-count">0 <?php esc_html_e('selected', 'knittnet'); ?></span>
                    <span class="knittnet-kb-selection-hint" style="margin-left: auto; font-size: 12px; color: var(--mxch-text-muted);">
                        <span class="dashicons dashicons-info-outline" style="font-size: 14px; vertical-align: middle;"></span>
                        <?php esc_html_e('Selections persist across pages', 'knittnet'); ?>
                    </span>
                </div>
                <div class="knittnet-kb-content-list">
                    <div class="knittnet-kb-loading">
                        <span class="knittnet-kb-spinner is-active"></span>
                        <?php esc_html_e('Loading content...', 'knittnet'); ?>
                    </div>
                </div>
            </div>
            <div class="knittnet-kb-modal-footer">
                <div class="knittnet-kb-pagination"></div>
                <div class="knittnet-kb-acf-pdf-option" style="display:flex; align-items:flex-start; gap:8px; padding:8px 0; font-size:12px; color: var(--mxch-text-secondary, #64748b);">
                    <input type="checkbox" id="knittnet-kb-acf-pdf-extract" style="margin-top:2px;">
                    <label for="knittnet-kb-acf-pdf-extract" style="cursor:pointer; line-height:1.4;">
                        <strong style="color: var(--mxch-text-primary, #1a1a2e);"><?php esc_html_e('Also extract text from PDFs linked in ACF File/Image fields', 'knittnet'); ?></strong><br>
                        <span><?php esc_html_e('Adds 200-500ms per post and ~30 KB to each KB entry. Recommended only if your ACF setup uses PDF attachments for primary content.', 'knittnet'); ?></span>
                    </label>
                </div>
                <div class="knittnet-kb-footer-actions">
                    <button type="button" id="knittnet-kb-process-selected" class="knittnet-kb-button-primary" disabled>
                        <?php esc_html_e('Process Selected Content', 'knittnet'); ?>
                        <span class="knittnet-kb-selected-count">(0)</span>
                    </button>
                    <button type="button" class="knittnet-kb-button-secondary knittnet-kb-modal-close">
                        <?php esc_html_e('Cancel', 'knittnet'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Edit Entry Modal
 */
function knittnet_render_edit_entry_modal() {
    ?>
    <div id="knittnet-kb-edit-modal" class="knittnet-kb-modal">
        <div class="knittnet-kb-modal-content knittnet-kb-edit-modal-content">
            <div class="knittnet-kb-modal-header">
                <h3 id="knittnet-kb-edit-title"><?php esc_html_e('Edit Knowledge Entry', 'knittnet'); ?></h3>
                <span class="knittnet-kb-modal-close" id="knittnet-kb-edit-close">&times;</span>
            </div>
            <div class="knittnet-kb-edit-body">
                <div class="knittnet-kb-edit-source" id="knittnet-kb-edit-source"></div>
                <textarea id="knittnet-kb-edit-textarea" class="knittnet-kb-edit-textarea" placeholder="<?php esc_attr_e('Loading content...', 'knittnet'); ?>"></textarea>
                <div class="knittnet-kb-edit-meta">
                    <span id="knittnet-kb-edit-charcount"></span>
                    <span id="knittnet-kb-edit-chunkinfo"></span>
                </div>
            </div>
            <div class="knittnet-kb-edit-footer">
                <div class="knittnet-kb-edit-notice" id="knittnet-kb-edit-notice"></div>
                <div class="knittnet-kb-edit-actions">
                    <button type="button" id="knittnet-kb-edit-cancel" class="mxch-btn mxch-btn-ghost">
                        <?php esc_html_e('Cancel', 'knittnet'); ?>
                    </button>
                    <button type="button" id="knittnet-kb-edit-save" class="mxch-btn mxch-btn-primary">
                        <span class="knittnet-kb-edit-save-text"><?php esc_html_e('Save & Re-embed', 'knittnet'); ?></span>
                        <span class="knittnet-kb-edit-save-spinner" style="display:none;">
                            <span class="knittnet-kb-spinner is-active" style="margin: 0;"></span>
                            <?php esc_html_e('Saving...', 'knittnet'); ?>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render the read-only Knowledge Inspector modal (plan-knittnet-20260628-d8cb4b).
 * Shows exactly what was indexed for an entry: the per-chunk stored text + lengths
 * and (for Pinecone) the stored metadata fields, rendered with the Testing tab's
 * own card components so the two surfaces feel like one system. Self-contained
 * markup + JS so it doesn't entangle the edit-modal init.
 */
function knittnet_render_inspect_entry_modal() {
    ?>
    <div id="knittnet-kb-inspect-modal" class="knittnet-kb-modal">
        <div class="knittnet-kb-modal-content knittnet-kb-inspect-modal-content">
            <div class="knittnet-kb-modal-header">
                <h3 id="knittnet-kb-inspect-title"><?php esc_html_e('Indexed Content', 'knittnet'); ?></h3>
                <span class="knittnet-kb-modal-close" id="knittnet-kb-inspect-close">&times;</span>
            </div>
            <div class="knittnet-kb-inspect-body">
                <div class="knittnet-kb-edit-source" id="knittnet-kb-inspect-source"></div>
                <div class="mxch-testing-results" id="knittnet-kb-inspect-results">
                    <div class="mxch-testing-no-data"><?php esc_html_e('Loading indexed content…', 'knittnet'); ?></div>
                </div>
            </div>
            <div class="knittnet-kb-edit-footer">
                <div class="knittnet-kb-edit-notice" id="knittnet-kb-inspect-notice"></div>
                <div class="knittnet-kb-edit-actions">
                    <button type="button" id="knittnet-kb-inspect-close-btn" class="mxch-btn mxch-btn-primary">
                        <?php esc_html_e('Close', 'knittnet'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
    (function() {
        'use strict';
        document.addEventListener('DOMContentLoaded', function() {
            var modal    = document.getElementById('knittnet-kb-inspect-modal');
            if (!modal) return;
            var closeX   = document.getElementById('knittnet-kb-inspect-close');
            var closeBtn = document.getElementById('knittnet-kb-inspect-close-btn');
            var sourceEl = document.getElementById('knittnet-kb-inspect-source');
            var resultsEl= document.getElementById('knittnet-kb-inspect-results');
            var titleEl  = document.getElementById('knittnet-kb-inspect-title');
            var noticeEl = document.getElementById('knittnet-kb-inspect-notice');

            var I18N = {
                title:    <?php echo wp_json_encode( esc_html__('Indexed Content', 'knittnet') ); ?>,
                loading:  <?php echo wp_json_encode( esc_html__('Loading indexed content…', 'knittnet') ); ?>,
                manual:   <?php echo wp_json_encode( esc_html__('Manual Content', 'knittnet') ); ?>,
                source:   <?php echo wp_json_encode( esc_html__('Source', 'knittnet') ); ?>,
                store:    <?php echo wp_json_encode( esc_html__('Storage', 'knittnet') ); ?>,
                type:     <?php echo wp_json_encode( esc_html__('Content type', 'knittnet') ); ?>,
                chunksLbl:<?php echo wp_json_encode( esc_html__('Chunks', 'knittnet') ); ?>,
                totalLen: <?php echo wp_json_encode( esc_html__('Total indexed length', 'knittnet') ); ?>,
                chunk:    <?php echo wp_json_encode( esc_html__('Chunk', 'knittnet') ); ?>,
                indexed:  <?php echo wp_json_encode( esc_html__('Indexed', 'knittnet') ); ?>,
                chars:    <?php echo wp_json_encode( esc_html__('chars', 'knittnet') ); ?>,
                showText: <?php echo wp_json_encode( esc_html__('Show indexed text', 'knittnet') ); ?>,
                hideText: <?php echo wp_json_encode( esc_html__('Hide indexed text', 'knittnet') ); ?>,
                wpStore:  <?php echo wp_json_encode( esc_html__('Local WordPress database', 'knittnet') ); ?>,
                pcStore:  <?php echo wp_json_encode( esc_html__('Pinecone', 'knittnet') ); ?>,
                meta:     <?php echo wp_json_encode( esc_html__('Vector metadata', 'knittnet') ); ?>,
                loadFail: <?php echo wp_json_encode( esc_html__('Failed to load indexed content.', 'knittnet') ); ?>,
                netErr:   <?php echo wp_json_encode( esc_html__('Network error: ', 'knittnet') ); ?>
            };

            function esc(str) {
                var d = document.createElement('div');
                d.textContent = (str === null || str === undefined) ? '' : String(str);
                return d.innerHTML;
            }
            function fmt(n) { try { return Number(n).toLocaleString(); } catch (e) { return n; } }

            document.addEventListener('click', function(e) {
                var btn = e.target.closest('.knittnet-inspect-entry-btn');
                if (btn) { e.preventDefault(); open(btn); }
            });
            if (closeX)   closeX.addEventListener('click', close);
            if (closeBtn) closeBtn.addEventListener('click', close);
            modal.addEventListener('click', function(e) { if (e.target === modal) close(); });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.classList.contains('active')) close();
            });

            function close() { modal.classList.remove('active'); }

            function open(btn) {
                var entry = {
                    sourceUrl:  btn.getAttribute('data-source-url') || '',
                    entryId:    btn.getAttribute('data-entry-id') || '',
                    dataSource: btn.getAttribute('data-data-source') || 'wordpress',
                    botId:      btn.getAttribute('data-bot-id') || 'default',
                    nonce:      btn.getAttribute('data-nonce') || ''
                };

                titleEl.textContent = I18N.title;
                noticeEl.textContent = '';
                noticeEl.className = 'knittnet-kb-edit-notice';
                resultsEl.innerHTML = '<div class="mxch-testing-no-data">' + esc(I18N.loading) + '</div>';

                if (entry.sourceUrl && entry.sourceUrl.indexOf('knittnet://') !== 0 && entry.sourceUrl.indexOf('_ungrouped_') !== 0) {
                    sourceEl.innerHTML = esc(I18N.source) + ': <a href="' + esc(entry.sourceUrl) + '" target="_blank" rel="noopener">' + esc(entry.sourceUrl) + '</a>';
                } else {
                    sourceEl.textContent = I18N.manual;
                }

                modal.classList.add('active');

                var fd = new FormData();
                fd.append('action', 'knittnet_inspect_entry');
                fd.append('nonce', entry.nonce);
                fd.append('source_url', entry.sourceUrl);
                fd.append('entry_id', entry.entryId);
                fd.append('data_source', entry.dataSource);
                fd.append('bot_id', entry.botId);

                fetch(ajaxurl, { method: 'POST', body: fd })
                    .then(function(r) { return r.json(); })
                    .then(function(resp) {
                        if (resp && resp.success) {
                            render(resp.data);
                        } else {
                            var msg = (resp && resp.data && resp.data.message) || I18N.loadFail;
                            resultsEl.innerHTML = '';
                            showNotice(msg, 'error');
                        }
                    })
                    .catch(function(err) {
                        resultsEl.innerHTML = '';
                        showNotice(I18N.netErr + err.message, 'error');
                    });
            }

            function render(data) {
                var storeLabel = data.store === 'pinecone' ? I18N.pcStore : I18N.wpStore;
                var html = '';

                // Summary card — what this entry is + how much was indexed.
                html += '<div class="match-card above-threshold">' +
                    '<div class="match-header">' +
                        '<div class="match-title"><span class="status-icon">&#128230;</span> ' + esc(data.chunk_count) + ' ' + esc(I18N.chunksLbl.toLowerCase()) + '</div>' +
                        '<span class="context-label">' + esc(storeLabel) + '</span>' +
                    '</div>' +
                    '<div class="mxch-testing-field"><label>' + esc(I18N.type) + '</label><code>' + esc(data.content_type || '—') + '</code> ' +
                        '&nbsp; <label style="display:inline; text-transform:none; letter-spacing:0; font-weight:600;">' + esc(I18N.totalLen) + ':</label> ' + esc(fmt(data.assembled_length)) + ' ' + esc(I18N.chars) + '</div>';
                if (data.metadata_note) {
                    html += '<div class="match-source"><span class="source-icon">&#8505;&#65039;</span> ' + esc(data.metadata_note) + '</div>';
                }
                html += '</div>';

                // One card per stored chunk — the actual embedded text.
                var chunks = data.chunks || [];
                chunks.forEach(function(chunk, i) {
                    var num = (chunk.index !== null && chunk.index !== undefined) ? (chunk.index + 1) : (i + 1);
                    var metaRows = '';
                    if (chunk.metadata && typeof chunk.metadata === 'object') {
                        var keys = Object.keys(chunk.metadata);
                        if (keys.length) {
                            metaRows += '<div class="chunk-detail-row chunk-used" style="display:block;"><span class="chunk-detail-num">' + esc(I18N.meta) + '</span></div>';
                            keys.forEach(function(k) {
                                metaRows += '<div class="chunk-detail-row"><span class="chunk-detail-num">' + esc(k) + '</span><span class="chunk-detail-score">' + esc(chunk.metadata[k]) + '</span></div>';
                            });
                        }
                    }
                    html += '<div class="match-card above-threshold">' +
                        '<div class="match-header">' +
                            '<div class="match-title"><span class="status-icon">&#10003;</span> ' + esc(I18N.chunk) + ' ' + esc(num) +
                                '<span class="chunk-summary">' + esc(fmt(chunk.length)) + ' ' + esc(I18N.chars) + '</span>' +
                            '</div>' +
                            '<span class="context-label">' + esc(I18N.indexed) + '</span>' +
                        '</div>' +
                        '<span class="chunk-expand-toggle" data-chunk="' + i + '">&#9654; ' + esc(I18N.showText) + '</span>' +
                        '<div class="chunk-details" data-chunk="' + i + '">' +
                            '<pre class="mxch-kb-inspect-chunk-text">' + esc(chunk.text) + '</pre>' +
                            metaRows +
                        '</div>' +
                    '</div>';
                });

                resultsEl.innerHTML = html;

                resultsEl.querySelectorAll('.chunk-expand-toggle').forEach(function(toggle) {
                    toggle.addEventListener('click', function() {
                        var id = this.getAttribute('data-chunk');
                        var details = resultsEl.querySelector('.chunk-details[data-chunk="' + id + '"]');
                        if (!details) return;
                        var expanded = details.classList.toggle('expanded');
                        this.innerHTML = (expanded ? '&#9660; ' + esc(I18N.hideText) : '&#9654; ' + esc(I18N.showText));
                    });
                });
            }

            function showNotice(msg, type) {
                noticeEl.textContent = msg;
                noticeEl.className = 'knittnet-kb-edit-notice' + (type ? ' ' + type : '');
            }
        });
    })();
    </script>
    <?php
}

/**
 * Render Knowledge Page Navigation Scripts
 */
function knittnet_render_knowledge_page_scripts() {
    ?>
    <script>
    (function() {
        'use strict';

        // Wait for DOM
        document.addEventListener('DOMContentLoaded', function() {
            initKnowledgeNavigation();
            initMobileMenu();
            initImportOptions();
            initCustomPostTypesToggle();
            // Sitemap detection is now initialized when user clicks the Sitemap Import option
        });

        function initKnowledgeNavigation() {
            const navLinks = document.querySelectorAll('.mxch-nav-link[data-target], .mxch-mobile-nav-link[data-target]');
            const sections = document.querySelectorAll('.mxch-section');

            function showSection(targetId) {
                // Hide all sections
                sections.forEach(section => section.classList.remove('active'));
                // Show target section
                const target = document.getElementById(targetId);
                if (target) {
                    target.classList.add('active');
                }
                // Update nav links
                navLinks.forEach(link => {
                    link.classList.toggle('active', link.dataset.target === targetId);
                });
                // Update URL hash
                history.replaceState(null, null, '#' + targetId);
            }

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.dataset.target;
                    showSection(targetId);
                    // Close mobile menu if open
                    const mobileMenu = document.querySelector('.mxch-mobile-menu');
                    const overlay = document.querySelector('.mxch-mobile-overlay');
                    if (mobileMenu) mobileMenu.classList.remove('open');
                    if (overlay) overlay.classList.remove('open');
                });
            });

            // Handle initial hash
            const hash = window.location.hash.substring(1);
            if (hash && document.getElementById(hash)) {
                showSection(hash);
            }
        }

        function initMobileMenu() {
            const menuBtn = document.querySelector('.mxch-mobile-menu-btn');
            const closeBtn = document.querySelector('.mxch-mobile-menu-close');
            const overlay = document.querySelector('.mxch-mobile-overlay');
            const menu = document.querySelector('.mxch-mobile-menu');

            if (menuBtn && menu) {
                menuBtn.addEventListener('click', function() {
                    menu.classList.add('open');
                    if (overlay) overlay.classList.add('open');
                });
            }

            if (closeBtn && menu) {
                closeBtn.addEventListener('click', function() {
                    menu.classList.remove('open');
                    if (overlay) overlay.classList.remove('open');
                });
            }

            if (overlay && menu) {
                overlay.addEventListener('click', function() {
                    menu.classList.remove('open');
                    overlay.classList.remove('open');
                });
            }
        }

        function initImportOptions() {
            const importBoxes = document.querySelectorAll('.knittnet-import-box');
            const urlInputArea = document.getElementById('knittnet-url-input-area');
            const contentInputArea = document.getElementById('knittnet-content-input-area');
            const urlInput = document.getElementById('sitemap_url');
            const importTypeField = document.getElementById('import_type');
            const descriptionText = document.getElementById('url-description-text');

            // Sitemap detection elements
            const sitemapsLoading = document.getElementById('knittnet-sitemaps-loading');
            const detectedSitemaps = document.getElementById('knittnet-detected-sitemaps');
            const noSitemaps = document.getElementById('knittnet-no-sitemaps');

            // Helper to hide all sitemap detection UI
            function hideSitemapDetection() {
                if (sitemapsLoading) sitemapsLoading.style.display = 'none';
                if (detectedSitemaps) detectedSitemaps.style.display = 'none';
                if (noSitemaps) noSitemaps.style.display = 'none';
            }

            importBoxes.forEach(box => {
                box.addEventListener('click', function() {
                    const option = this.dataset.option;
                    const type = this.dataset.type;

                    // Remove active from all boxes
                    importBoxes.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Handle different import types
                    if (option === 'wordpress') {
                        // WordPress content selector - handled by existing modal
                        hideSitemapDetection();
                        return;
                    }

                    if (option === 'content') {
                        if (urlInputArea) urlInputArea.style.display = 'none';
                        if (contentInputArea) contentInputArea.style.display = 'block';
                        hideSitemapDetection();
                    } else if (type === 'sitemap' || type === 'url' || type === 'pdf') {
                        if (contentInputArea) contentInputArea.style.display = 'none';
                        if (urlInputArea) urlInputArea.style.display = 'block';

                        if (urlInput) {
                            urlInput.placeholder = this.dataset.placeholder || 'Enter URL here';
                        }
                        if (importTypeField) {
                            importTypeField.value = type;
                        }
                        if (descriptionText) {
                            const descriptions = {
                                'sitemap': '<?php echo esc_js(__('Use a content-specific sub-sitemap (e.g., post-sitemap.xml), not the main sitemap index.', 'knittnet')); ?>',
                                'url': '<?php echo esc_js(__('Enter the URL of any webpage to import its content.', 'knittnet')); ?>',
                                'pdf': '<?php echo esc_js(__('Enter the URL of a publicly accessible PDF file.', 'knittnet')); ?>'
                            };
                            descriptionText.textContent = descriptions[type] || '';
                        }

                        // Only show sitemap detection when Sitemap Import is selected
                        if (type === 'sitemap') {
                            // Initialize sitemap detection (function is defined in knowledge-processing.js)
                            if (typeof window.knittnetInitSitemapDetection === 'function') {
                                window.knittnetInitSitemapDetection();
                            }
                        } else {
                            hideSitemapDetection();
                        }
                    }
                });
            });
        }

        function initCustomPostTypesToggle() {
            const toggleBtn = document.getElementById('knittnet-custom-post-types-toggle');
            const container = document.getElementById('knittnet-custom-post-types-container');

            if (toggleBtn && container) {
                toggleBtn.addEventListener('click', function() {
                    const isHidden = container.style.display === 'none';
                    container.style.display = isHidden ? 'block' : 'none';
                    const icon = this.querySelector('span:last-child');
                    if (icon) {
                        icon.textContent = isHidden ? '▲' : '▼';
                    }
                });
            }
        }

        // Sitemap detection is now handled by knowledge-processing.js
        // It will be initialized when user clicks "Sitemap Import" option

        // ─── Edit Entry Modal ────────────────────────────────────
        initEditModal();

        function initEditModal() {
            var modal     = document.getElementById('knittnet-kb-edit-modal');
            var textarea  = document.getElementById('knittnet-kb-edit-textarea');
            var saveBtn   = document.getElementById('knittnet-kb-edit-save');
            var cancelBtn = document.getElementById('knittnet-kb-edit-cancel');
            var closeBtn  = document.getElementById('knittnet-kb-edit-close');
            var notice    = document.getElementById('knittnet-kb-edit-notice');
            var charCount = document.getElementById('knittnet-kb-edit-charcount');
            var chunkInfo = document.getElementById('knittnet-kb-edit-chunkinfo');
            var sourceEl  = document.getElementById('knittnet-kb-edit-source');
            var titleEl   = document.getElementById('knittnet-kb-edit-title');
            var saveText  = saveBtn ? saveBtn.querySelector('.knittnet-kb-edit-save-text') : null;
            var saveSpin  = saveBtn ? saveBtn.querySelector('.knittnet-kb-edit-save-spinner') : null;

            if (!modal) return;

            var currentEntry = {};

            // Bind edit buttons (delegated)
            document.addEventListener('click', function(e) {
                var btn = e.target.closest('.knittnet-edit-entry-btn');
                if (btn) {
                    e.preventDefault();
                    openEditModal(btn);
                }
            });

            // Close handlers
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            // Character count
            if (textarea) {
                textarea.addEventListener('input', function() {
                    updateCharCount();
                });
            }

            // Save handler
            if (saveBtn) saveBtn.addEventListener('click', saveContent);

            function openEditModal(btn) {
                currentEntry = {
                    sourceUrl:  btn.getAttribute('data-source-url') || '',
                    entryId:    btn.getAttribute('data-entry-id') || '',
                    dataSource: btn.getAttribute('data-data-source') || 'wordpress',
                    botId:      btn.getAttribute('data-bot-id') || 'default',
                    nonce:      btn.getAttribute('data-nonce') || ''
                };

                // Reset state
                textarea.value = '';
                textarea.placeholder = 'Loading content...';
                textarea.disabled = true;
                saveBtn.disabled = true;
                notice.textContent = '';
                notice.className = 'knittnet-kb-edit-notice';
                charCount.textContent = '';
                chunkInfo.textContent = '';

                // Show source
                if (currentEntry.sourceUrl && currentEntry.sourceUrl.indexOf('knittnet://') !== 0) {
                    sourceEl.innerHTML = 'Source: <a href="' + escapeHtml(currentEntry.sourceUrl) + '" target="_blank">' + escapeHtml(truncate(currentEntry.sourceUrl, 60)) + '</a>';
                } else {
                    sourceEl.textContent = 'Manual Content';
                }

                // Show modal
                modal.classList.add('active');

                // Fetch content
                var formData = new FormData();
                formData.append('action', 'knittnet_get_entry_content');
                formData.append('nonce', currentEntry.nonce);
                formData.append('source_url', currentEntry.sourceUrl);
                formData.append('entry_id', currentEntry.entryId);
                formData.append('data_source', currentEntry.dataSource);
                formData.append('bot_id', currentEntry.botId);

                fetch(ajaxurl, { method: 'POST', body: formData })
                    .then(function(r) { return r.json(); })
                    .then(function(resp) {
                        if (resp.success) {
                            textarea.value = resp.data.content || '';
                            textarea.disabled = false;
                            textarea.placeholder = 'Edit your content here...';
                            saveBtn.disabled = false;
                            currentEntry.contentType = resp.data.content_type || 'content';

                            updateCharCount();

                            if (resp.data.is_chunked) {
                                chunkInfo.textContent = resp.data.chunk_count + ' chunks — will be re-chunked on save';
                                titleEl.textContent = 'Edit Knowledge Entry (' + resp.data.chunk_count + ' chunks)';
                            } else {
                                chunkInfo.textContent = '';
                                titleEl.textContent = 'Edit Knowledge Entry';
                            }

                            textarea.focus();
                        } else {
                            textarea.placeholder = '';
                            showNotice((resp.data && resp.data.message) || 'Failed to load content.', 'error');
                        }
                    })
                    .catch(function(err) {
                        textarea.placeholder = '';
                        showNotice('Network error: ' + err.message, 'error');
                    });
            }

            function saveContent() {
                if (!textarea.value.trim()) {
                    showNotice('Content cannot be empty.', 'error');
                    return;
                }

                saveBtn.disabled = true;
                if (saveText) saveText.style.display = 'none';
                if (saveSpin) saveSpin.style.display = '';
                showNotice('Saving and re-embedding...', '');

                var formData = new FormData();
                formData.append('action', 'knittnet_save_entry_content');
                formData.append('nonce', currentEntry.nonce);
                formData.append('source_url', currentEntry.sourceUrl);
                formData.append('entry_id', currentEntry.entryId);
                formData.append('content', textarea.value);
                formData.append('data_source', currentEntry.dataSource);
                formData.append('bot_id', currentEntry.botId);
                formData.append('content_type', currentEntry.contentType || 'content');

                fetch(ajaxurl, { method: 'POST', body: formData })
                    .then(function(r) { return r.json(); })
                    .then(function(resp) {
                        if (saveText) saveText.style.display = '';
                        if (saveSpin) saveSpin.style.display = 'none';
                        saveBtn.disabled = false;

                        if (resp.success) {
                            showNotice('Saved successfully!', 'success');
                            setTimeout(function() {
                                closeModal();
                                window.location.reload();
                            }, 1000);
                        } else {
                            showNotice((resp.data && resp.data.message) || 'Save failed.', 'error');
                        }
                    })
                    .catch(function(err) {
                        if (saveText) saveText.style.display = '';
                        if (saveSpin) saveSpin.style.display = 'none';
                        saveBtn.disabled = false;
                        showNotice('Network error: ' + err.message, 'error');
                    });
            }

            function closeModal() {
                modal.classList.remove('active');
            }

            function updateCharCount() {
                var len = textarea.value.length;
                charCount.textContent = len.toLocaleString() + ' characters';
            }

            function showNotice(msg, type) {
                notice.textContent = msg;
                notice.className = 'knittnet-kb-edit-notice' + (type ? ' ' + type : '');
            }

            function escapeHtml(str) {
                var div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            }

            function truncate(str, max) {
                return str.length > max ? str.substring(0, max) + '...' : str;
            }
        }

    })();
    </script>
    <?php
}
