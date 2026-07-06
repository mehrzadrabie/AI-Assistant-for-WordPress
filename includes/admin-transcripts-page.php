<?php
/**
 * KnittNet Chat Transcripts Page - Redesigned with Sidebar Navigation
 *
 * @package KnittNet
 * @since 2.7.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the new sidebar-based Transcripts page
 */
function knittnet_render_transcripts_page($admin_instance, $page_data) {
    $is_activated = $admin_instance->is_activated();
    $plugin_url = plugin_dir_url(dirname(__FILE__));

    // Extract page data
    extract($page_data);
    ?>
    <div class="knet-admin-wrapper">
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
                <span class="knet-mobile-menu-title"><?php esc_html_e('Transcripts', 'knittnet'); ?></span>
                <button type="button" class="knet-mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'knittnet'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <nav class="knet-mobile-menu-nav">
                <!-- Overview Section -->
                <div class="knet-mobile-nav-section">
                    <div class="knet-mobile-nav-section-title"><?php esc_html_e('Overview', 'knittnet'); ?></div>
                    <button class="knet-mobile-nav-link active" data-target="dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                        <span><?php esc_html_e('Dashboard', 'knittnet'); ?></span>
                    </button>
                </div>
                <!-- Conversations Section -->
                <div class="knet-mobile-nav-section">
                    <div class="knet-mobile-nav-section-title"><?php esc_html_e('Conversations', 'knittnet'); ?></div>
                    <button class="knet-mobile-nav-link" data-target="all-chats">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span><?php esc_html_e('All Chats', 'knittnet'); ?></span>
                    </button>
                    <button class="knet-mobile-nav-link" data-target="leads">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg>
                        <span><?php esc_html_e('Leads', 'knittnet'); ?></span>
                    </button>
                </div>
                <!-- Settings Section -->
                <div class="knet-mobile-nav-section">
                    <div class="knet-mobile-nav-section-title"><?php esc_html_e('Settings', 'knittnet'); ?></div>
                    <button class="knet-mobile-nav-link" data-target="notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        <span><?php esc_html_e('Notifications', 'knittnet'); ?></span>
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
                <!-- Overview Section -->
                <div class="knet-nav-section">
                    <div class="knet-nav-section-title"><?php esc_html_e('Overview', 'knittnet'); ?></div>

                    <div class="knet-nav-item" data-section="dashboard">
                        <button class="knet-nav-link active" data-target="dashboard">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Dashboard', 'knittnet'); ?></span>
                        </button>
                    </div>
                </div>

                <!-- Conversations Section -->
                <div class="knet-nav-section">
                    <div class="knet-nav-section-title"><?php esc_html_e('Conversations', 'knittnet'); ?></div>

                    <div class="knet-nav-item" data-section="all-chats">
                        <button class="knet-nav-link" data-target="all-chats">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('All Chats', 'knittnet'); ?></span>
                            <span class="knet-nav-link-badge"><?php echo esc_html($total_chats); ?></span>
                        </button>
                    </div>

                    <div class="knet-nav-item" data-section="leads">
                        <button class="knet-nav-link" data-target="leads">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Leads', 'knittnet'); ?></span>
                            <span class="knet-nav-link-badge" id="knet-leads-nav-badge" style="display:none;">0</span>
                        </button>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="knet-nav-section">
                    <div class="knet-nav-section-title"><?php esc_html_e('Settings', 'knittnet'); ?></div>

                    <div class="knet-nav-item" data-section="notifications">
                        <button class="knet-nav-link" data-target="notifications">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Notifications', 'knittnet'); ?></span>
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
            <div id="dashboard" class="knet-section active">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Transcripts Dashboard', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Overview of your chatbot conversations and engagement metrics.', 'knittnet'); ?></p>
                </div>

                <!-- Stats Grid -->
                <div class="knet-stats-grid">
                    <div class="knet-stat-card">
                        <div class="knet-stat-icon knet-stat-icon-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <div class="knet-stat-content">
                            <span class="knet-stat-value"><?php echo esc_html($total_chats); ?></span>
                            <span class="knet-stat-label"><?php esc_html_e('Total Chats', 'knittnet'); ?></span>
                        </div>
                    </div>

                    <div class="knet-stat-card">
                        <div class="knet-stat-icon knet-stat-icon-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                        </div>
                        <div class="knet-stat-content">
                            <span class="knet-stat-value"><?php echo esc_html($total_messages); ?></span>
                            <span class="knet-stat-label"><?php esc_html_e('Total Messages', 'knittnet'); ?></span>
                        </div>
                    </div>

                    <div class="knet-stat-card">
                        <div class="knet-stat-icon knet-stat-icon-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div class="knet-stat-content">
                            <span class="knet-stat-value"><?php echo esc_html($total_users); ?></span>
                            <span class="knet-stat-label"><?php esc_html_e('Unique Users', 'knittnet'); ?></span>
                            <span class="knet-stat-sublabel">
                                <?php
                                echo sprintf(
                                    esc_html__('%d registered, %d guests', 'knittnet'),
                                    $registered_users,
                                    $guest_users
                                );
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="knet-stat-card">
                        <div class="knet-stat-icon knet-stat-icon-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div class="knet-stat-content">
                            <span class="knet-stat-value"><?php echo esc_html($avg_messages); ?></span>
                            <span class="knet-stat-label"><?php esc_html_e('Avg Messages/Chat', 'knittnet'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Activity Cards Row -->
                <div class="knet-cards-row">
                    <!-- Activity Timeline Card -->
                    <div class="knet-card knet-card-flex">
                        <div class="knet-card-header">
                            <h3 class="knet-card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                <?php esc_html_e('Recent Activity', 'knittnet'); ?>
                            </h3>
                        </div>
                        <div class="knet-card-body">
                            <div class="knet-activity-stats">
                                <div class="knet-activity-item">
                                    <div class="knet-activity-icon knet-activity-today">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    </div>
                                    <div class="knet-activity-content">
                                        <span class="knet-activity-value"><?php echo esc_html($today_chats); ?></span>
                                        <span class="knet-activity-label"><?php esc_html_e('Today', 'knittnet'); ?></span>
                                    </div>
                                </div>
                                <div class="knet-activity-item">
                                    <div class="knet-activity-icon knet-activity-week">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    </div>
                                    <div class="knet-activity-content">
                                        <span class="knet-activity-value"><?php echo esc_html($week_chats); ?></span>
                                        <span class="knet-activity-label"><?php esc_html_e('Last 7 Days', 'knittnet'); ?></span>
                                    </div>
                                </div>
                                <div class="knet-activity-item">
                                    <div class="knet-activity-icon knet-activity-month">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    </div>
                                    <div class="knet-activity-content">
                                        <span class="knet-activity-value"><?php echo esc_html($month_chats); ?></span>
                                        <span class="knet-activity-label"><?php esc_html_e('Last 30 Days', 'knittnet'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Insights Card -->
                    <div class="knet-card knet-card-flex">
                        <div class="knet-card-header">
                            <h3 class="knet-card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                <?php esc_html_e('Insights', 'knittnet'); ?>
                            </h3>
                        </div>
                        <div class="knet-card-body">
                            <div class="knet-insights-list">
                                <div class="knet-insight-item">
                                    <span class="knet-insight-label"><?php esc_html_e('Peak Activity', 'knittnet'); ?></span>
                                    <span class="knet-insight-value">
                                        <?php
                                        if ($busiest_hour) {
                                            $hour = $busiest_hour->hour;
                                            $formatted_hour = date('g A', strtotime("$hour:00"));
                                            echo esc_html($formatted_hour);
                                        } else {
                                            echo esc_html__('N/A', 'knittnet');
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="knet-insight-item">
                                    <span class="knet-insight-label"><?php esc_html_e('Engagement Rate', 'knittnet'); ?></span>
                                    <span class="knet-insight-value">
                                        <?php
                                        $engagement_rate = $total_chats > 0 ? round(($total_messages / $total_chats), 1) : 0;
                                        echo esc_html($engagement_rate . ' msg/chat');
                                        ?>
                                    </span>
                                </div>
                                <div class="knet-insight-item">
                                    <span class="knet-insight-label"><?php esc_html_e('Status', 'knittnet'); ?></span>
                                    <span class="knet-insight-value">
                                        <?php
                                        if ($today_chats > 0) {
                                            echo '<span class="knet-status-badge knet-status-active">' . esc_html__('Active', 'knittnet') . '</span>';
                                        } else if ($week_chats > 0) {
                                            echo '<span class="knet-status-badge knet-status-moderate">' . esc_html__('Moderate', 'knittnet') . '</span>';
                                        } else {
                                            echo '<span class="knet-status-badge knet-status-quiet">' . esc_html__('Quiet', 'knittnet') . '</span>';
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="knet-insight-item">
                                    <span class="knet-insight-label"><?php esc_html_e('Agent Tests', 'knittnet'); ?></span>
                                    <span class="knet-insight-value"><?php echo esc_html($agent_tests); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Satisfaction (last 30 days) — plan-a5b006 -->
                <div class="knet-card knet-satisfaction-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H7"/><path d="M3 10h4"/></svg>
                            <?php esc_html_e('Satisfaction (last 30 days)', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body">
                        <?php
                        $satisfaction_stats = isset($satisfaction_stats) && is_array($satisfaction_stats) ? $satisfaction_stats : array();
                        if (empty($satisfaction_stats)) :
                            ?>
                            <p class="knet-satisfaction-empty">
                                <?php esc_html_e('No ratings yet. The widget shows the prompt after a conversation has had at least two bot replies and the visitor goes idle for 60 seconds.', 'knittnet'); ?>
                            </p>
                            <?php
                        else :
                            ?>
                            <ul class="knet-satisfaction-list">
                                <?php foreach ($satisfaction_stats as $row) :
                                    $bot_label = $row['bot_id'] === 'default'
                                        ? esc_html__('Default bot', 'knittnet')
                                        : esc_html($row['bot_id']);
                                    ?>
                                    <li class="knet-satisfaction-row">
                                        <span class="knet-satisfaction-bot"><?php echo $bot_label; ?></span>
                                        <span class="knet-satisfaction-meta">
                                            <span class="knet-satisfaction-total"><?php
                                                echo esc_html(sprintf(
                                                    /* translators: %d: number of rated sessions */
                                                    _n('%d rated session', '%d rated sessions', $row['total'], 'knittnet'),
                                                    $row['total']
                                                ));
                                            ?></span>
                                            <span class="knet-satisfaction-bar" aria-hidden="true">
                                                <span class="knet-satisfaction-bar-up" style="width: <?php echo esc_attr($row['positive_pct']); ?>%;"></span>
                                                <span class="knet-satisfaction-bar-down" style="width: <?php echo esc_attr($row['negative_pct']); ?>%;"></span>
                                            </span>
                                            <span class="knet-satisfaction-pct knet-satisfaction-pct-up" title="<?php esc_attr_e('Positive ratings', 'knittnet'); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H7"/><path d="M3 10h4"/></svg>
                                                <?php echo esc_html($row['positive_pct']); ?>%
                                            </span>
                                            <span class="knet-satisfaction-pct knet-satisfaction-pct-down" title="<?php esc_attr_e('Negative ratings', 'knittnet'); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 14V2"/><path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H17"/><path d="M21 14h-4"/></svg>
                                                <?php echo esc_html($row['negative_pct']); ?>%
                                            </span>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php
                        endif;
                        ?>
                    </div>
                </div>

                <!-- Activity Chart -->
                <div class="knet-card knet-activity-chart-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            <?php esc_html_e('7-Day Activity', 'knittnet'); ?>
                        </h3>
                        <div class="knet-chart-legend">
                            <span class="knet-legend-item knet-legend-chats">
                                <span class="knet-legend-dot"></span>
                                <?php esc_html_e('Chats', 'knittnet'); ?>
                            </span>
                            <span class="knet-legend-item knet-legend-messages">
                                <span class="knet-legend-dot"></span>
                                <?php esc_html_e('Messages', 'knittnet'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="knet-card-body">
                        <div class="knet-chart-container">
                            <canvas id="knittnet-activity-chart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                            <?php esc_html_e('Quick Actions', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body">
                        <div class="knet-quick-actions">
                            <button type="button" class="knet-quick-action-btn" data-action="view-chats">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                <span><?php esc_html_e('View All Chats', 'knittnet'); ?></span>
                            </button>
                            <button type="button" id="knet-export-btn" class="knet-quick-action-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                <span><?php esc_html_e('Export All Chats', 'knittnet'); ?></span>
                            </button>
                            <button type="button" class="knet-quick-action-btn" data-action="settings">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                <span><?php esc_html_e('Notification Settings', 'knittnet'); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Chats Section - Split Panel Layout -->
            <div id="all-chats" class="knet-section">
                <div class="knet-split-panel">
                    <!-- Left Panel - Chat List -->
                    <div class="knet-chat-list-panel">
                        <!-- Bulk Actions Toolbar -->
                        <div class="knet-bulk-toolbar">
                            <label class="knet-bulk-select-all">
                                <input type="checkbox" id="knet-select-all">
                                <span><?php esc_html_e('All', 'knittnet'); ?></span>
                            </label>
                            <span class="knet-selected-count" id="knet-selected-count">0</span>
                            <div class="knet-bulk-actions">
                                <button type="button" class="knet-bulk-btn" id="knet-sort-btn" title="<?php esc_attr_e('Sort', 'knittnet'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="16" y2="6"/><line x1="4" y1="12" x2="12" y2="12"/><line x1="4" y1="18" x2="8" y2="18"/><polyline points="15 15 18 18 21 15"/></svg>
                                    <span><?php esc_html_e('Sort', 'knittnet'); ?></span>
                                </button>
                                <button type="button" class="knet-bulk-btn knet-bulk-delete" id="knet-delete-selected" disabled title="<?php esc_attr_e('Delete Selected', 'knittnet'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="knet-panel-header">
                            <div class="knet-search-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" id="knet-search-transcripts" class="knet-search-input" placeholder="<?php esc_attr_e('Search chats...', 'knittnet'); ?>">
                            </div>
                            <div class="knet-panel-title-row">
                                <span class="knet-panel-count" id="knet-chat-count">0 / 0 chats</span>
                                <div class="knet-panel-actions">
                                    <button type="button" id="knet-refresh-list" class="knet-icon-btn" title="<?php esc_attr_e('Refresh', 'knittnet'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="knet-chat-list" id="knet-chat-list">
                            <!-- Chat list items loaded via AJAX -->
                        </div>
                        <div class="knet-panel-footer">
                            <div class="knet-pagination-simple" id="knet-pagination">
                                <!-- Pagination controls -->
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel - Conversation View -->
                    <div class="knet-conversation-panel" id="knet-conversation-panel">
                        <!-- Empty State -->
                        <div class="knet-conversation-empty" id="knet-conversation-empty">
                            <div class="knet-empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </div>
                            <h3><?php esc_html_e('Select a conversation', 'knittnet'); ?></h3>
                            <p><?php esc_html_e('Choose a chat from the list to view the full conversation.', 'knittnet'); ?></p>
                        </div>

                        <!-- Conversation Content (hidden initially) -->
                        <div class="knet-conversation-content" id="knet-conversation-content" style="display: none;">
                            <!-- Header -->
                            <div class="knet-conversation-header">
                                <button type="button" class="knet-mobile-back-btn" id="knet-transcript-back-btn" style="display: none;" title="<?php esc_attr_e('Back to list', 'knittnet'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                                </button>
                                <div class="knet-user-avatar" id="knet-user-avatar">
                                    <span>MX</span>
                                </div>
                                <div class="knet-user-info">
                                    <div class="knet-user-name" id="knet-user-name">User Name</div>
                                    <div class="knet-user-meta" id="knet-user-meta">User ID: 123</div>
                                </div>
                                <button type="button" class="knet-details-toggle" id="knet-toggle-details" title="<?php esc_attr_e('Show details', 'knittnet'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                </button>
                            </div>

                            <!-- Details Drawer (collapsible) -->
                            <div class="knet-details-drawer" id="knet-details-drawer" style="display: none;">
                                <div class="knet-details-section">
                                    <h4><?php esc_html_e('Chat Details', 'knittnet'); ?></h4>
                                    <div class="knet-detail-row">
                                        <span class="knet-detail-label"><?php esc_html_e('Total Messages', 'knittnet'); ?></span>
                                        <span class="knet-detail-value" id="knet-detail-messages">0</span>
                                    </div>
                                    <div class="knet-detail-row">
                                        <span class="knet-detail-label"><?php esc_html_e('Started', 'knittnet'); ?></span>
                                        <span class="knet-detail-value" id="knet-detail-started">-</span>
                                    </div>
                                    <div class="knet-detail-row">
                                        <span class="knet-detail-label"><?php esc_html_e('Page', 'knittnet'); ?></span>
                                        <span class="knet-detail-value" id="knet-detail-page">-</span>
                                    </div>
                                    <div class="knet-detail-row" id="knet-detail-ip-row" style="display: none;">
                                        <span class="knet-detail-label"><?php esc_html_e('IP Address', 'knittnet'); ?></span>
                                        <span class="knet-detail-value" id="knet-detail-ip">-</span>
                                    </div>
                                    <div class="knet-detail-row" id="knet-detail-email-row" style="display: none;">
                                        <span class="knet-detail-label"><?php esc_html_e('Email', 'knittnet'); ?></span>
                                        <span class="knet-detail-value" id="knet-detail-email">-</span>
                                    </div>
                                    <div class="knet-detail-row" id="knet-detail-feedback-row" style="display: none;">
                                        <span class="knet-detail-label"><?php esc_html_e('Feedback', 'knittnet'); ?></span>
                                        <span class="knet-detail-value knet-detail-feedback-value" id="knet-detail-feedback">-</span>
                                    </div>
                                </div>
                                <div class="knet-details-section" id="knet-clicked-section" style="display: none;">
                                    <h4><?php esc_html_e('Clicked Links', 'knittnet'); ?></h4>
                                    <div class="knet-clicked-links" id="knet-clicked-links">
                                        <!-- Links populated via JS -->
                                    </div>
                                </div>
                                <div class="knet-details-actions">
                                    <button type="button" id="knet-delete-current" class="knet-btn knet-btn-danger knet-btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        <?php esc_html_e('Delete Chat', 'knittnet'); ?>
                                    </button>
                                    <button type="button" id="knet-export-current" class="knet-btn knet-btn-secondary knet-btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        <?php esc_html_e('Export', 'knittnet'); ?>
                                    </button>
                                </div>
                                <div class="knet-translate-section">
                                    <div class="knet-translate-controls">
                                        <select id="knet-translate-lang" class="knet-translate-select" title="<?php esc_attr_e('Target language', 'knittnet'); ?>">
                                            <option value="en">English</option>
                                            <option value="es">Español</option>
                                            <option value="fr">Français</option>
                                            <option value="de">Deutsch</option>
                                            <option value="it">Italiano</option>
                                            <option value="pt">Português</option>
                                            <option value="nl">Nederlands</option>
                                            <option value="ru">Русский</option>
                                            <option value="zh">中文</option>
                                            <option value="ja">日本語</option>
                                            <option value="ko">한국어</option>
                                            <option value="ar">العربية</option>
                                            <option value="hi">हिन्दी</option>
                                            <option value="tr">Türkçe</option>
                                            <option value="pl">Polski</option>
                                            <option value="vi">Tiếng Việt</option>
                                            <option value="th">ไทย</option>
                                            <option value="id">Bahasa Indonesia</option>
                                            <option value="sv">Svenska</option>
                                            <option value="da">Dansk</option>
                                        </select>
                                        <button type="button" id="knet-translate-btn" class="knet-btn knet-btn-primary knet-btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
                                            <span class="knet-translate-text"><?php esc_html_e('Translate', 'knittnet'); ?></span>
                                        </button>
                                        <button type="button" id="knet-show-original-btn" class="knet-btn knet-btn-secondary knet-btn-sm" style="display: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                            <?php esc_html_e('Original', 'knittnet'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Messages Area -->
                            <div class="knet-messages-area" id="knet-messages-area">
                                <!-- Messages loaded via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>

                <?php wp_nonce_field('knittnet_delete_chat_history', 'knittnet_delete_chat_nonce'); ?>

                <!-- Transcript delete confirm modal (shared by bulk + individual delete) -->
                <div id="knet-transcript-confirm" class="knet-modal-overlay" style="display:none;">
                    <div class="knet-modal-content knet-leads-confirm-box">
                        <div class="knet-modal-header">
                            <h2 id="knet-transcript-confirm-title"><?php esc_html_e('Delete conversation?', 'knittnet'); ?></h2>
                            <button type="button" class="knet-modal-close" data-knet-transcript-close>&times;</button>
                        </div>
                        <div class="knet-modal-body">
                            <p id="knet-transcript-confirm-body"></p>
                            <label class="knet-transcript-confirm-check">
                                <input type="checkbox" id="knet-transcript-also-delete-lead">
                                <span>
                                    <strong><?php esc_html_e('Also remove the lead from the Leads tab', 'knittnet'); ?></strong>
                                    <em class="knet-transcript-confirm-sublabel"><?php esc_html_e('By default, leads are kept and marked "Chat deleted" so your contact list is preserved.', 'knittnet'); ?></em>
                                </span>
                            </label>
                            <p class="knet-leads-confirm-warning"><?php esc_html_e('This cannot be undone.', 'knittnet'); ?></p>
                        </div>
                        <div class="knet-leads-confirm-actions">
                            <button type="button" class="knet-btn knet-btn-secondary" data-knet-transcript-close><?php esc_html_e('Cancel', 'knittnet'); ?></button>
                            <button type="button" class="knet-btn knet-btn-danger" id="knet-transcript-confirm-go"><?php esc_html_e('Delete', 'knittnet'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leads Section -->
            <div id="leads" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Leads', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Captured visitor emails and names from the lead-capture form, grouped by unique lead.', 'knittnet'); ?></p>
                </div>

                <!-- Stats strip -->
                <div class="knet-leads-stats">
                    <div class="knet-stat-card">
                        <div class="knet-stat-icon knet-stat-icon-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div class="knet-stat-content">
                            <span class="knet-stat-value" id="knet-leads-stat-total">0</span>
                            <span class="knet-stat-label"><?php esc_html_e('Total Leads', 'knittnet'); ?></span>
                        </div>
                    </div>
                    <div class="knet-stat-card">
                        <div class="knet-stat-icon knet-stat-icon-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                        </div>
                        <div class="knet-stat-content">
                            <span class="knet-stat-value" id="knet-leads-stat-new">0</span>
                            <span class="knet-stat-label"><?php esc_html_e('New This Week', 'knittnet'); ?></span>
                        </div>
                    </div>
                    <div class="knet-stat-card">
                        <div class="knet-stat-icon knet-stat-icon-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <div class="knet-stat-content">
                            <span class="knet-stat-value" id="knet-leads-stat-avg">0</span>
                            <span class="knet-stat-label"><?php esc_html_e('Avg Convos / Lead', 'knittnet'); ?></span>
                        </div>
                    </div>
                    <div class="knet-stat-card">
                        <div class="knet-stat-icon knet-stat-icon-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <div class="knet-stat-content">
                            <span class="knet-stat-value" id="knet-leads-stat-orphan">0%</span>
                            <span class="knet-stat-label"><?php esc_html_e('Orphan Leads', 'knittnet'); ?></span>
                            <span class="knet-stat-sublabel" id="knet-leads-stat-orphan-sub"><?php esc_html_e('Captured but never chatted', 'knittnet'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Top pages card -->
                <div class="knet-card knet-leads-toppages">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v18H3z"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                            <?php esc_html_e('Top Pages Capturing Leads', 'knittnet'); ?>
                        </h3>
                        <span class="knet-leads-toppages-hint"><?php esc_html_e('Click a page to filter the list below', 'knittnet'); ?></span>
                    </div>
                    <div class="knet-card-body">
                        <div class="knet-leads-toppages-list" id="knet-leads-toppages-list">
                            <div class="knet-leads-empty-mini"><?php esc_html_e('No page data yet.', 'knittnet'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Leads toolbar + table -->
                <div class="knet-card knet-leads-card">
                    <div class="knet-leads-toolbar">
                        <div class="knet-leads-toolbar-left">
                            <label class="knet-bulk-select-all">
                                <input type="checkbox" id="knet-leads-select-all">
                                <span><?php esc_html_e('All', 'knittnet'); ?></span>
                            </label>
                            <span class="knet-selected-count" id="knet-leads-selected-count">0</span>
                            <button type="button" class="knet-bulk-btn knet-bulk-delete" id="knet-leads-delete-selected" disabled title="<?php esc_attr_e('Delete Selected', 'knittnet'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                <span><?php esc_html_e('Delete', 'knittnet'); ?></span>
                            </button>
                        </div>
                        <div class="knet-leads-toolbar-right">
                            <div class="knet-leads-export" id="knet-leads-export-wrap">
                                <button type="button" class="knet-btn knet-btn-secondary knet-btn-sm" id="knet-leads-export-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    <?php esc_html_e('Export', 'knittnet'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <div class="knet-leads-export-menu" id="knet-leads-export-menu">
                                    <button type="button" data-fields="email_and_name" data-scope="all"><?php esc_html_e('All leads — email + name', 'knittnet'); ?></button>
                                    <button type="button" data-fields="email_only" data-scope="all"><?php esc_html_e('All leads — email only', 'knittnet'); ?></button>
                                    <button type="button" data-fields="email_and_name" data-scope="selected" disabled><?php esc_html_e('Selected — email + name', 'knittnet'); ?></button>
                                    <button type="button" data-fields="email_only" data-scope="selected" disabled><?php esc_html_e('Selected — email only', 'knittnet'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="knet-leads-filters">
                        <div class="knet-search-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" id="knet-leads-search" class="knet-search-input" placeholder="<?php esc_attr_e('Search by email or name...', 'knittnet'); ?>">
                        </div>
                        <select id="knet-leads-date-range" class="knet-select">
                            <option value="all"><?php esc_html_e('All time', 'knittnet'); ?></option>
                            <option value="today"><?php esc_html_e('Last 24 hours', 'knittnet'); ?></option>
                            <option value="7d"><?php esc_html_e('Last 7 days', 'knittnet'); ?></option>
                            <option value="30d"><?php esc_html_e('Last 30 days', 'knittnet'); ?></option>
                            <option value="90d"><?php esc_html_e('Last 90 days', 'knittnet'); ?></option>
                        </select>
                        <select id="knet-leads-status" class="knet-select">
                            <option value="all"><?php esc_html_e('All leads', 'knittnet'); ?></option>
                            <option value="with"><?php esc_html_e('With conversation', 'knittnet'); ?></option>
                            <option value="chat_deleted"><?php esc_html_e('Chat deleted', 'knittnet'); ?></option>
                            <option value="orphan"><?php esc_html_e('Orphan (no chat)', 'knittnet'); ?></option>
                        </select>
                        <button type="button" class="knet-btn knet-btn-ghost knet-btn-sm" id="knet-leads-clear-filters" style="display:none;">
                            <?php esc_html_e('Clear filters', 'knittnet'); ?>
                        </button>
                        <span class="knet-leads-active-page-filter" id="knet-leads-active-page-filter" style="display:none;">
                            <span class="knet-leads-page-chip-label"></span>
                            <button type="button" class="knet-leads-page-chip-remove" aria-label="<?php esc_attr_e('Remove page filter', 'knittnet'); ?>">&times;</button>
                        </span>
                    </div>

                    <div class="knet-leads-table-wrap">
                        <table class="knet-leads-table" id="knet-leads-table">
                            <thead>
                                <tr>
                                    <th class="knet-leads-col-check"></th>
                                    <th class="knet-leads-col-lead"><?php esc_html_e('Lead', 'knittnet'); ?></th>
                                    <th class="knet-leads-col-count"><?php esc_html_e('Conversations', 'knittnet'); ?></th>
                                    <th class="knet-leads-col-last"><?php esc_html_e('Last seen', 'knittnet'); ?></th>
                                    <th class="knet-leads-col-page"><?php esc_html_e('Top page', 'knittnet'); ?></th>
                                    <th class="knet-leads-col-actions"></th>
                                </tr>
                            </thead>
                            <tbody id="knet-leads-tbody">
                                <tr><td colspan="6" class="knet-leads-loading"><span class="spinner is-active"></span></td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="knet-panel-footer">
                        <span class="knet-leads-count" id="knet-leads-count">0 leads</span>
                        <div class="knet-pagination-simple" id="knet-leads-pagination"></div>
                    </div>
                </div>

                <!-- Delete confirm modal -->
                <div id="knet-leads-confirm" class="knet-modal-overlay" style="display:none;">
                    <div class="knet-modal-content knet-leads-confirm-box">
                        <div class="knet-modal-header">
                            <h2><?php esc_html_e('Delete leads', 'knittnet'); ?></h2>
                            <button type="button" class="knet-modal-close" data-knet-leads-close>&times;</button>
                        </div>
                        <div class="knet-modal-body">
                            <p id="knet-leads-confirm-body"></p>
                            <p class="knet-leads-confirm-warning"><?php esc_html_e('This cannot be undone.', 'knittnet'); ?></p>
                        </div>
                        <div class="knet-leads-confirm-actions">
                            <button type="button" class="knet-btn knet-btn-secondary" data-knet-leads-close><?php esc_html_e('Cancel', 'knittnet'); ?></button>
                            <button type="button" class="knet-btn knet-btn-danger" id="knet-leads-confirm-go"><?php esc_html_e('Delete permanently', 'knittnet'); ?></button>
                        </div>
                    </div>
                </div>

                <?php wp_nonce_field('knittnet_delete_leads', 'knittnet_leads_delete_nonce'); ?>
                <?php wp_nonce_field('knittnet_export_leads', 'knittnet_leads_export_nonce'); ?>
            </div>

            <!-- Notifications Section -->
            <div id="notifications" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Notification Settings', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Configure email notifications and transcript management options.', 'knittnet'); ?></p>
                </div>

                <div id="knet-notifications-form" class="knittnet-autosave-section">

                    <!-- Email Notifications Card -->
                    <div class="knet-card">
                        <div class="knet-card-header">
                            <h3 class="knet-card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                <?php esc_html_e('Email Notifications', 'knittnet'); ?>
                            </h3>
                        </div>
                        <div class="knet-card-body">
                            <?php
                            $options = get_option('knittnet_transcripts_options', array());
                            $enabled = isset($options['knittnet_enable_notifications']) ? $options['knittnet_enable_notifications'] : 0;
                            $email = isset($options['knittnet_notification_email']) ? $options['knittnet_notification_email'] : get_option('admin_email');
                            ?>
                            <div class="knet-field">
                                <div class="knet-toggle-row">
                                    <label class="knittnet-toggle-switch">
                                        <input type="checkbox"
                                               name="knittnet_transcripts_options[knittnet_enable_notifications]"
                                               class="knittnet-autosave-field"
                                               value="1"
                                               data-nonce="<?php echo wp_create_nonce('knittnet_autosave_nonce'); ?>"
                                               <?php checked(1, $enabled); ?>>
                                        <span class="knittnet-toggle-slider"></span>
                                    </label>
                                    <span class="knet-toggle-label"><?php esc_html_e('Enable Chat Notifications', 'knittnet'); ?></span>
                                </div>
                                <p class="knet-field-description"><?php esc_html_e('Send email notification when a new chat session starts.', 'knittnet'); ?></p>
                            </div>

                            <div class="knet-field">
                                <label class="knet-field-label"><?php esc_html_e('Notification Email', 'knittnet'); ?></label>
                                <input type="email"
                                       name="knittnet_transcripts_options[knittnet_notification_email]"
                                       class="knet-input knittnet-autosave-field"
                                       value="<?php echo esc_attr($email); ?>"
                                       data-nonce="<?php echo wp_create_nonce('knittnet_autosave_nonce'); ?>">
                                <p class="knet-field-description"><?php esc_html_e('Email address where notifications will be sent.', 'knittnet'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Auto-Email Transcript Card -->
                    <div class="knet-card">
                        <div class="knet-card-header">
                            <h3 class="knet-card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                                <?php esc_html_e('Auto-Email Transcript', 'knittnet'); ?>
                            </h3>
                        </div>
                        <div class="knet-card-body">
                            <?php
                            $auto_email_enabled = isset($options['knittnet_auto_email_transcript_enabled']) ? $options['knittnet_auto_email_transcript_enabled'] : 0;
                            $delay = isset($options['knittnet_auto_email_transcript_delay']) ? $options['knittnet_auto_email_transcript_delay'] : '30';
                            $require_contact = isset($options['knittnet_auto_email_transcript_require_contact']) ? $options['knittnet_auto_email_transcript_require_contact'] : 0;
                            ?>
                            <div class="knet-field">
                                <div class="knet-toggle-row">
                                    <label class="knittnet-toggle-switch">
                                        <input type="checkbox"
                                               name="knittnet_transcripts_options[knittnet_auto_email_transcript_enabled]"
                                               class="knittnet-autosave-field"
                                               value="1"
                                               data-nonce="<?php echo wp_create_nonce('knittnet_autosave_nonce'); ?>"
                                               <?php checked(1, $auto_email_enabled); ?>>
                                        <span class="knittnet-toggle-slider"></span>
                                    </label>
                                    <span class="knet-toggle-label"><?php esc_html_e('Auto-Email Full Transcript', 'knittnet'); ?></span>
                                </div>
                                <p class="knet-field-description"><?php esc_html_e('Automatically send the full conversation transcript after the chat ends.', 'knittnet'); ?></p>
                            </div>

                            <div class="knet-field">
                                <label class="knet-field-label"><?php esc_html_e('Send Transcript After', 'knittnet'); ?></label>
                                <select name="knittnet_transcripts_options[knittnet_auto_email_transcript_delay]"
                                        class="knet-select knittnet-autosave-field"
                                        data-nonce="<?php echo wp_create_nonce('knittnet_autosave_nonce'); ?>">
                                    <option value="15" <?php selected($delay, '15'); ?>><?php esc_html_e('15 minutes', 'knittnet'); ?></option>
                                    <option value="30" <?php selected($delay, '30'); ?>><?php esc_html_e('30 minutes', 'knittnet'); ?></option>
                                    <option value="60" <?php selected($delay, '60'); ?>><?php esc_html_e('1 hour', 'knittnet'); ?></option>
                                </select>
                            </div>

                            <div class="knet-field">
                                <div class="knet-toggle-row">
                                    <label class="knittnet-toggle-switch">
                                        <input type="checkbox"
                                               name="knittnet_transcripts_options[knittnet_auto_email_transcript_require_contact]"
                                               class="knittnet-autosave-field"
                                               value="1"
                                               data-nonce="<?php echo wp_create_nonce('knittnet_autosave_nonce'); ?>"
                                               <?php checked(1, $require_contact); ?>>
                                        <span class="knittnet-toggle-slider"></span>
                                    </label>
                                    <span class="knet-toggle-label"><?php esc_html_e('Only send if user provided contact info', 'knittnet'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transcript Management Card -->
                    <div class="knet-card">
                        <div class="knet-card-header">
                            <h3 class="knet-card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                <?php esc_html_e('Transcript Management', 'knittnet'); ?>
                            </h3>
                        </div>
                        <div class="knet-card-body">
                            <?php
                            $auto_delete = isset($options['knittnet_auto_delete_transcripts']) ? $options['knittnet_auto_delete_transcripts'] : 'never';
                            ?>
                            <div class="knet-field">
                                <label class="knet-field-label"><?php esc_html_e('Auto-Delete Old Transcripts', 'knittnet'); ?></label>
                                <select name="knittnet_transcripts_options[knittnet_auto_delete_transcripts]"
                                        class="knet-select knittnet-autosave-field"
                                        data-nonce="<?php echo wp_create_nonce('knittnet_autosave_nonce'); ?>">
                                    <option value="never" <?php selected($auto_delete, 'never'); ?>><?php esc_html_e('Never (Keep All)', 'knittnet'); ?></option>
                                    <option value="1week" <?php selected($auto_delete, '1week'); ?>><?php esc_html_e('After 1 Week', 'knittnet'); ?></option>
                                    <option value="2weeks" <?php selected($auto_delete, '2weeks'); ?>><?php esc_html_e('After 2 Weeks', 'knittnet'); ?></option>
                                    <option value="1month" <?php selected($auto_delete, '1month'); ?>><?php esc_html_e('After 1 Month', 'knittnet'); ?></option>
                                </select>
                                <p class="knet-field-description"><?php esc_html_e('Automatically delete old chat transcripts to manage database size and privacy.', 'knittnet'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- RAG Context Modal -->
    <div id="knet-rag-modal" class="knet-modal-overlay" style="display: none;">
        <div class="knet-modal-content">
            <div class="knet-modal-header">
                <h2><?php esc_html_e('Message Context', 'knittnet'); ?></h2>
                <button type="button" class="knet-modal-close">&times;</button>
            </div>
            <div class="knet-modal-body">
                <!-- Tabs -->
                <div class="knet-context-tabs">
                    <button type="button" class="knet-context-tab active" data-tab="sources">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        <?php esc_html_e('Sources', 'knittnet'); ?>
                        <span class="knet-tab-badge" id="knet-sources-count" style="display: none;">0</span>
                    </button>
                    <button type="button" class="knet-context-tab" data-tab="actions">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        <?php esc_html_e('Actions', 'knittnet'); ?>
                        <span class="knet-tab-badge" id="knet-actions-count" style="display: none;">0</span>
                    </button>
                </div>

                <div class="knet-rag-loading" style="display: none;">
                    <span class="spinner is-active"></span>
                    <?php esc_html_e('Loading context...', 'knittnet'); ?>
                </div>

                <!-- Sources Tab Content -->
                <div class="knet-tab-content" id="knet-tab-sources">
                    <div class="knet-rag-content">
                        <!-- RAG context will be populated via JavaScript -->
                    </div>
                </div>

                <!-- Actions Tab Content -->
                <div class="knet-tab-content" id="knet-tab-actions" style="display: none;">
                    <div class="knet-actions-content">
                        <!-- Action scores will be populated via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
