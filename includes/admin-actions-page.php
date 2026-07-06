<?php
/**
 * KnittNet Actions Page - Redesigned with Sidebar Navigation
 *
 * @package KnittNet
 * @since 2.7.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the new sidebar-based Actions page
 */
function knittnet_render_actions_page($admin_instance, $page_data) {
    $is_activated = $admin_instance->is_activated();
    $plugin_url = plugin_dir_url(dirname(__FILE__));

    // Extract page data
    extract($page_data);
    ?>
    <div class="knet-admin-wrapper knet-actions-wrapper">
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
                <span class="knet-mobile-menu-title"><?php esc_html_e('Actions', 'knittnet'); ?></span>
                <button type="button" class="knet-mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'knittnet'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <nav class="knet-mobile-menu-nav">
                <!-- Actions umbrella: Dashboard, then AI Tools (recommended), then Trigger Phrases -->
                <div class="knet-mobile-nav-section">
                    <div class="knet-mobile-nav-section-title"><?php esc_html_e('Actions', 'knittnet'); ?></div>
                    <button class="knet-mobile-nav-link <?php echo (isset($active_tab) && $active_tab === 'dashboard') ? 'active' : ''; ?>" data-target="dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                        <span><?php esc_html_e('Dashboard', 'knittnet'); ?></span>
                    </button>
                    <button class="knet-mobile-nav-link <?php echo (isset($active_tab) && $active_tab === 'ai-tools') ? 'active' : ''; ?>" data-target="ai-tools">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                        <span><?php esc_html_e('AI Tools', 'knittnet'); ?></span>
                    </button>
                    <button class="knet-mobile-nav-link" data-target="all-actions">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        <span><?php esc_html_e('Trigger Phrases', 'knittnet'); ?></span>
                    </button>
                </div>
                <?php if (!$is_activated): ?>
                <div class="knet-mobile-menu-footer">
                    <a href="https://knittnet.ai/" target="_blank" class="knet-mobile-upgrade-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        <?php esc_html_e('Upgrade to Pro', 'knittnet'); ?>
                    </a>
                </div>
                <?php endif; ?>
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
                <!-- Actions umbrella: Dashboard, then AI Tools (recommended), then Trigger Phrases -->
                <div class="knet-nav-section">
                    <div class="knet-nav-section-title"><?php esc_html_e('Actions', 'knittnet'); ?></div>

                    <div class="knet-nav-item" data-section="dashboard">
                        <button class="knet-nav-link <?php echo (isset($active_tab) && $active_tab === 'dashboard') ? 'active' : ''; ?>" data-target="dashboard">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Dashboard', 'knittnet'); ?></span>
                        </button>
                    </div>

                    <div class="knet-nav-item" data-section="ai-tools">
                        <button class="knet-nav-link <?php echo (isset($active_tab) && $active_tab === 'ai-tools') ? 'active' : ''; ?>" data-target="ai-tools">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('AI Tools', 'knittnet'); ?></span>
                            <span class="knet-nav-link-badge"><?php echo esc_html($total_tools); ?></span>
                        </button>
                    </div>

                    <div class="knet-nav-item" data-section="all-actions">
                        <button class="knet-nav-link" data-target="all-actions">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Trigger Phrases', 'knittnet'); ?></span>
                            <span class="knet-nav-link-badge"><?php echo esc_html($total_actions); ?></span>
                        </button>
                    </div>
                </div>
            </nav>

            <?php if (!$is_activated): ?>
            <div class="knet-sidebar-footer">
                <a href="https://knittnet.ai/" target="_blank" class="knet-sidebar-upgrade-v2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <?php esc_html_e('Pro Upgrade', 'knittnet'); ?>
                </a>
            </div>
            <?php endif; ?>
        </aside>

        <!-- Main Content Area -->
        <main class="knet-content">
            <!-- Dashboard Section -->
            <div id="dashboard" class="knet-section <?php echo (isset($active_tab) && $active_tab === 'dashboard') ? 'active' : ''; ?>">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Actions', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Give your chatbot the ability to do things — generate images, search the web, hand off to a human, capture an email, and more. There are two ways to set this up. The cards below explain when to use each.', 'knittnet'); ?></p>
                </div>

                <!-- Two ways to set up actions — landing explainer -->
                <div class="knet-info-section">
                    <div class="knet-info-section-header">
                        <div class="knet-info-section-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        </div>
                        <h3 class="knet-info-section-title"><?php esc_html_e('Two ways to set up actions', 'knittnet'); ?></h3>
                    </div>
                    <p class="knet-info-section-desc">
                        <?php esc_html_e('Both let your chatbot take action during a conversation. Pick whichever fits how you want it to behave — or use both together.', 'knittnet'); ?>
                    </p>

                    <div class="knet-approach-grid">
                        <!-- AI Tools (recommended) -->
                        <div class="knet-approach-card knet-approach-card-primary">
                            <div class="knet-approach-card-head">
                                <div class="knet-approach-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                </div>
                                <div class="knet-approach-titles">
                                    <h4 class="knet-approach-title"><?php esc_html_e('AI Tools', 'knittnet'); ?></h4>
                                    <span class="knet-approach-pill"><?php esc_html_e('Recommended', 'knittnet'); ?></span>
                                </div>
                            </div>
                            <p class="knet-approach-desc"><?php esc_html_e('Add the tools your chatbot is allowed to use, and the AI decides when to use each one from the conversation. Easiest to set up — there are no phrases to write. Best for most sites.', 'knittnet'); ?></p>
                            <button type="button" class="knet-btn knet-btn-primary knet-approach-btn" data-approach-target="ai-tools">
                                <?php esc_html_e('Set up AI Tools', 'knittnet'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            </button>
                        </div>
                        <!-- Trigger Phrases -->
                        <div class="knet-approach-card">
                            <div class="knet-approach-card-head">
                                <div class="knet-approach-icon knet-approach-icon-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                                </div>
                                <div class="knet-approach-titles">
                                    <h4 class="knet-approach-title"><?php esc_html_e('Trigger Phrases', 'knittnet'); ?></h4>
                                </div>
                            </div>
                            <p class="knet-approach-desc"><?php esc_html_e('You write the exact phrases that should fire a capability, with a match-strength slider. Precise and predictable — best when you want tight control over exactly when something happens.', 'knittnet'); ?></p>
                            <button type="button" class="knet-btn knet-btn-secondary knet-approach-btn" id="knet-add-action-dashboard-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                <?php esc_html_e('Add Trigger Phrase', 'knittnet'); ?>
                            </button>
                        </div>
                    </div>

                    <div class="knet-approach-note">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <span><strong><?php esc_html_e('Can I use both?', 'knittnet'); ?></strong> <?php esc_html_e('Yes. When a visitor\'s message matches one of your trigger phrases, that fires first; AI Tools cover everything else. Many sites run both together.', 'knittnet'); ?></span>
                    </div>
                </div>

            </div>

            <!-- All Actions Section - Split Panel Layout -->
            <div id="all-actions" class="knet-section">
                <div class="knet-split-panel">
                    <!-- Left Panel - Action List -->
                    <div class="knet-action-list-panel">
                        <!-- Bulk Actions Toolbar -->
                        <div class="knet-bulk-toolbar">
                            <label class="knet-bulk-select-all">
                                <input type="checkbox" id="knet-select-all-actions">
                                <span><?php esc_html_e('All', 'knittnet'); ?></span>
                            </label>
                            <span class="knet-selected-count" id="knet-selected-action-count">0</span>
                            <div class="knet-bulk-actions">
                                <button type="button" class="knet-bulk-btn knet-bulk-delete" id="knet-delete-selected-actions" disabled title="<?php esc_attr_e('Delete Selected', 'knittnet'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="knet-panel-header">
                            <div class="knet-search-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" id="knet-search-actions" class="knet-search-input" placeholder="<?php esc_attr_e('Search trigger phrases...', 'knittnet'); ?>">
                            </div>
                            <div class="knet-panel-title-row">
                                <span class="knet-panel-count" id="knet-action-count">0 / 0 trigger phrases</span>
                                <div class="knet-panel-actions">
                                    <button type="button" id="knet-add-action-btn" class="knet-btn knet-btn-primary knet-btn-sm" title="<?php esc_attr_e('Add Trigger Phrase', 'knittnet'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        <?php esc_html_e('Add', 'knittnet'); ?>
                                    </button>
                                    <button type="button" id="knet-refresh-actions" class="knet-icon-btn" title="<?php esc_attr_e('Refresh', 'knittnet'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="knet-action-list" id="knet-action-list">
                            <!-- Action list items loaded via AJAX -->
                        </div>
                        <div class="knet-panel-footer">
                            <div class="knet-pagination-simple" id="knet-actions-pagination">
                                <!-- Pagination controls -->
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel - Action Details/Editor -->
                    <div class="knet-action-detail-panel" id="knet-action-detail-panel">
                        <!-- Empty State -->
                        <div class="knet-action-empty" id="knet-action-empty">
                            <div class="knet-empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                            </div>
                            <h3><?php esc_html_e('Select a trigger phrase', 'knittnet'); ?></h3>
                            <p><?php esc_html_e('Choose a trigger phrase from the list to view details and edit, or create a new one.', 'knittnet'); ?></p>
                            <button type="button" id="knet-create-first-action-btn" class="knet-btn knet-btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                <?php esc_html_e('Create Trigger Phrase', 'knittnet'); ?>
                            </button>
                        </div>

                        <!-- Action Editor (hidden initially) -->
                        <div class="knet-action-editor" id="knet-action-editor" style="display: none;">
                            <!-- Step 1: Action Type Selection -->
                            <div id="knet-editor-step-1" class="knet-editor-step active">
                                <div class="knet-editor-header">
                                    <button type="button" class="knet-mobile-back-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                                        <?php esc_html_e('Back', 'knittnet'); ?>
                                    </button>
                                    <h3 class="knet-editor-title"><?php esc_html_e('Choose what it does', 'knittnet'); ?></h3>
                                    <button type="button" class="knet-editor-close" id="knet-close-editor">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </div>

                                <div class="knet-type-search">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                    <input type="text" id="knet-type-search-input" placeholder="<?php esc_attr_e('Search types...', 'knittnet'); ?>">
                                </div>

                                <div class="knet-type-categories">
                                    <button type="button" class="knet-category-btn active" data-category="all"><?php esc_html_e('All', 'knittnet'); ?></button>
                                    <?php foreach ($callback_groups as $group_label => $group_callbacks): ?>
                                        <button type="button" class="knet-category-btn" data-category="<?php echo esc_attr(sanitize_title($group_label)); ?>"><?php echo esc_html($group_label); ?></button>
                                    <?php endforeach; ?>
                                </div>

                                <div class="knet-types-grid" id="knet-types-grid">
                                    <?php foreach ($callback_groups as $group_label => $group_callbacks):
                                        $category_slug = sanitize_title($group_label);
                                        foreach ($group_callbacks as $function => $data):
                                            $label = $data['label'];
                                            $pro_only = $data['pro_only'];
                                            $icon = isset($data['icon']) ? $data['icon'] : 'admin-generic';
                                            $description = isset($data['description']) ? $data['description'] : '';
                                            $is_addon = isset($data['addon']) && $data['addon'] !== false;
                                            $addon_name = isset($data['addon_name']) ? $data['addon_name'] : '';
                                            $is_installed = isset($data['installed']) ? $data['installed'] : true;
                                    ?>
                                        <div class="knet-type-card <?php echo (!$is_activated && $pro_only) ? 'knet-type-pro' : ''; ?> <?php echo ($is_addon && !$is_installed) ? 'knet-type-addon' : ''; ?>"
                                             data-category="<?php echo esc_attr($category_slug); ?>"
                                             data-value="<?php echo esc_attr($function); ?>"
                                             data-label="<?php echo esc_attr($label); ?>"
                                             data-pro="<?php echo $pro_only ? 'true' : 'false'; ?>"
                                             data-addon="<?php echo esc_attr($is_addon ? $data['addon'] : ''); ?>"
                                             data-installed="<?php echo $is_installed ? 'true' : 'false'; ?>">
                                            <div class="knet-type-icon">
                                                <span class="dashicons dashicons-<?php echo esc_attr($icon); ?>"></span>
                                            </div>
                                            <div class="knet-type-info">
                                                <h4><?php echo esc_html($label); ?></h4>
                                                <p><?php echo esc_html($description ?: sprintf(__('Use the %s action', 'knittnet'), $label)); ?></p>
                                                <?php if ($pro_only && !$is_activated): ?>
                                                    <span class="knet-badge knet-badge-pro"><?php esc_html_e('Pro', 'knittnet'); ?></span>
                                                <?php endif; ?>
                                                <?php if ($is_addon && !$is_installed): ?>
                                                    <span class="knet-badge knet-badge-addon"><?php echo esc_html(sprintf(__('Requires %s', 'knittnet'), $addon_name)); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; endforeach; ?>
                                </div>
                            </div>

                            <!-- Step 2: Action Configuration -->
                            <div id="knet-editor-step-2" class="knet-editor-step">
                                <div class="knet-editor-header">
                                    <button type="button" class="knet-back-btn" id="knet-back-to-step-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                                        <?php esc_html_e('Back', 'knittnet'); ?>
                                    </button>
                                    <h3 class="knet-editor-title" id="knet-config-title"><?php esc_html_e('Configure Trigger Phrase', 'knittnet'); ?></h3>
                                    <button type="button" class="knet-editor-close" id="knet-close-editor-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </div>

                                <div class="knet-selected-type">
                                    <div class="knet-selected-type-icon" id="knet-selected-type-icon">
                                        <span class="dashicons dashicons-admin-generic"></span>
                                    </div>
                                    <div class="knet-selected-type-info">
                                        <h4 id="knet-selected-type-label"><?php esc_html_e('Selected Type', 'knittnet'); ?></h4>
                                        <p id="knet-selected-type-desc"><?php esc_html_e('Configure this trigger phrase for your chatbot', 'knittnet'); ?></p>
                                    </div>
                                </div>

                                <form id="knet-action-form" method="post">
                                    <?php wp_nonce_field('knittnet_add_intent_nonce', 'knet_action_nonce'); ?>
                                    <input type="hidden" name="action_id" id="knet-action-id" value="">
                                    <input type="hidden" name="callback_function" id="knet-callback-function" value="">
                                    <input type="hidden" name="form_mode" id="knet-form-mode" value="add">

                                    <div class="knet-form-group">
                                        <label for="knet-action-label"><?php esc_html_e('Label', 'knittnet'); ?></label>
                                        <input type="text" id="knet-action-label" name="intent_label" required placeholder="<?php esc_attr_e('e.g., Newsletter Signup', 'knittnet'); ?>">
                                        <p class="knet-form-hint"><?php esc_html_e('A descriptive name for this trigger phrase (for your reference only).', 'knittnet'); ?></p>
                                    </div>

                                    <div class="knet-form-group">
                                        <label><?php esc_html_e('Trigger Phrases', 'knittnet'); ?></label>
                                        <div class="knet-phrase-input-wrapper">
                                            <div class="knet-phrase-tags" id="knet-phrase-tags">
                                                <!-- Phrase pills rendered dynamically -->
                                            </div>
                                            <div class="knet-phrase-add-row">
                                                <input type="text" id="knet-phrase-input" placeholder="<?php esc_attr_e('Type a trigger phrase and press Enter', 'knittnet'); ?>">
                                                <button type="button" class="knet-btn knet-btn-sm" id="knet-add-phrase-btn"><?php esc_html_e('Add', 'knittnet'); ?></button>
                                            </div>
                                        </div>
                                        <p class="knet-form-hint"><?php esc_html_e('Each phrase gets its own embedding vector for more accurate matching.', 'knittnet'); ?></p>
                                    </div>

                                    <div class="knet-form-group">
                                        <label for="knet-action-threshold">
                                            <?php esc_html_e('Similarity Threshold', 'knittnet'); ?>
                                            <span class="knet-threshold-value" id="knet-threshold-value">85%</span>
                                        </label>
                                        <input type="range" id="knet-action-threshold" name="similarity_threshold" min="10" max="95" value="85">
                                        <p class="knet-form-hint"><?php esc_html_e('Lower values (10-30) trigger more easily. Higher values (70-95) require more exact matches.', 'knittnet'); ?></p>
                                    </div>

                                    <div class="knet-form-group">
                                        <label><?php esc_html_e('Enabled Bots', 'knittnet'); ?></label>
                                        <div class="knet-bot-selector" id="knet-bot-selector">
                                            <label class="knet-checkbox-label">
                                                <input type="checkbox" name="enabled_bots[]" value="default" checked>
                                                <span class="knet-checkmark"></span>
                                                <?php esc_html_e('Default Bot', 'knittnet'); ?>
                                            </label>
                                            <?php if (class_exists('KnittNet_Multi_Bot_Core_Manager')):
                                                $multi_bot_manager = KnittNet_Multi_Bot_Core_Manager::get_instance();
                                                $available_bots_list = $multi_bot_manager->get_available_bots();
                                                foreach ($available_bots_list as $bot_id => $bot_name):
                                                    if ($bot_id === 'default') continue;
                                            ?>
                                                <label class="knet-checkbox-label">
                                                    <input type="checkbox" name="enabled_bots[]" value="<?php echo esc_attr($bot_id); ?>">
                                                    <span class="knet-checkmark"></span>
                                                    <?php echo esc_html($bot_name); ?>
                                                </label>
                                            <?php endforeach; endif; ?>
                                        </div>
                                    </div>

                                    <div class="knet-form-actions">
                                        <button type="button" class="knet-btn knet-btn-secondary" id="knet-cancel-action">
                                            <?php esc_html_e('Cancel', 'knittnet'); ?>
                                        </button>
                                        <button type="submit" class="knet-btn knet-btn-primary" id="knet-save-action">
                                            <?php esc_html_e('Save Trigger Phrase', 'knittnet'); ?>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Action View (for viewing existing action details) -->
                            <div id="knet-action-view" class="knet-editor-step">
                                <div class="knet-editor-header">
                                    <button type="button" class="knet-mobile-back-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                                        <?php esc_html_e('Back', 'knittnet'); ?>
                                    </button>
                                    <h3 class="knet-editor-title" id="knet-view-title"><?php esc_html_e('Trigger Phrase Details', 'knittnet'); ?></h3>
                                    <div class="knet-header-actions">
                                        <label class="knet-toggle-switch" title="<?php esc_attr_e('Toggle Enabled', 'knittnet'); ?>">
                                            <input type="checkbox" id="knet-action-enabled-toggle" checked>
                                            <span class="knet-toggle-slider"></span>
                                        </label>
                                        <button type="button" class="knet-icon-btn" id="knet-edit-action-btn" title="<?php esc_attr_e('Edit', 'knittnet'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </button>
                                        <button type="button" class="knet-icon-btn knet-btn-danger-icon" id="knet-delete-action-btn" title="<?php esc_attr_e('Delete', 'knittnet'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="knet-action-details">
                                    <div class="knet-detail-card">
                                        <div class="knet-detail-icon" id="knet-view-icon">
                                            <span class="dashicons dashicons-admin-generic"></span>
                                        </div>
                                        <div class="knet-detail-content">
                                            <h4 id="knet-view-label"><?php esc_html_e('Label', 'knittnet'); ?></h4>
                                            <p class="knet-detail-type" id="knet-view-type"><?php esc_html_e('Type', 'knittnet'); ?></p>
                                        </div>
                                    </div>

                                    <div class="knet-detail-section">
                                        <h5><?php esc_html_e('Trigger Phrases', 'knittnet'); ?></h5>
                                        <div class="knet-phrases-list" id="knet-view-phrases">
                                            <!-- Phrases loaded dynamically -->
                                        </div>
                                    </div>

                                    <div class="knet-detail-section">
                                        <h5><?php esc_html_e('Settings', 'knittnet'); ?></h5>
                                        <div class="knet-settings-grid">
                                            <div class="knet-setting-item">
                                                <span class="knet-setting-label"><?php esc_html_e('Similarity Threshold', 'knittnet'); ?></span>
                                                <span class="knet-setting-value" id="knet-view-threshold">85%</span>
                                            </div>
                                            <div class="knet-setting-item">
                                                <span class="knet-setting-label"><?php esc_html_e('Status', 'knittnet'); ?></span>
                                                <span class="knet-setting-value knet-status-badge" id="knet-view-status"><?php esc_html_e('Enabled', 'knittnet'); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="knet-detail-section">
                                        <h5><?php esc_html_e('Assigned Bots', 'knittnet'); ?></h5>
                                        <div class="knet-bots-list" id="knet-view-bots">
                                            <!-- Bots loaded dynamically -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Tools Section (native function calling) -->
            <!-- No content-header band here — the section starts directly at the
                 list+detail split panel, exactly like the Trigger Phrases section
                 (#all-actions) above, for pixel parity (plan 5f7409). The per-
                 approach explainer already lives on the Dashboard landing. -->
            <div id="ai-tools" class="knet-section <?php echo (isset($active_tab) && $active_tab === 'ai-tools') ? 'active' : ''; ?>">
                <div id="knet-fc-settings" data-fc-nonce="<?php echo esc_attr(wp_create_nonce('knittnet_fc_autosave')); ?>">

                    <!-- Model-not-capable notice (relocated from the removed master toggle).
                         Tools can still be added; they just won't fire until a tool-capable
                         model is selected — so a user on a non-tool model isn't met with silence. -->
                    <?php if (empty($fc_model_capable)): ?>
                    <div class="knet-notice knet-notice-warning" style="margin-bottom: 20px;">
                        <svg class="knet-notice-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        <div><?php printf(esc_html__('Your current chat model (%s) does not support tools. You can still add tools here, but they will not fire until you choose a tool-capable model in Settings.', 'knittnet'), '<strong>' . esc_html($fc_current_model) . '</strong>'); ?></div>
                    </div>
                    <?php endif; ?>

                    <!-- Brave-key-not-set notice (plan 183856). Web Search + Image Search
                         run on the Brave Search API; if either is enabled as a tool with no
                         brave_api_key configured, they silently won't fire — so the admin
                         gets a clear setup cue instead. Mirrors the model-not-capable notice. -->
                    <?php if (!empty($fc_brave_missing)): ?>
                    <div class="knet-notice knet-notice-warning" style="margin-bottom: 20px;">
                        <svg class="knet-notice-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        <div><?php printf(esc_html__('Web Search and Image Search are powered by Brave Search and need a Brave Search API key to work. Add your key under %s to enable them.', 'knittnet'), '<strong>' . esc_html__('Settings → Brave Search', 'knittnet') . '</strong>'); ?></div>
                    </div>
                    <?php endif; ?>

                    <?php if (empty($fc_tools)): ?>
                        <div class="knet-card">
                            <div class="knet-card-body">
                                <p class="knet-fc-intro"><?php esc_html_e('No tools available yet. Core search and image tools appear here, and activating add-ons like WooCommerce adds more.', 'knittnet'); ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php
                        // Hidden store — the SINGLE source of truth for autosave. Each
                        // .knet-fc-tool keeps its hidden marker + checkbox + usage-hint
                        // textarea exactly as the per-tool checklist did, so fcCollectAndSave()
                        // and the knittnet_fc_autosave endpoint are UNCHANGED (HARD: no data-model
                        // change). The master-detail list + detail panel below only toggle these
                        // inputs. A tool whose checkbox is checked = active (plan d450a7) — there
                        // is no global toggle and no per-tool enable checkbox in the UI anymore.
                        $fc_hint_max = (int) (class_exists('KnittNet_Tool_Registry') ? KnittNet_Tool_Registry::HINT_MAX : 500);
                        ?>
                        <div id="knet-fc-tool-store" class="knet-fc-tool-store" hidden aria-hidden="true">
                            <?php foreach ($fc_tools as $fc_tool):
                                $fc_cb   = $fc_tool['callback'];
                                $fc_on   = !empty($fc_tool['enabled']);
                                $fc_icon = isset($fc_tool['icon']) ? $fc_tool['icon'] : 'admin-generic';
                            ?>
                                <div class="knet-fc-tool"
                                     data-fc-callback="<?php echo esc_attr($fc_cb); ?>"
                                     data-fc-label="<?php echo esc_attr($fc_tool['label']); ?>"
                                     data-fc-desc="<?php echo esc_attr($fc_tool['description']); ?>"
                                     data-fc-setup="<?php echo esc_attr(isset($fc_tool['setup_note']) ? $fc_tool['setup_note'] : ''); ?>"
                                     data-fc-icon="<?php echo esc_attr($fc_icon); ?>"
                                     data-fc-addon="<?php echo !empty($fc_tool['is_addon']) ? '1' : '0'; ?>"
                                     data-fc-cautious="<?php echo !empty($fc_tool['cautious']) ? '1' : '0'; ?>">
                                    <input type="hidden" name="knittnet_fc_all_tools[]" value="<?php echo esc_attr($fc_cb); ?>">
                                    <input type="checkbox" name="knittnet_fc_tools[]" value="<?php echo esc_attr($fc_cb); ?>" <?php checked($fc_on); ?>>
                                    <textarea class="knet-fc-hint-input" data-fc-callback="<?php echo esc_attr($fc_cb); ?>" maxlength="<?php echo $fc_hint_max; ?>"><?php echo esc_textarea(isset($fc_tool['usage_hint']) ? $fc_tool['usage_hint'] : ''); ?></textarea>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Master-detail split panel — clones the Trigger Phrases list+detail
                             component (same knet-* classes/CSS) so AI Tools reads as the same surface.
                             FC-specific IDs keep the Trigger Phrases JS off these elements. -->
                        <div class="knet-split-panel"
                             id="knet-fc-panel"
                             data-i18n-tools="<?php esc_attr_e('tools', 'knittnet'); ?>"
                             data-i18n-tool="<?php esc_attr_e('tool', 'knittnet'); ?>"
                             data-i18n-active="<?php esc_attr_e('Active', 'knittnet'); ?>"
                             data-i18n-addon="<?php esc_attr_e('Add-on', 'knittnet'); ?>"
                             data-i18n-sensitive="<?php esc_attr_e('Sensitive', 'knittnet'); ?>"
                             data-i18n-nohint="<?php esc_attr_e('No usage note — the AI decides on its own when to use this.', 'knittnet'); ?>">

                            <!-- Left: tool list -->
                            <div class="knet-action-list-panel">
                                <!-- Bulk Actions Toolbar — exact clone of the Trigger Phrases
                                     #all-actions toolbar (All select-all + bulk-remove trash),
                                     FC-specific IDs so the Trigger Phrases JS stays off it (plan 5f7409). -->
                                <div class="knet-bulk-toolbar">
                                    <label class="knet-bulk-select-all">
                                        <input type="checkbox" id="knet-fc-select-all">
                                        <span><?php esc_html_e('All', 'knittnet'); ?></span>
                                    </label>
                                    <span class="knet-selected-count" id="knet-fc-selected-count">0</span>
                                    <div class="knet-bulk-actions">
                                        <button type="button" class="knet-bulk-btn knet-bulk-delete" id="knet-fc-delete-selected" disabled title="<?php esc_attr_e('Remove Selected', 'knittnet'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="knet-panel-header">
                                    <div class="knet-search-wrapper">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                        <input type="text" id="knet-fc-search" class="knet-search-input" placeholder="<?php esc_attr_e('Search tools...', 'knittnet'); ?>">
                                    </div>
                                    <div class="knet-panel-title-row">
                                        <span class="knet-panel-count" id="knet-fc-count">0 <?php esc_html_e('tools', 'knittnet'); ?></span>
                                        <div class="knet-panel-actions">
                                            <button type="button" id="knet-fc-add-btn" class="knet-btn knet-btn-primary knet-btn-sm js-knet-fc-add" title="<?php esc_attr_e('Add Tool', 'knittnet'); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                                <?php esc_html_e('Add', 'knittnet'); ?>
                                            </button>
                                            <button type="button" id="knet-fc-refresh" class="knet-icon-btn" title="<?php esc_attr_e('Refresh', 'knittnet'); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="knet-action-list" id="knet-fc-list">
                                    <!-- Tool rows built by JS from the hidden store (enabled tools only) -->
                                </div>
                                <div class="knet-panel-footer">
                                    <span id="knet-fc-save-status" class="knet-fc-save-status" role="status" aria-live="polite"></span>
                                </div>
                            </div>

                            <!-- Right: detail / view panel -->
                            <div class="knet-action-detail-panel" id="knet-fc-detail-panel">
                                <!-- Empty state -->
                                <div class="knet-action-empty" id="knet-fc-empty">
                                    <div class="knet-empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                    </div>
                                    <h3><?php esc_html_e('Select a tool', 'knittnet'); ?></h3>
                                    <p><?php esc_html_e('Choose a tool from the list to view details and edit when the AI should use it, or add a new one.', 'knittnet'); ?></p>
                                    <button type="button" class="knet-btn knet-btn-primary js-knet-fc-add">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        <?php esc_html_e('Add Tool', 'knittnet'); ?>
                                    </button>
                                </div>

                                <!-- Tool view (shown when a tool is selected) -->
                                <div class="knet-action-editor" id="knet-fc-view" style="display: none;">
                                    <div class="knet-editor-header">
                                        <button type="button" class="knet-mobile-back-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                                            <?php esc_html_e('Back', 'knittnet'); ?>
                                        </button>
                                        <h3 class="knet-editor-title"><?php esc_html_e('Tool Details', 'knittnet'); ?></h3>
                                        <div class="knet-header-actions">
                                            <button type="button" class="knet-icon-btn" id="knet-fc-edit-btn" title="<?php esc_attr_e('Edit usage note', 'knittnet'); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </button>
                                            <button type="button" class="knet-icon-btn knet-btn-danger-icon" id="knet-fc-remove-btn" title="<?php esc_attr_e('Remove tool', 'knittnet'); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="knet-action-details">
                                        <div class="knet-detail-card">
                                            <div class="knet-detail-icon" id="knet-fc-view-icon">
                                                <span class="dashicons dashicons-admin-generic"></span>
                                            </div>
                                            <div class="knet-detail-content">
                                                <h4 id="knet-fc-view-label"><?php esc_html_e('Tool', 'knittnet'); ?></h4>
                                                <p class="knet-detail-type" id="knet-fc-view-type"></p>
                                            </div>
                                        </div>

                                        <div class="knet-detail-section">
                                            <h5><?php esc_html_e('What it does', 'knittnet'); ?></h5>
                                            <p class="knet-fc-view-desc" id="knet-fc-view-desc"></p>
                                        </div>

                                        <div class="knet-detail-section">
                                            <h5><?php esc_html_e('When the assistant uses this', 'knittnet'); ?></h5>
                                            <p class="knet-fc-view-hint" id="knet-fc-view-hint"></p>
                                        </div>

                                        <!-- Setup requirement (plan 183856) — only shown for tools that
                                             declare a setup_note (e.g. Web/Image Search → Brave key). JS
                                             toggles this section based on the tool's data-fc-setup. -->
                                        <div class="knet-detail-section" id="knet-fc-view-setup-wrap" style="display:none;">
                                            <h5><?php esc_html_e('Setup', 'knittnet'); ?></h5>
                                            <p class="knet-fc-view-setup" id="knet-fc-view-setup"></p>
                                        </div>

                                        <div class="knet-detail-section">
                                            <h5><?php esc_html_e('Status', 'knittnet'); ?></h5>
                                            <div class="knet-settings-grid">
                                                <div class="knet-setting-item">
                                                    <span class="knet-setting-label"><?php esc_html_e('State', 'knittnet'); ?></span>
                                                    <span class="knet-setting-value knet-status-badge"><?php esc_html_e('Active', 'knittnet'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Loading Overlay -->
    <div id="knet-action-loading" class="knet-loading-overlay" style="display: none;">
        <div class="knet-loading-spinner"></div>
        <div class="knet-loading-text"><?php esc_html_e('Saving, please wait...', 'knittnet'); ?></div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="knet-delete-modal" class="knet-modal-overlay" style="display: none;">
        <div class="knet-modal-content knet-modal-sm">
            <div class="knet-modal-header">
                <h2><?php esc_html_e('Delete Trigger Phrase', 'knittnet'); ?></h2>
                <button type="button" class="knet-modal-close">&times;</button>
            </div>
            <div class="knet-modal-body">
                <p><?php esc_html_e('Are you sure you want to delete this trigger phrase? This cannot be undone.', 'knittnet'); ?></p>
            </div>
            <div class="knet-modal-footer">
                <button type="button" class="knet-btn knet-btn-secondary" id="knet-cancel-delete"><?php esc_html_e('Cancel', 'knittnet'); ?></button>
                <button type="button" class="knet-btn knet-btn-danger" id="knet-confirm-delete"><?php esc_html_e('Delete', 'knittnet'); ?></button>
            </div>
        </div>
    </div>

    <!-- Add-Tool modal (AI Tools card flow, plan 8bbf98 part 4) -->
    <div id="knet-fc-tool-modal" class="knet-modal-overlay" style="display: none;">
        <div class="knet-modal-content knet-modal-lg">
            <!-- Step 1: pick a capability -->
            <div class="knet-fc-modal-step" data-step="1">
                <div class="knet-modal-header">
                    <h2><?php esc_html_e('Add a Tool', 'knittnet'); ?></h2>
                    <button type="button" class="knet-modal-close" data-fc-modal-close>&times;</button>
                </div>
                <div class="knet-modal-body">
                    <p class="knet-fc-intro"><?php esc_html_e('Pick a capability the AI can call from the conversation.', 'knittnet'); ?></p>
                    <div class="knet-types-grid knet-fc-modal-grid" id="knet-fc-modal-grid"></div>
                    <p class="knet-fc-modal-allset" id="knet-fc-modal-allset" style="display:none;"><?php esc_html_e('Every available tool is already added. Activate add-ons like WooCommerce to unlock more.', 'knittnet'); ?></p>
                </div>
            </div>
            <!-- Step 2: confirm + when-to-use -->
            <div class="knet-fc-modal-step" data-step="2" style="display:none;">
                <div class="knet-modal-header">
                    <button type="button" class="knet-back-btn" id="knet-fc-modal-back">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        <?php esc_html_e('Back', 'knittnet'); ?>
                    </button>
                    <h2 id="knet-fc-modal-step2-title" data-add-title="<?php esc_attr_e('Add Tool', 'knittnet'); ?>" data-edit-title="<?php esc_attr_e('Edit Tool', 'knittnet'); ?>"><?php esc_html_e('Add Tool', 'knittnet'); ?></h2>
                    <button type="button" class="knet-modal-close" data-fc-modal-close>&times;</button>
                </div>
                <div class="knet-modal-body">
                    <div class="knet-selected-type">
                        <div class="knet-selected-type-icon" id="knet-fc-modal-selected-icon">
                            <span class="dashicons dashicons-admin-generic"></span>
                        </div>
                        <div class="knet-selected-type-info">
                            <h4 id="knet-fc-modal-selected-label"><?php esc_html_e('Selected tool', 'knittnet'); ?></h4>
                            <p id="knet-fc-modal-selected-desc"></p>
                        </div>
                    </div>
                    <div class="knet-form-group">
                        <label for="knet-fc-modal-hint"><?php esc_html_e('When should the assistant use this?', 'knittnet'); ?></label>
                        <textarea id="knet-fc-modal-hint" class="knet-fc-modal-hint-input" rows="3" maxlength="<?php echo (int) (class_exists('KnittNet_Tool_Registry') ? KnittNet_Tool_Registry::HINT_MAX : 500); ?>" placeholder="<?php esc_attr_e('Optional — e.g. “Use only when the visitor asks about pricing or discounts.”', 'knittnet'); ?>"></textarea>
                        <p class="knet-form-hint"><?php esc_html_e('Leave blank to let the AI decide on its own. Your note is added to what the model knows about this tool.', 'knittnet'); ?></p>
                    </div>
                    <div class="knet-fc-modal-sensitive-note" id="knet-fc-modal-sensitive" style="display:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        <span><?php esc_html_e('Sensitive tool — it can spend money, expose customer data, or hand off to a human. Only add it if you want the AI to do that.', 'knittnet'); ?></span>
                    </div>
                </div>
                <div class="knet-modal-footer">
                    <button type="button" class="knet-btn knet-btn-secondary" data-fc-modal-close><?php esc_html_e('Cancel', 'knittnet'); ?></button>
                    <button type="button" class="knet-btn knet-btn-primary" id="knet-fc-modal-save" data-add-label="<?php esc_attr_e('Add Tool', 'knittnet'); ?>" data-save-label="<?php esc_attr_e('Save Changes', 'knittnet'); ?>"><?php esc_html_e('Add Tool', 'knittnet'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <?php
}
