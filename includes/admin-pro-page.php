<?php
/**
 * KnittNet Pro & Extensions Page - Consolidated Sidebar Navigation
 *
 * @package KnittNet
 * @since 2.7.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the consolidated Pro & Extensions page
 */
function knittnet_render_pro_page($admin_instance, $addons_config) {
    $plugin_url = plugin_dir_url(dirname(__FILE__));

    // Count addon statuses
    $active_count = 0;
    $installed_count = 0;
    foreach ($addons_config as $slug => $addon) {
        $status = knittnet_get_addon_status_info($addon['plugin_file'], $addon['config_page']);
        if ($status['status'] === 'active') {
            $active_count++;
            $installed_count++;
        } elseif ($status['status'] === 'inactive') {
            $installed_count++;
        }
    }
    ?>
    <div class="knet-admin-wrapper knet-pro-wrapper">
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
                <span class="knet-mobile-menu-title"><?php esc_html_e('Pro & Extensions', 'knittnet'); ?></span>
                <button type="button" class="knet-mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'knittnet'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <nav class="knet-mobile-menu-nav">
                <!-- Extensions Section (Mobile) -->
                <div class="knet-mobile-nav-section">
                    <div class="knet-mobile-nav-section-title"><?php esc_html_e('Extensions', 'knittnet'); ?></div>
                    <button class="knet-mobile-nav-link active" data-target="overview">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                        <span><?php esc_html_e('Overview', 'knittnet'); ?></span>
                    </button>
                    <?php foreach ($addons_config as $slug => $addon):
                        $status = knittnet_get_addon_status_info($addon['plugin_file'], $addon['config_page']);
                    ?>
                    <button class="knet-mobile-nav-link knet-addon-nav-link" data-target="addon-<?php echo esc_attr($slug); ?>">
                        <span class="knet-addon-status-dot <?php echo esc_attr($status['status']); ?>"></span>
                        <span><?php echo esc_html(!empty($addon['sidebar_title']) ? $addon['sidebar_title'] : str_replace('KnittNet ', '', $addon['title'])); ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>
            </nav>
        </div>

        <!-- Sidebar Navigation -->
        <aside class="knet-sidebar knet-pro-sidebar">
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
                <!-- Extensions Section -->
                <div class="knet-nav-section knet-extensions-section">
                    <div class="knet-nav-section-title">
                        <?php esc_html_e('Extensions', 'knittnet'); ?>
                        <span class="knet-nav-section-count"><?php echo esc_html(count($addons_config)); ?></span>
                    </div>

                    <div class="knet-nav-item" data-section="overview">
                        <button class="knet-nav-link active" data-target="overview">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Overview', 'knittnet'); ?></span>
                        </button>
                    </div>

                    <?php foreach ($addons_config as $slug => $addon):
                        $status = knittnet_get_addon_status_info($addon['plugin_file'], $addon['config_page']);
                    ?>
                    <div class="knet-nav-item knet-addon-item" data-section="addon-<?php echo esc_attr($slug); ?>">
                        <button class="knet-nav-link knet-addon-nav-link" data-target="addon-<?php echo esc_attr($slug); ?>">
                            <span class="knet-addon-status-dot <?php echo esc_attr($status['status']); ?>"></span>
                            <span class="knet-nav-link-text"><?php echo esc_html(!empty($addon['sidebar_title']) ? $addon['sidebar_title'] : str_replace('KnittNet ', '', $addon['title'])); ?></span>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </nav>

        </aside>

        <!-- Main Content Area -->
        <main class="knet-content knet-pro-content">
            <!-- Overview Section -->
            <div id="overview" class="knet-section active">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Extensions', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Every KnittNet AI Assistant feature is unlocked — no license key, no activation step. This page lists optional companion extensions you can install separately.', 'knittnet'); ?></p>
                </div>

                <!-- Extensions Overview Grid -->
                <div class="knet-content-header knet-mt-xl">
                    <h2 class="knet-content-title knet-h2"><?php esc_html_e('Available Extensions', 'knittnet'); ?></h2>
                    <p class="knet-content-subtitle"><?php esc_html_e('Click on any extension to learn more and get started.', 'knittnet'); ?></p>
                </div>

                <div class="knet-extensions-grid">
                    <?php foreach ($addons_config as $slug => $addon):
                        $status = knittnet_get_addon_status_info($addon['plugin_file'], $addon['config_page']);
                    ?>
                    <div class="knet-extension-card" data-addon="<?php echo esc_attr($slug); ?>" style="--card-accent: <?php echo esc_attr($addon['accent']); ?>">
                        <div class="knet-extension-card-header">
                            <h3 class="knet-extension-title"><?php echo esc_html($addon['title']); ?></h3>
                            <span class="knet-extension-status <?php echo esc_attr($status['status']); ?>">
                                <?php echo esc_html(ucfirst(str_replace('-', ' ', $status['status']))); ?>
                            </span>
                        </div>
                        <p class="knet-extension-desc"><?php echo esc_html($addon['description']); ?></p>
                        <div class="knet-extension-card-footer">
                            <?php if ($status['status'] === 'active'): ?>
                                <a href="<?php echo esc_url($status['action_url']); ?>" class="knet-btn knet-btn-primary knet-btn-sm">
                                    <?php esc_html_e('Configure', 'knittnet'); ?>
                                </a>
                            <?php elseif ($status['status'] === 'inactive'): ?>
                                <a href="<?php echo esc_url($status['action_url']); ?>" class="knet-btn knet-btn-secondary knet-btn-sm">
                                    <?php esc_html_e('Activate', 'knittnet'); ?>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo esc_url($addon['url']); ?>" target="_blank" class="knet-btn knet-btn-secondary knet-btn-sm">
                                    <?php esc_html_e('Get Extension', 'knittnet'); ?>
                                </a>
                            <?php endif; ?>
                            <button type="button" class="knet-btn knet-btn-ghost knet-btn-sm knet-view-addon-btn" data-target="addon-<?php echo esc_attr($slug); ?>">
                                <?php esc_html_e('Learn More', 'knittnet'); ?>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Individual Addon Detail Sections (Hero Style) -->
            <?php foreach ($addons_config as $slug => $addon):
                $status = knittnet_get_addon_status_info($addon['plugin_file'], $addon['config_page']);
                // Hero features are rendered via SVG showcase (no image files needed)
            ?>
            <div id="addon-<?php echo esc_attr($slug); ?>" class="knet-section knet-addon-detail">
                <!-- Back Link -->
                <div class="knet-addon-page-header">
                    <button type="button" class="knet-back-link" data-target="overview">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        <?php esc_html_e('Back to Extensions', 'knittnet'); ?>
                    </button>
                </div>

                <!-- Hero Section -->
                <div class="knet-addon-hero-section">
                    <!-- Left: Content -->
                    <div class="knet-addon-hero-content">
                        <h1 class="knet-addon-hero-title"><?php echo esc_html($addon['title']); ?></h1>
                        <p class="knet-addon-hero-desc"><?php echo esc_html($addon['description']); ?></p>

                        <!-- Feature highlights -->
                        <?php if (!empty($addon['key_benefits'])): ?>
                        <div class="knet-addon-hero-features">
                            <?php foreach ($addon['key_benefits'] as $benefit): ?>
                            <div class="knet-addon-hero-feature">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                <span><?php echo esc_html($benefit); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Action Buttons - Context aware -->
                        <div class="knet-addon-hero-actions">
                            <?php if ($status['status'] === 'active'): ?>
                                <!-- Already active - Configure button -->
                                <a href="<?php echo esc_url($status['action_url']); ?>" class="knet-btn knet-btn-primary knet-btn-lg">
                                    <?php esc_html_e('Configure Extension', 'knittnet'); ?>
                                </a>
                            <?php elseif ($status['status'] === 'inactive'): ?>
                                <!-- Installed but inactive - Activate button -->
                                <a href="<?php echo esc_url($status['action_url']); ?>" class="knet-btn knet-btn-primary knet-btn-lg">
                                    <?php esc_html_e('Activate Extension', 'knittnet'); ?>
                                </a>
                            <?php else: ?>
                                <!-- Not installed - Download -->
                                <?php $download_link = !empty($addon['download_url']) ? $addon['download_url'] : $addon['url']; ?>
                                <a href="<?php echo esc_url($download_link); ?>" target="_blank" class="knet-btn knet-btn-primary knet-btn-lg">
                                    <?php esc_html_e('Download Add-on', 'knittnet'); ?>
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo esc_url($addon['url']); ?>" target="_blank" class="knet-btn knet-btn-outline knet-btn-lg">
                                <?php esc_html_e('Documentation', 'knittnet'); ?>
                            </a>
                        </div>
                    </div>

                    <!-- Right: Feature Visual -->
                    <div class="knet-addon-hero-image">
                        <div class="knet-addon-feature-showcase" style="--showcase-accent: <?php echo esc_attr($addon['accent']); ?>">
                            <?php foreach ($addon['hero_features'] as $hf): ?>
                            <div class="knet-addon-showcase-item">
                                <div class="knet-addon-showcase-icon"><?php echo $hf['icon']; ?></div>
                                <div class="knet-addon-showcase-text">
                                    <strong><?php echo esc_html($hf['title']); ?></strong>
                                    <span><?php echo esc_html($hf['desc']); ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </main>
    </div>

    <!-- Hidden fields for JavaScript -->
    <input type="hidden" id="knittnet-ajax-url" value="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" />
    <input type="hidden" id="knittnet-nonce" value="<?php echo esc_attr(wp_create_nonce('knittnet_activate_license_nonce')); ?>" />
    <input type="hidden" id="knittnet-api-url" value="https://knittnet.ai" />
    <?php
}

/**
 * Get addon status information
 */
function knittnet_get_addon_status_info($plugin_file, $config_page) {
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $all_plugins = get_plugins();
    $active_plugins = get_option('active_plugins', array());

    if (isset($all_plugins[$plugin_file])) {
        if (in_array($plugin_file, $active_plugins)) {
            return array(
                'status' => 'active',
                'action_url' => admin_url('admin.php?page=' . $config_page),
                'action_text' => __('Configure', 'knittnet')
            );
        } else {
            return array(
                'status' => 'inactive',
                'action_url' => wp_nonce_url(
                    admin_url('plugins.php?action=activate&plugin=' . $plugin_file),
                    'activate-plugin_' . $plugin_file
                ),
                'action_text' => __('Activate', 'knittnet')
            );
        }
    }

    return array(
        'status' => 'not-installed',
        'action_url' => '',
        'action_text' => __('Get Extension', 'knittnet')
    );
}
