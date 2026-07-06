<?php
/**
 * KnittNet Admin Settings Page - Redesigned with Sidebar Navigation
 *
 * @package KnittNet
 * @since 2.7.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the new sidebar-based admin settings page
 */
function knittnet_render_settings_page($admin_instance) {
    $is_activated = $admin_instance->is_activated();
    $options = get_option('knittnet_options', array());
    $plugin_url = plugin_dir_url(dirname(__FILE__));
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
                <span class="knet-mobile-menu-title"><?php esc_html_e('Settings', 'knittnet'); ?></span>
                <button type="button" class="knet-mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'knittnet'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <nav class="knet-mobile-menu-nav">
                <!-- Configuration Section -->
                <div class="knet-mobile-nav-section">
                    <div class="knet-mobile-nav-section-title"><?php esc_html_e('Configuration', 'knittnet'); ?></div>
                    <button class="knet-mobile-nav-link" data-parent="chatbot">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
                        <span><?php esc_html_e('Chatbot', 'knittnet'); ?></span>
                        <svg class="knet-mobile-nav-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                    <div class="knet-mobile-nav-sub" data-parent="chatbot">
                        <button class="knet-mobile-nav-sub-link active" data-target="chatbot-ai-models"><?php esc_html_e('AI Models', 'knittnet'); ?></button>
                        <button class="knet-mobile-nav-sub-link" data-target="chatbot-behavior"><?php esc_html_e('Behavior', 'knittnet'); ?></button>
                        <button class="knet-mobile-nav-sub-link" data-target="chatbot-display"><?php esc_html_e('Display', 'knittnet'); ?></button>
                        <button class="knet-mobile-nav-sub-link" data-target="chatbot-lead-capture"><?php esc_html_e('Lead Capture', 'knittnet'); ?></button>
                        <button class="knet-mobile-nav-sub-link" data-target="chatbot-quick-questions"><?php esc_html_e('Quick Questions', 'knittnet'); ?></button>
                        <button class="knet-mobile-nav-sub-link" data-target="chatbot-rate-limits"><?php esc_html_e('Rate Limits', 'knittnet'); ?></button>
                    </div>
                    <button class="knet-mobile-nav-link" data-target="api-keys">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                        <span><?php esc_html_e('API Keys', 'knittnet'); ?></span>
                    </button>
                    <button class="knet-mobile-nav-link" data-target="optimization">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                        <span><?php esc_html_e('Optimization', 'knittnet'); ?></span>
                    </button>
                    <button class="knet-mobile-nav-link" data-target="testing">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 2v6"/><path d="M15 2v6"/><path d="M12 17v5"/><path d="M5 8h14"/><path d="M6 11v-1a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v1"/><path d="m9 14 3 3 3-3"/></svg>
                        <span><?php esc_html_e('Testing', 'knittnet'); ?></span>
                    </button>
                </div>
                <!-- Integrations Section -->
                <div class="knet-mobile-nav-section">
                    <div class="knet-mobile-nav-section-title"><?php esc_html_e('Integrations', 'knittnet'); ?></div>
                    <button class="knet-mobile-nav-link" data-parent="integrations">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                        <span><?php esc_html_e('Integrations', 'knittnet'); ?></span>
                        <svg class="knet-mobile-nav-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                    <div class="knet-mobile-nav-sub" data-parent="integrations">
                        <button class="knet-mobile-nav-sub-link" data-target="integrations-toolbar"><?php esc_html_e('Toolbar', 'knittnet'); ?></button>
                        <button class="knet-mobile-nav-sub-link" data-target="integrations-loops"><?php esc_html_e('Loops', 'knittnet'); ?></button>
                        <button class="knet-mobile-nav-sub-link" data-target="integrations-brave"><?php esc_html_e('Brave Search', 'knittnet'); ?></button>
                        <button class="knet-mobile-nav-sub-link" data-target="integrations-slack"><?php esc_html_e('Slack', 'knittnet'); ?></button>
                        <button class="knet-mobile-nav-sub-link" data-target="integrations-telegram"><?php esc_html_e('Telegram', 'knittnet'); ?></button>
                    </div>
                </div>
                <!-- Resources Section -->
                <div class="knet-mobile-nav-section">
                    <div class="knet-mobile-nav-section-title"><?php esc_html_e('Resources', 'knittnet'); ?></div>
                    <button class="knet-mobile-nav-link" data-target="tutorials">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                        <span><?php esc_html_e('Tutorials', 'knittnet'); ?></span>
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
                <!-- Chatbot Section -->
                <div class="knet-nav-section">
                    <div class="knet-nav-section-title"><?php esc_html_e('Configuration', 'knittnet'); ?></div>

                    <div class="knet-nav-item expanded" data-section="chatbot">
                        <button class="knet-nav-link active">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Chatbot', 'knittnet'); ?></span>
                            <svg class="knet-nav-link-arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                        <div class="knet-nav-sub">
                            <button class="knet-nav-sub-link active" data-target="chatbot-ai-models"><?php esc_html_e('AI Models', 'knittnet'); ?></button>
                            <button class="knet-nav-sub-link" data-target="chatbot-behavior"><?php esc_html_e('Behavior', 'knittnet'); ?></button>
                            <button class="knet-nav-sub-link" data-target="chatbot-display"><?php esc_html_e('Display', 'knittnet'); ?></button>
                            <button class="knet-nav-sub-link" data-target="chatbot-lead-capture"><?php esc_html_e('Lead Capture', 'knittnet'); ?></button>
                            <button class="knet-nav-sub-link" data-target="chatbot-quick-questions"><?php esc_html_e('Quick Questions', 'knittnet'); ?></button>
                            <button class="knet-nav-sub-link" data-target="chatbot-rate-limits"><?php esc_html_e('Rate Limits', 'knittnet'); ?></button>
                        </div>
                    </div>

                    <div class="knet-nav-item" data-section="api-keys">
                        <button class="knet-nav-link" data-target="api-keys">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('API Keys', 'knittnet'); ?></span>
                        </button>
                    </div>

                    <div class="knet-nav-item" data-section="optimization">
                        <button class="knet-nav-link" data-target="optimization">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Optimization', 'knittnet'); ?></span>
                        </button>
                    </div>

                    <div class="knet-nav-item" data-section="testing">
                        <button class="knet-nav-link" data-target="testing">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 2v6"/><path d="M15 2v6"/><path d="M12 17v5"/><path d="M5 8h14"/><path d="M6 11v-1a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v1"/><path d="m9 14 3 3 3-3"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Testing', 'knittnet'); ?></span>
                        </button>
                    </div>
                </div>

                <!-- Integrations Section -->
                <div class="knet-nav-section">
                    <div class="knet-nav-section-title"><?php esc_html_e('Integrations', 'knittnet'); ?></div>

                    <div class="knet-nav-item" data-section="integrations">
                        <button class="knet-nav-link">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Integrations', 'knittnet'); ?></span>
                            <svg class="knet-nav-link-arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                        <div class="knet-nav-sub">
                            <button class="knet-nav-sub-link" data-target="integrations-toolbar"><?php esc_html_e('Toolbar', 'knittnet'); ?></button>
                            <button class="knet-nav-sub-link" data-target="integrations-loops"><?php esc_html_e('Loops', 'knittnet'); ?></button>
                            <button class="knet-nav-sub-link" data-target="integrations-brave"><?php esc_html_e('Brave Search', 'knittnet'); ?></button>
                            <button class="knet-nav-sub-link" data-target="integrations-slack"><?php esc_html_e('Slack', 'knittnet'); ?></button>
                            <button class="knet-nav-sub-link" data-target="integrations-telegram"><?php esc_html_e('Telegram', 'knittnet'); ?></button>
                        </div>
                    </div>
                </div>

                <!-- Resources Section -->
                <div class="knet-nav-section">
                    <div class="knet-nav-section-title"><?php esc_html_e('Resources', 'knittnet'); ?></div>

                    <div class="knet-nav-item" data-section="tutorials">
                        <button class="knet-nav-link" data-target="tutorials">
                            <span class="knet-nav-link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                            </span>
                            <span class="knet-nav-link-text"><?php esc_html_e('Tutorials', 'knittnet'); ?></span>
                        </button>
                    </div>
                </div>

            </nav>

        </aside>

        <!-- Main Content Area -->
        <main class="knet-content">
            <!-- ========================================
                 CHATBOT - AI MODELS
                 ======================================== -->
            <div id="chatbot-ai-models" class="knet-section active">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('AI Models', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Configure the AI models your chatbot will use for conversations and embeddings.', 'knittnet'); ?></p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-body knittnet-autosave-section">
                        <?php
                        // Chat Model
                        knittnet_render_field_wrapper('model', __('Chat Model', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_model_callback();
                        }, __('Select the AI model your chatbot will use for conversations.', 'knittnet'));

                        // Embedding Model
                        knittnet_render_field_wrapper('embedding_model', __('Embedding Model', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->embedding_model_callback();
                        }, __('Vector embedding model for knowledge base matching. Changing models requires reconfiguring knowledge data.', 'knittnet'));

                        // Enable Streaming
                        knittnet_render_field_wrapper('enable_streaming', __('Enable Streaming', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->enable_streaming_toggle_callback();
                        }, __('Enable real-time streaming responses for supported models (OpenAI, Claude, DeepSeek, Grok).', 'knittnet'));

                        // Enable Web Search (OpenAI & Gemini)
                        knittnet_render_field_wrapper('enable_web_search', __('Enable Web Search', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->enable_web_search_toggle_callback();
                        }, __('Allow the chatbot to search the web for current information (OpenAI and Gemini models).', 'knittnet'));
                        ?>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 CHATBOT - BEHAVIOR
                 ======================================== -->
            <div id="chatbot-behavior" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Behavior', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Control how your AI chatbot responds and processes queries.', 'knittnet'); ?></p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-body knittnet-autosave-section">
                        <?php
                        // AI Instructions
                        knittnet_render_field_wrapper('system_prompt_instructions', __('AI Instructions (Behavior)', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->system_prompt_instructions_callback();
                        }, __('Provide system-level instructions to guide the AI\'s behavior and responses.', 'knittnet'));

                        // Similarity Threshold
                        knittnet_render_field_wrapper('similarity_threshold', __('Similarity Threshold', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_similarity_threshold_callback();
                        }, __('Adjust threshold for content matching. Lower values = more matches, higher values = stricter matching.', 'knittnet'));

                        // RAG Sources Limit
                        knittnet_render_field_wrapper('rag_sources_limit', __('RAG Sources Limit', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_rag_sources_limit_callback();
                        }, __('Number of knowledge base sources (pages/documents) to include in AI context. Higher values provide more context but use more tokens.', 'knittnet'));

                        // RAG Chunks Limit
                        knittnet_render_field_wrapper('rag_chunks_limit', __('RAG Chunks Limit', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_rag_chunks_limit_callback();
                        }, __('Maximum total content chunks sent to the AI across all sources. Higher values provide more context but increase token usage and cost.', 'knittnet'));

                        // Contextual Awareness
                        knittnet_render_field_wrapper('contextual_awareness', __('Contextual Awareness', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_contextual_awareness_callback();
                        }, __('Allow the chatbot to understand and reference the current page content for more relevant responses.', 'knittnet'));

                        // Citation Links
                        knittnet_render_field_wrapper('citation_links', __('Citation Links', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_citation_links_toggle_callback();
                        }, __('Allow the AI to include citation links from your knowledge database in responses. If disabled, ensure your AI Behavior settings do not mention links or the AI may fabricate URLs.', 'knittnet'));

                        // Satisfaction Rating Prompt
                        // The toggle callback inline-renders the 5 customization
                        // fields inside a single sub-options wrapper (plan-29caac).
                        // No separate customization group needed — the wrapper handles
                        // its own server-side display state based on the toggle value.
                        knittnet_render_field_wrapper('satisfaction_rating', __('Satisfaction Rating Prompt', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_satisfaction_rating_toggle_callback();
                        }, __('Show a 👍 / 👎 prompt after a conversation has had a couple of bot replies and the visitor has been idle for a moment. Disable to hide the prompt site-wide.', 'knittnet'));
                        ?>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 CHATBOT - DISPLAY
                 ======================================== -->
            <div id="chatbot-display" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Display Settings', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Customize how and where your chatbot appears on your website.', 'knittnet'); ?></p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                            <?php esc_html_e('Visibility', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body knittnet-autosave-section">
                        <?php
                        // Auto-Display
                        knittnet_render_field_wrapper('auto_display', __('Auto-Display Chatbot', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_append_to_body_callback();
                        }, __('Show chatbot automatically on all pages. Disable to use shortcode [knittnet_chatbot floating="yes"] for floating widget or [knittnet_chatbot floating="no"] for embedded chat.', 'knittnet'));

                        // Open Links in New Tab
                        knittnet_render_field_wrapper('link_target', __('Open Links in New Tab', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_link_target_toggle_callback();
                        }, __('Open links clicked in chat responses in a new browser tab.', 'knittnet'));

                        // Chat Persistence
                        knittnet_render_field_wrapper('chat_persistence', __('Chat Persistence', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_chat_persistence_toggle_callback();
                        }, __('Keep chat history when users navigate or return within 24 hours.', 'knittnet'));

                        // Download Transcript button
                        knittnet_render_field_wrapper('print_button', __('Show Download Transcript Button', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_print_button_toggle_callback();
                        }, __('Show the "Download Transcript" item in the chat window menu.', 'knittnet'));

                        // Start-New-Chat button (plan ac2e81) — lets a visitor reset the
                        // conversation without the owner disabling chat persistence globally.
                        knittnet_render_field_wrapper('reset_chat_enabled', __('Show Start-New-Chat Button', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_reset_chat_toggle_callback();
                        }, __('Show a "Start new chat" item in the chat window menu that clears the current conversation and begins a fresh session.', 'knittnet'));

                        knittnet_render_field_wrapper('reset_chat_label', __('Start-New-Chat Button Label', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_reset_chat_label_callback();
                        }, __('Label for the Start-New-Chat menu item. Leave blank to use "Start new chat".', 'knittnet'));
                        ?>
                    </div>
                </div>

                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            <?php esc_html_e('Text & Labels', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body knittnet-autosave-section">
                        <?php
                        // Top Bar Title
                        knittnet_render_field_wrapper('top_bar_title', __('Top Bar Title', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_top_bar_title_callback();
                        }, __('Title text shown in the chatbot header.', 'knittnet'));

                        // AI Agent Text
                        knittnet_render_field_wrapper('ai_agent_text', __('AI Agent Text', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_ai_agent_text_callback();
                        }, __('Text shown for AI agents in the status indicator.', 'knittnet'));

                        // Intro Message
                        knittnet_render_field_wrapper('intro_message', __('Introductory Message', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_intro_message_callback();
                        }, __('First message shown when chat opens. HTML allowed.', 'knittnet'));

                        // Input Placeholder
                        knittnet_render_field_wrapper('input_copy', __('Input Placeholder', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_input_copy_callback();
                        }, __('Placeholder text in the chat input field.', 'knittnet'));

                        // Max Input Length (characters) — plan a3fae2 part C
                        knittnet_render_field_wrapper('max_input_length', __('Max Input Length (characters)', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_max_input_length_callback();
                        }, __('Cap how many characters a visitor can type or paste into a single message. Set to 0 for unlimited (default).', 'knittnet'));

                        // Chat Teaser
                        knittnet_render_field_wrapper('pre_chat_message', __('Chat Teaser Pop-up', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_pre_chat_message_callback();
                        }, __('Message shown before users start chatting.', 'knittnet'));
                        ?>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 CHATBOT - LEAD CAPTURE
                 ======================================== -->
            <div id="chatbot-lead-capture" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Lead Capture', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Collect user information and manage privacy settings.', 'knittnet'); ?></p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            <?php esc_html_e('Email Capture', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body knittnet-autosave-section">
                        <?php
                        // Require Email
                        knittnet_render_field_wrapper('enable_email_block', __('Require Email to Chat', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->enable_email_block_callback();
                        }, __('Require users to enter their email before chatting. Shows for non-logged-in users.', 'knittnet'));

                        // Email Form Content
                        knittnet_render_field_wrapper('email_blocker_header', __('Email Form Content', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->email_blocker_header_content_callback();
                        }, __('HTML content shown above the email form.', 'knittnet'));

                        // Email Button Text
                        knittnet_render_field_wrapper('email_blocker_button', __('Submit Button Text', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->email_blocker_button_text_callback();
                        }, __('Text for the email form submit button.', 'knittnet'));

                        // Require Name
                        knittnet_render_field_wrapper('enable_name_field', __('Also Require Name', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->enable_name_field_callback();
                        }, __('Also require users to enter their name with email.', 'knittnet'));

                        // Name Placeholder
                        knittnet_render_field_wrapper('name_placeholder', __('Name Field Placeholder', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->name_field_placeholder_callback();
                        }, __('Placeholder text for the name input field.', 'knittnet'));
                        ?>
                    </div>
                </div>

                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <?php esc_html_e('Privacy & Compliance', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body knittnet-autosave-section">
                        <?php
                        // Privacy Notice
                        knittnet_render_field_wrapper('privacy_toggle', __('Privacy Notice', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_privacy_toggle_callback();
                        }, __('Show privacy notice below the chat widget.', 'knittnet'));

                        // Complianz
                        knittnet_render_field_wrapper('complianz_toggle', __('Complianz Integration', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_complianz_toggle_callback();
                        }, __('Apply Complianz consent logic to the chatbot (requires Complianz plugin).', 'knittnet'));
                        ?>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 CHATBOT - QUICK QUESTIONS
                 ======================================== -->
            <div id="chatbot-quick-questions" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Quick Questions', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Pre-defined questions displayed above the chat input to help users get started.', 'knittnet'); ?></p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-body knittnet-autosave-section">
                        <?php
                        // Quick Question 1
                        knittnet_render_field_wrapper('popular_question_1', __('Quick Question 1', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_popular_question_1_callback();
                        });

                        // Quick Question 2
                        knittnet_render_field_wrapper('popular_question_2', __('Quick Question 2', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_popular_question_2_callback();
                        });

                        // Quick Question 3
                        knittnet_render_field_wrapper('popular_question_3', __('Quick Question 3', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_popular_question_3_callback();
                        });

                        // Additional Questions
                        knittnet_render_field_wrapper('additional_questions', __('Additional Questions', 'knittnet'), function() use ($admin_instance) {
                            $admin_instance->knittnet_additional_popular_questions_callback();
                        }, __('Add as many quick questions as you need.', 'knittnet'));
                        ?>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 CHATBOT - RATE LIMITS
                 ======================================== -->
            <div id="chatbot-rate-limits" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Rate Limits', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Set message limits for each user role to manage API usage.', 'knittnet'); ?></p>
                </div>

                <div class="knet-notice knet-notice-info" style="margin-bottom: 24px;">
                    <svg class="knet-notice-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <div>
                        <strong><?php esc_html_e('Placeholders available:', 'knittnet'); ?></strong>
                        <code>{limit}</code>, <code>{timeframe}</code>, <code>{count}</code>, <code>{remaining}</code>
                        <br>
                        <strong><?php esc_html_e('Markdown links supported:', 'knittnet'); ?></strong>
                        <code>[Link text](https://example.com)</code>
                    </div>
                </div>

                <div class="knet-card">
                    <div class="knet-card-body knittnet-autosave-section">
                        <?php
                        // Render rate limits with accordion
                        knittnet_render_rate_limits_accordion($admin_instance);
                        ?>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 API KEYS
                 ======================================== -->
            <div id="api-keys" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('API Keys', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Manage API keys for AI providers and integrations.', 'knittnet'); ?></p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
                            <?php esc_html_e('AI Providers', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body knittnet-autosave-section">
                        <table class="form-table">
                            <?php do_settings_fields('knittnet-api-keys', 'knittnet_api_keys_section'); ?>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 OPTIMIZATION
                 ======================================== -->
            <div id="optimization" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Optimization & Diagnostics', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Optimize performance, debug issues, and manage plugin settings.', 'knittnet'); ?></p>
                </div>

                <!-- Script Loading Card -->
                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                            <?php esc_html_e('Script Loading', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body knittnet-autosave-section">
                        <?php
                        $script_loading_strategy = isset($options['script_loading_strategy']) ? $options['script_loading_strategy'] : 'default';
                        ?>
                        <div class="knet-field">
                            <label class="knet-field-label"><?php esc_html_e('Script Loading Strategy', 'knittnet'); ?></label>
                            <div class="knet-field-control">
                                <select name="knittnet_options[script_loading_strategy]" id="script_loading_strategy" class="knet-select knittnet-autosave-field">
                                    <option value="default" <?php selected($script_loading_strategy, 'default'); ?>><?php esc_html_e('Default (Immediate)', 'knittnet'); ?></option>
                                    <option value="defer" <?php selected($script_loading_strategy, 'defer'); ?>><?php esc_html_e('Deferred', 'knittnet'); ?></option>
                                    <option value="delay_1s" <?php selected($script_loading_strategy, 'delay_1s'); ?>><?php esc_html_e('Delay 1 Second', 'knittnet'); ?></option>
                                    <option value="delay_3s" <?php selected($script_loading_strategy, 'delay_3s'); ?>><?php esc_html_e('Delay 3 Seconds', 'knittnet'); ?></option>
                                    <option value="delay_5s" <?php selected($script_loading_strategy, 'delay_5s'); ?>><?php esc_html_e('Delay 5 Seconds', 'knittnet'); ?></option>
                                    <option value="on_interaction" <?php selected($script_loading_strategy, 'on_interaction'); ?>><?php esc_html_e('On User Interaction', 'knittnet'); ?></option>
                                </select>
                            </div>
                            <p class="knet-field-description"><?php esc_html_e('Control when the chatbot script loads to improve page performance.', 'knittnet'); ?></p>
                        </div>

                        <div class="knet-notice knet-notice-info" style="margin-top: 16px;">
                            <svg class="knet-notice-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <div>
                                <strong><?php esc_html_e('Loading Strategies:', 'knittnet'); ?></strong>
                                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                                    <li><strong><?php esc_html_e('Default:', 'knittnet'); ?></strong> <?php esc_html_e('Script loads immediately in the footer. Best for sites where chat is critical.', 'knittnet'); ?></li>
                                    <li><strong><?php esc_html_e('Deferred:', 'knittnet'); ?></strong> <?php esc_html_e('Script loads after HTML parsing completes. Good balance of performance and availability.', 'knittnet'); ?></li>
                                    <li><strong><?php esc_html_e('Delay:', 'knittnet'); ?></strong> <?php esc_html_e('Script loads after a set delay. Helps improve LCP scores.', 'knittnet'); ?></li>
                                    <li><strong><?php esc_html_e('On User Interaction:', 'knittnet'); ?></strong> <?php esc_html_e('Script loads when user scrolls, moves mouse, or touches screen. Best for Core Web Vitals but chat appears with slight delay.', 'knittnet'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page Cache Compatibility Card -->
                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                            <?php esc_html_e('Page Cache Compatibility', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body">
                        <p class="knet-field-description" style="margin-top: 0;"><?php esc_html_e('KnittNet\'s chat send (and stream send, plus PDF / Word upload) all POST to /wp-admin/admin-ajax.php. Page-cache plugins occasionally cache those responses, which breaks the per-session nonce flow on the first message. KnittNet auto-tells the five most common cache plugins to never cache chat AJAX via their own filter APIs — no setup needed for those. For other cache plugins (or Cloudflare APO), copy the manual exclusion string into your cache plugin\'s exclusion list.', 'knittnet'); ?></p>

                        <?php
                        $knet_cache_rows = array(
                            array('name' => 'WP Rocket',        'state' => 'auto', 'exclusion' => '/wp-admin/admin-ajax\\.php\\?action=knittnet_.*'),
                            array('name' => 'LiteSpeed Cache',  'state' => 'auto', 'exclusion' => '/wp-admin/admin-ajax.php?action=knittnet_*'),
                            array('name' => 'W3 Total Cache',   'state' => 'auto', 'exclusion' => '/wp-admin/admin-ajax.php?action=knittnet_*'),
                            array('name' => 'FlyingPress',      'state' => 'auto', 'exclusion' => '/wp-admin/admin-ajax.php?action=knittnet_*'),
                            array('name' => 'WP Super Cache',   'state' => 'auto', 'exclusion' => 'wp-admin/admin-ajax.php'),
                            array('name' => 'Cloudflare APO',   'state' => 'manual', 'exclusion' => 'Page Rule / Cache Rule: URI Path contains "/wp-admin/admin-ajax.php" → Bypass Cache'),
                        );
                        ?>

                        <div class="knet-cache-compat-grid" style="display: grid; grid-template-columns: 1fr; gap: 12px; margin-top: 16px;">
                            <?php foreach ($knet_cache_rows as $row) :
                                $is_auto = ($row['state'] === 'auto');
                                $pill_class = $is_auto ? 'knet-status-pill-active' : 'knet-status-pill-warning';
                                $pill_label = $is_auto ? __('Auto-handled', 'knittnet') : __('Manual', 'knittnet');
                                ?>
                                <div class="knet-cache-compat-row" style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 14px; background: #f8fafc; border: 1px solid var(--knet-card-border); border-radius: var(--knet-radius-md);">
                                    <div style="display: flex; align-items: center; gap: 10px; min-width: 0; flex: 0 0 auto;">
                                        <span style="font-weight: 500;"><?php echo esc_html($row['name']); ?></span>
                                        <span class="knet-status-pill <?php echo esc_attr($pill_class); ?>">
                                            <span class="knet-status-dot"></span>
                                            <?php echo esc_html($pill_label); ?>
                                        </span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 8px; flex: 1 1 auto; min-width: 0;">
                                        <code style="background: #fff; border: 1px solid var(--knet-card-border); padding: 4px 8px; border-radius: var(--knet-radius-sm); font-size: 12px; color: var(--knet-text-secondary); flex: 1 1 auto; overflow-x: auto; white-space: nowrap; min-width: 0;"><?php echo esc_html($row['exclusion']); ?></code>
                                        <button type="button" class="knet-btn knet-btn-secondary knet-btn-sm" data-knet-copy="<?php echo esc_attr($row['exclusion']); ?>" style="flex: 0 0 auto;">
                                            <?php esc_html_e('Copy', 'knittnet'); ?>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="knet-notice knet-notice-info" style="margin-top: 16px;">
                            <svg class="knet-notice-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span><?php esc_html_e('Auto-handled plugins receive a filter from KnittNet that excludes chat AJAX from their cache. Even so, having the exclusion in your own exclusion list is harmless and acts as a belt-and-suspenders guarantee.', 'knittnet'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Provider Reliability Card -->
                <?php
                $auto_retry_enabled = !isset($options['auto_retry_on_transient_error']) ||
                                      (string) $options['auto_retry_on_transient_error'] !== '0';
                ?>
                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/><polyline points="21 4 21 10 15 10"/></svg>
                            <?php esc_html_e('Provider Reliability', 'knittnet'); ?>
                        </h3>
                        <span class="knet-status-pill <?php echo $auto_retry_enabled ? 'knet-status-pill-active' : 'knet-status-pill-disabled'; ?>">
                            <span class="knet-status-dot"></span>
                            <?php echo $auto_retry_enabled ? esc_html__('Active', 'knittnet') : esc_html__('Disabled', 'knittnet'); ?>
                        </span>
                    </div>
                    <div class="knet-card-body knittnet-autosave-section">
                        <div class="knet-field">
                            <label class="knet-field-label" for="auto_retry_on_transient_error">
                                <?php esc_html_e('Auto-retry on provider overload', 'knittnet'); ?>
                            </label>
                            <div class="knet-field-control">
                                <label class="knet-toggle">
                                    <input type="checkbox"
                                           id="auto_retry_on_transient_error"
                                           name="knittnet_options[auto_retry_on_transient_error]"
                                           class="knittnet-autosave-field"
                                           value="1"
                                           <?php checked($auto_retry_enabled); ?> />
                                    <span class="knet-toggle-slider"></span>
                                </label>
                            </div>
                            <p class="knet-field-description"><?php esc_html_e('When the AI provider returns a transient error (rate-limit / overload / 429 / 503 / Gemini "model overloaded"), KnittNet retries the chat-send up to twice before surfacing the error. Visitors see a brief pause instead of a raw provider error in the bot bubble.', 'knittnet'); ?></p>
                            <p class="knet-field-hint"><?php esc_html_e('Configuration errors (invalid API key, 401/403/404/422) still surface immediately — never retried.', 'knittnet'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Debug Mode Card -->
                <?php
                $debug_mode = isset($options['debug_mode']) ? $options['debug_mode'] : 'off';
                $debug_active = ($debug_mode === 'on');
                ?>
                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                            <?php esc_html_e('Debug Mode', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body">
                        <div class="knet-field">
                            <label class="knet-toggle">
                                <input type="checkbox" class="knet-toggle-input" id="knittnet_debug_mode" name="knittnet_options[debug_mode]" value="on" <?php checked($debug_active); ?>>
                                <span class="knet-toggle-switch"></span>
                                <span class="knet-toggle-label"><?php esc_html_e('Enable Debug Logging', 'knittnet'); ?></span>
                            </label>
                            <p class="knet-field-description"><?php esc_html_e('Tracks settings changes, knowledge base errors, API issues, and embedding failures. Enable only when troubleshooting. Max 100 entries stored.', 'knittnet'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Debug Log Viewer Card -->
                <div class="knet-card">
                    <div class="knet-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                            <?php esc_html_e('Debug Log', 'knittnet'); ?>
                        </h3>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <span id="knittnet-log-count" class="knet-badge" style="display: none;"></span>
                            <button type="button" class="knet-btn knet-btn-secondary knet-btn-sm" id="knittnet-refresh-log">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/></svg>
                                <?php esc_html_e('Refresh', 'knittnet'); ?>
                            </button>
                            <button type="button" class="knet-btn knet-btn-secondary knet-btn-sm" id="knittnet-clear-log">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                <?php esc_html_e('Clear', 'knittnet'); ?>
                            </button>
                        </div>
                    </div>
                    <div class="knet-card-body">
                        <div id="knittnet-debug-log" class="knittnet-debug-log-viewer">
                            <div class="knittnet-debug-log-empty">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <p><?php esc_html_e('No log entries yet. Enable debug mode to start logging.', 'knittnet'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tools Card -->
                <div class="knet-card">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                            <?php esc_html_e('Settings Tools', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body">
                        <div class="knet-tools-grid">
                            <!-- Export Settings -->
                            <div class="knet-tool-item">
                                <div class="knet-tool-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </div>
                                <div class="knet-tool-content">
                                    <h4><?php esc_html_e('Export Settings', 'knittnet'); ?></h4>
                                    <p><?php esc_html_e('Download a JSON file of your current settings for backup or support. API keys are masked for security.', 'knittnet'); ?></p>
                                    <button type="button" class="knet-btn knet-btn-secondary" id="knittnet-export-settings">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        <?php esc_html_e('Export Settings', 'knittnet'); ?>
                                    </button>
                                </div>
                            </div>

                            <!-- Reset All Settings -->
                            <div class="knet-tool-item knet-tool-item-danger">
                                <div class="knet-tool-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </div>
                                <div class="knet-tool-content">
                                    <h4><?php esc_html_e('Reset All Settings', 'knittnet'); ?></h4>
                                    <p><?php esc_html_e('Reset all KnittNet settings to their default values. This action cannot be undone.', 'knittnet'); ?></p>
                                    <button type="button" class="knet-btn knet-btn-danger" id="knittnet-reset-settings">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg>
                                        <?php esc_html_e('Reset All Settings', 'knittnet'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reset Confirmation Modal -->
                <div id="knittnet-reset-modal" class="knet-modal" style="display: none;">
                    <div class="knet-modal-backdrop"></div>
                    <div class="knet-modal-content">
                        <div class="knet-modal-header">
                            <h3><?php esc_html_e('Reset All Settings', 'knittnet'); ?></h3>
                            <button type="button" class="knet-modal-close" id="knittnet-reset-modal-close">&times;</button>
                        </div>
                        <div class="knet-modal-body">
                            <div class="knet-notice knet-notice-warning">
                                <svg class="knet-notice-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                <div>
                                    <strong><?php esc_html_e('Warning: This action cannot be undone!', 'knittnet'); ?></strong>
                                    <p><?php esc_html_e('All your KnittNet settings including API keys, customizations, and configurations will be permanently deleted.', 'knittnet'); ?></p>
                                </div>
                            </div>
                            <div class="knet-field" style="margin-top: 20px;">
                                <label class="knet-field-label"><?php esc_html_e('Type RESET to confirm:', 'knittnet'); ?></label>
                                <input type="text" id="knittnet-reset-confirmation" class="knet-input" placeholder="RESET" autocomplete="off">
                            </div>
                        </div>
                        <div class="knet-modal-footer">
                            <button type="button" class="knet-btn knet-btn-secondary" id="knittnet-reset-cancel"><?php esc_html_e('Cancel', 'knittnet'); ?></button>
                            <button type="button" class="knet-btn knet-btn-danger" id="knittnet-reset-confirm" disabled><?php esc_html_e('Reset All Settings', 'knittnet'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 INTEGRATIONS - TOOLBAR
                 ======================================== -->
            <div id="integrations-toolbar" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Toolbar Settings', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Configure the chat toolbar and document upload features.', 'knittnet'); ?></p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-body knittnet-autosave-section">
                        <table class="form-table">
                            <?php do_settings_fields('knittnet-embed', 'knittnet_pdf_intent_section'); ?>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 INTEGRATIONS - LOOPS
                 ======================================== -->
            <div id="integrations-loops" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Loops Integration', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Configure email capture with Loops for mailing list growth.', 'knittnet'); ?></p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-body knittnet-autosave-section">
                        <table class="form-table">
                            <?php do_settings_fields('knittnet-embed', 'knittnet_loops_section'); ?>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 INTEGRATIONS - BRAVE SEARCH
                 ======================================== -->
            <div id="integrations-brave" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Brave Search', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Configure Brave Search API for web search capabilities.', 'knittnet'); ?></p>
                </div>

                <div class="knet-card">
                    <div class="knet-card-body knittnet-autosave-section">
                        <table class="form-table">
                            <?php do_settings_fields('knittnet-embed', 'knittnet_brave_section'); ?>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 INTEGRATIONS - SLACK
                 ======================================== -->
            <div id="integrations-slack" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Slack', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Connect your chatbot to Slack for live human support.', 'knittnet'); ?></p>
                </div>

                <div class="knet-notice knet-notice-info" style="margin-bottom: 24px;">
                    <svg class="knet-notice-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <div>
                        <?php printf(
                            esc_html__('Visit our %s for setup instructions.', 'knittnet'),
                            '<a href="https://knittnet.ai/documentation/knittnet-core#slack-live-agent-handoff" target="_blank">' . esc_html__('documentation page', 'knittnet') . '</a>'
                        ); ?>
                    </div>
                </div>

                <div class="knet-card">
                    <div class="knet-card-body knittnet-autosave-section">
                        <table class="form-table">
                            <?php do_settings_fields('knittnet-embed', 'knittnet_live_agent_section'); ?>
                        </table>
                    </div>
                </div>

                <div class="knet-card" style="margin-top: 24px;">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <?php esc_html_e('Test Connection', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body">
                        <p class="knet-field-description" style="margin-bottom: 16px;">
                            <?php esc_html_e('Test your Slack bot token to verify it has the required permissions. This will check if the bot can authenticate and create channels.', 'knittnet'); ?>
                        </p>
                        <button type="button" id="knittnet-test-slack-connection" class="knet-btn knet-btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <?php esc_html_e('Test Slack Connection', 'knittnet'); ?>
                        </button>
                        <div id="knittnet-slack-test-result" style="margin-top: 16px; display: none;"></div>
                    </div>
                </div>

                <div class="knet-card" style="margin-top: 24px;">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <?php esc_html_e('Required OAuth Scopes', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body">
                        <p class="knet-field-description" style="margin-bottom: 12px;">
                            <?php esc_html_e('Your Slack app needs these Bot Token Scopes (OAuth & Permissions > Scopes):', 'knittnet'); ?>
                        </p>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px; background: #f8f9fa; padding: 16px; border-radius: 8px; font-family: monospace; font-size: 13px;">
                            <code style="background: #e9ecef; padding: 4px 8px; border-radius: 4px;">channels:manage</code>
                            <code style="background: #e9ecef; padding: 4px 8px; border-radius: 4px;">channels:read</code>
                            <code style="background: #e9ecef; padding: 4px 8px; border-radius: 4px;">chat:write</code>
                            <code style="background: #e9ecef; padding: 4px 8px; border-radius: 4px;">chat:write.public</code>
                            <code style="background: #e9ecef; padding: 4px 8px; border-radius: 4px;">groups:write</code>
                            <code style="background: #e9ecef; padding: 4px 8px; border-radius: 4px;">users:read</code>
                        </div>
                        <p class="knet-field-description" style="margin-top: 12px;">
                            <?php esc_html_e('After adding scopes, reinstall the app to your workspace.', 'knittnet'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 INTEGRATIONS - TELEGRAM
                 ======================================== -->
            <div id="integrations-telegram" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Telegram', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Connect your chatbot to Telegram for live human support.', 'knittnet'); ?></p>
                </div>

                <div class="knet-notice knet-notice-info" style="margin-bottom: 24px;">
                    <svg class="knet-notice-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <div>
                        <?php esc_html_e('Telegram Live Agent requires a Telegram bot and a supergroup with forum topics enabled.', 'knittnet'); ?>
                        <a href="https://knittnet.ai/documentation-bot/" target="_blank" style="margin-left: 5px;"><?php esc_html_e('Chat with our advanced doc bot for assistance setting up.', 'knittnet'); ?></a>
                    </div>
                </div>

                <div class="knet-card">
                    <div class="knet-card-body knittnet-autosave-section">
                        <table class="form-table">
                            <?php do_settings_fields('knittnet-embed', 'knittnet_telegram_section'); ?>
                        </table>
                    </div>
                </div>

                <div class="knet-card" style="margin-top: 24px;">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                            <?php esc_html_e('Webhook Setup', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body">
                        <?php
                        $telegram_bot_token = $options['telegram_bot_token'] ?? '';
                        $telegram_webhook_secret = $options['telegram_webhook_secret'] ?? '';
                        $webhook_url = rest_url('knittnet/v1/telegram-webhook');
                        ?>

                        <p class="knet-field-description" style="margin-bottom: 8px;">
                            <strong><?php esc_html_e('Your Webhook URL:', 'knittnet'); ?></strong>
                        </p>
                        <div class="knet-webhook-url-display" style="margin-bottom: 20px;">
                            <code id="knet-telegram-webhook-url"><?php echo esc_url($webhook_url); ?></code>
                            <button type="button" class="knet-btn knet-btn-secondary knet-btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('knet-telegram-webhook-url').textContent); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy', 2000);">
                                <?php esc_html_e('Copy', 'knittnet'); ?>
                            </button>
                        </div>

                        <?php if (!empty($telegram_bot_token)) : ?>
                            <p class="knet-field-description" style="margin-bottom: 8px;">
                                <strong><?php esc_html_e('Register Webhook URL:', 'knittnet'); ?></strong><br>
                                <?php esc_html_e('Copy and paste this URL into your browser to register the webhook with Telegram:', 'knittnet'); ?>
                            </p>
                            <?php
                            $registration_url = 'https://api.telegram.org/bot' . $telegram_bot_token . '/setWebhook?url=' . urlencode($webhook_url);
                            if (!empty($telegram_webhook_secret)) {
                                $registration_url .= '&secret_token=' . urlencode($telegram_webhook_secret);
                            }
                            ?>
                            <div class="knet-webhook-url-display" style="margin-bottom: 12px;">
                                <code id="knet-telegram-registration-url" style="word-break: break-all; font-size: 12px;"><?php echo esc_url($registration_url); ?></code>
                                <button type="button" class="knet-btn knet-btn-secondary knet-btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('knet-telegram-registration-url').textContent); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy', 2000);">
                                    <?php esc_html_e('Copy', 'knittnet'); ?>
                                </button>
                            </div>
                            <p class="knet-field-description" style="color: #22c55e;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 4px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <?php esc_html_e('You should see {"ok":true,"result":true,"description":"Webhook was set"} after visiting the URL.', 'knittnet'); ?>
                            </p>
                        <?php else : ?>
                            <p class="knet-field-description" style="color: #f59e0b;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 4px;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                                <?php esc_html_e('Enter your Bot Token above and save to generate the registration URL.', 'knittnet'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 TUTORIALS
                 ======================================== -->
            <div id="tutorials" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Video Tutorials', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Learn how to get the most out of KnittNet with our video guides.', 'knittnet'); ?></p>
                </div>

                <?php
                // Setup Guide — reopen the onboarding wizard (plan-347916, moved here from Display tab).
                // Only shows once the user has dismissed/graduated from the Onboarding page.
                if (function_exists('knittnet_onboarding_is_dismissed') && knittnet_onboarding_is_dismissed()):
                    $auto_grad = function_exists('knittnet_onboarding_is_auto_graduated') && knittnet_onboarding_is_auto_graduated();
                ?>
                <div class="knet-card knet-onboarding-unhide-card" style="margin-bottom: 24px;">
                    <div class="knet-card-header">
                        <h3 class="knet-card-title">
                            <svg class="knet-card-title-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 3 3 6 3s6-1 6-3v-5"/></svg>
                            <?php esc_html_e('Setup Guide', 'knittnet'); ?>
                        </h3>
                    </div>
                    <div class="knet-card-body">
                        <p style="margin:0 0 12px;color:var(--knet-text-secondary);font-size:13px;line-height:1.5;">
                            <?php if ($auto_grad): ?>
                                <?php esc_html_e('You finished the setup guide. Reopen it any time to revisit the steps or change a setup choice.', 'knittnet'); ?>
                            <?php else: ?>
                                <?php esc_html_e('You hid the setup guide from the KnittNet menu. Reopen it any time to revisit the steps.', 'knittnet'); ?>
                            <?php endif; ?>
                        </p>
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin:0;">
                            <?php wp_nonce_field('knittnet_unhide_onboarding'); ?>
                            <input type="hidden" name="action" value="knittnet_unhide_onboarding" />
                            <button type="submit" class="knet-btn knet-btn-secondary knet-btn-sm">
                                <?php esc_html_e('Reopen setup guide', 'knittnet'); ?>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

                <div class="knet-card" style="margin-bottom: 24px;">
                    <div class="knet-card-body">
                        <div style="display: flex; align-items: flex-start; gap: 16px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--knet-warning); flex-shrink: 0;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            <div>
                                <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600;"><?php esc_html_e('Need Help?', 'knittnet'); ?></h3>
                                <p style="margin: 0 0 12px 0; color: var(--knet-text-secondary);"><?php esc_html_e('If you\'re having trouble or believe something is not working as expected, please submit a support ticket.', 'knittnet'); ?></p>
                                <a href="https://wordpress.org/support/plugin/knittnet-basic/" target="_blank" class="knet-btn knet-btn-secondary knet-btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2v5Z"/><path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"/></svg>
                                    <?php esc_html_e('Submit Support Ticket', 'knittnet'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="knet-tutorials-grid">
                    <?php
                    $tutorials = array(
                        array(
                            'title' => __('Quick Setup with KnittNet', 'knittnet'),
                            'description' => __('Learn how to quickly setup and understand how your chatbot works.', 'knittnet'),
                            'url' => 'https://www.youtube.com/watch?v=3BqoiyWaQiM&t'
                        ),
                        array(
                            'title' => __('AI Content Generation', 'knittnet'),
                            'description' => __('Generate full blog posts and landing pages with AI—from prompt to publish in minutes.', 'knittnet'),
                            'url' => 'https://www.youtube.com/watch?v=n8TeEeHpxs4'
                        ),
                        array(
                            'title' => __('Chat With Your GSC Data', 'knittnet'),
                            'description' => __('Find content gaps, quick win keywords, and SEO opportunities by chatting with your Google Search Console data using the Admin Assistant add-on.', 'knittnet'),
                            'url' => 'https://www.youtube.com/watch?v=ytgMou_7SZA&t=2s'
                        ),
                        array(
                            'title' => __('AI Theme Generator', 'knittnet'),
                            'description' => __('Learn how to instantly restyle your chatbot using plain English prompts with real-time previews.', 'knittnet'),
                            'url' => 'https://www.youtube.com/watch?v=rSQDW2qbtRU&t'
                        ),
                        array(
                            'title' => __('KnittNet Forms', 'knittnet'),
                            'description' => __('Create and manage smart forms that automatically trigger during chat conversations.', 'knittnet'),
                            'url' => 'https://www.youtube.com/watch?v=3MrWy5dRalA'
                        ),
                        array(
                            'title' => __('Admin Assistant Add-on', 'knittnet'),
                            'description' => __('Bring a ChatGPT-like experience directly inside your WordPress dashboard.', 'knittnet'),
                            'url' => 'https://youtu.be/AdEA1k-UCFM'
                        ),
                        array(
                            'title' => __('Chat Themes', 'knittnet'),
                            'description' => __('Customize appearance with real-time previews to match your brand.', 'knittnet'),
                            'url' => 'https://youtu.be/MfbB9mZi6ag'
                        ),
                        array(
                            'title' => __('WooCommerce Integration', 'knittnet'),
                            'description' => __('Provide product recommendations and shopping assistance to customers.', 'knittnet'),
                            'url' => 'https://www.youtube.com/watch?v=WsqAppHRGdA'
                        ),
                        array(
                            'title' => __('Knowledge Base Setup', 'knittnet'),
                            'description' => __('Set up your knowledge base using PDFs, sitemaps, and manual entries.', 'knittnet'),
                            'url' => 'https://www.youtube.com/watch?v=8Ztjs66-VTo'
                        ),
                        array(
                            'title' => __('Document Chat', 'knittnet'),
                            'description' => __('Chat with PDF and Word documents for enhanced document analysis.', 'knittnet'),
                            'url' => 'https://www.youtube.com/watch?v=j_c45WWCTG0'
                        ),
                        array(
                            'title' => __('Perplexity Integration', 'knittnet'),
                            'description' => __('Enable real-time web search capabilities for your chatbot.', 'knittnet'),
                            'url' => 'https://youtu.be/wpKkbt24-bo'
                        ),
                        array(
                            'title' => __('Brave Search Intent', 'knittnet'),
                            'description' => __('Leverage Brave Search for improved query understanding.', 'knittnet'),
                            'url' => 'https://www.youtube.com/watch?v=7vDL5H7vToc'
                        ),
                        array(
                            'title' => __('Loops Email Capture', 'knittnet'),
                            'description' => __('Set up email capture with Loops to grow your mailing list.', 'knittnet'),
                            'url' => 'https://www.youtube.com/watch?v=CNgm5TYDyTc'
                        ),
                        array(
                            'title' => __('AI Agent Testing', 'knittnet'),
                            'description' => __('Evaluate and improve your chatbot\'s performance and accuracy.', 'knittnet'),
                            'url' => 'https://www.youtube.com/watch?v=A0jowbpyX54'
                        ),
                    );

                    foreach ($tutorials as $tutorial): ?>
                    <div class="knet-tutorial-card">
                        <div class="knet-tutorial-content">
                            <h3 class="knet-tutorial-title"><?php echo esc_html($tutorial['title']); ?></h3>
                            <p class="knet-tutorial-description"><?php echo esc_html($tutorial['description']); ?></p>
                            <a href="<?php echo esc_url($tutorial['url']); ?>" target="_blank" rel="noopener" class="knet-tutorial-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                <?php esc_html_e('Watch Tutorial', 'knittnet'); ?>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- =====================================================
                 TESTING SECTION
                 ===================================================== -->
            <div id="testing" class="knet-section">
                <div class="knet-content-header">
                    <h1 class="knet-content-title"><?php esc_html_e('Testing', 'knittnet'); ?></h1>
                    <p class="knet-content-subtitle"><?php esc_html_e('Test your chatbot and inspect debug data in real time.', 'knittnet'); ?></p>
                </div>

                <div class="knet-testing-layout">
                    <!-- Left Column: Debug Panel -->
                    <div class="knet-testing-debug-panel">
                        <!-- Quick Actions -->
                        <div class="knet-card">
                            <div class="knet-card-body">
                                <h3 class="knet-testing-section-title"><?php esc_html_e('Quick Actions', 'knittnet'); ?></h3>
                                <div class="knet-testing-actions-row">
                                    <button type="button" class="knet-testing-btn knet-testing-btn-danger" id="knet-testing-clear-session">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        <?php esc_html_e('Clear Chat Session', 'knittnet'); ?>
                                    </button>
                                    <button type="button" class="knet-testing-btn knet-testing-btn-secondary" id="knet-testing-clear-debug">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20"/></svg>
                                        <?php esc_html_e('Clear Debug Log', 'knittnet'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Last Query Analysis -->
                        <div class="knet-card">
                            <div class="knet-card-body">
                                <h3 class="knet-testing-section-title"><?php esc_html_e('Last Query Analysis', 'knittnet'); ?></h3>

                                <div class="knet-testing-field">
                                    <label><?php esc_html_e('Similarity Threshold', 'knittnet'); ?></label>
                                    <span id="knet-testing-threshold"><?php esc_html_e('Loading...', 'knittnet'); ?></span>
                                </div>

                                <div class="knet-testing-field">
                                    <label><?php esc_html_e('User Query', 'knittnet'); ?></label>
                                    <div id="knet-testing-last-query" class="knet-testing-query-display"><?php esc_html_e('Waiting for next query...', 'knittnet'); ?></div>
                                </div>

                                <div class="knet-testing-field">
                                    <label><?php esc_html_e('Approved URLs for Citations', 'knittnet'); ?></label>
                                    <div id="knet-testing-approved-urls" class="knet-testing-results">
                                        <div class="knet-testing-no-data"><?php esc_html_e('No URL data yet', 'knittnet'); ?></div>
                                    </div>
                                </div>

                                <div class="knet-testing-field">
                                    <label><?php esc_html_e('Document Matches', 'knittnet'); ?></label>
                                    <div id="knet-testing-similarity-scores" class="knet-testing-results">
                                        <div class="knet-testing-no-data"><?php esc_html_e('No query data yet', 'knittnet'); ?></div>
                                    </div>
                                </div>

                                <div class="knet-testing-field">
                                    <label><?php esc_html_e('Actions Triggered', 'knittnet'); ?></label>
                                    <div id="knet-testing-action-scores" class="knet-testing-results">
                                        <div class="knet-testing-no-data"><?php esc_html_e('No action data yet', 'knittnet'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Information -->
                        <div class="knet-card">
                            <div class="knet-card-body">
                                <h3 class="knet-testing-section-title"><?php esc_html_e('System Information', 'knittnet'); ?></h3>

                                <div class="knet-testing-field">
                                    <label><?php esc_html_e('System Prompt', 'knittnet'); ?></label>
                                    <div id="knet-testing-system-prompt" class="knet-testing-system-prompt"><?php esc_html_e('Loading...', 'knittnet'); ?></div>
                                </div>

                                <div class="knet-testing-field">
                                    <label><?php esc_html_e('Knowledge Base', 'knittnet'); ?></label>
                                    <span id="knet-testing-kb-status"><?php esc_html_e('Checking...', 'knittnet'); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Debug Log -->
                        <div class="knet-card">
                            <div class="knet-card-body">
                                <h3 class="knet-testing-section-title"><?php esc_html_e('Debug Log', 'knittnet'); ?></h3>
                                <div id="knet-testing-debug-console" class="knet-testing-debug-console">
                                    <div class="knet-testing-debug-entry"><?php esc_html_e('Debug panel ready - send a message to begin...', 'knittnet'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Chatbot Replica -->
                    <div class="knet-testing-chatbot-column">
                        <div class="knet-testing-chatbot-scope">
                            <?php
                            // Render the chatbot inline for testing
                            echo do_shortcode('[knittnet_chatbot floating="no" bot_id="testing"]');
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sidebar navigation
        const navLinks = document.querySelectorAll('.knet-nav-link, .knet-nav-sub-link');
        const sections = document.querySelectorAll('.knet-section');
        const navItems = document.querySelectorAll('.knet-nav-item');

        function showSection(target) {
            // Show target section
            sections.forEach(section => {
                section.classList.remove('active');
                if (section.id === target) {
                    section.classList.add('active');
                }
            });

            // Scroll content area to top
            const contentArea = document.querySelector('.knet-content');
            if (contentArea) {
                contentArea.scrollTop = 0;
            }
        }

        function setActiveNav(clickedLink) {
            // Remove active from all links
            navLinks.forEach(l => l.classList.remove('active'));

            // Add active to clicked link
            clickedLink.classList.add('active');

            // If clicking a sub-link, also highlight parent
            if (clickedLink.classList.contains('knet-nav-sub-link')) {
                const parent = clickedLink.closest('.knet-nav-item');
                if (parent) {
                    parent.querySelector('.knet-nav-link').classList.add('active');
                }
            }
        }

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const target = this.dataset.target;
                const parentItem = this.closest('.knet-nav-item');
                const hasSubmenu = parentItem && parentItem.querySelector('.knet-nav-sub');

                // If this is a parent nav link WITH children (expandable menu)
                if (this.classList.contains('knet-nav-link') && hasSubmenu) {
                    const wasExpanded = parentItem.classList.contains('expanded');

                    // Collapse all other expandable items
                    navItems.forEach(item => {
                        if (item !== parentItem && item.querySelector('.knet-nav-sub')) {
                            item.classList.remove('expanded');
                        }
                    });

                    // Toggle this item
                    parentItem.classList.toggle('expanded');

                    // If expanding, show the first sub-item's content and mark it active
                    if (!wasExpanded) {
                        const firstSubLink = parentItem.querySelector('.knet-nav-sub-link');
                        if (firstSubLink) {
                            const firstTarget = firstSubLink.dataset.target;
                            showSection(firstTarget);
                            setActiveNav(firstSubLink);
                            // Also mark parent as active
                            this.classList.add('active');
                            history.replaceState(null, null, '#' + firstTarget);
                        }
                    }
                }
                // If this is a nav link WITHOUT children (like API Keys, Tutorials)
                else if (this.classList.contains('knet-nav-link') && !hasSubmenu && target) {
                    // Collapse all expandable items
                    navItems.forEach(item => {
                        if (item.querySelector('.knet-nav-sub')) {
                            item.classList.remove('expanded');
                        }
                    });

                    // Show the section
                    showSection(target);
                    setActiveNav(this);
                    history.replaceState(null, null, '#' + target);
                }
                // If this is a sub-link
                else if (this.classList.contains('knet-nav-sub-link') && target) {
                    // Ensure parent stays expanded
                    if (parentItem) {
                        parentItem.classList.add('expanded');
                    }
                    showSection(target);
                    setActiveNav(this);
                    history.replaceState(null, null, '#' + target);
                }
            });
        });

        // Handle initial deep-link (?tab=<slug> takes precedence over #hash so the
        // Onboarding setup-step CTAs land on the right sub-tab). Whitelist of valid
        // section ids must stay in sync with the .knet-section[id] values above.
        const validTabs = [
            'chatbot-ai-models', 'chatbot-behavior', 'chatbot-display',
            'chatbot-lead-capture', 'chatbot-quick-questions', 'chatbot-rate-limits',
            'api-keys', 'optimization', 'testing',
            'integrations-toolbar', 'integrations-loops', 'integrations-brave',
            'integrations-slack', 'integrations-telegram',
            'tutorials'
        ];
        function activateTab(target) {
            if (!target) return false;
            if (validTabs.indexOf(target) === -1) return false;
            const targetLink = document.querySelector('[data-target="' + target + '"]');
            if (!targetLink) return false;
            const parentItem = targetLink.closest('.knet-nav-item');
            if (parentItem && targetLink.classList.contains('knet-nav-sub-link')) {
                parentItem.classList.add('expanded');
            }
            showSection(target);
            setActiveNav(targetLink);
            return true;
        }
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        let tabActivated = false;
        if (tabParam) {
            tabActivated = activateTab(tabParam);
            // Invalid ?tab=… silently falls through to default (#hash or AI Models).
        }
        if (!tabActivated) {
            const hash = window.location.hash.slice(1);
            if (hash) {
                activateTab(hash);
            }
        }

        // Rate limit accordion
        const rateLimitHeaders = document.querySelectorAll('.knet-rate-limit-header');
        rateLimitHeaders.forEach(header => {
            header.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const item = this.closest('.knet-rate-limit-item');
                item.classList.toggle('expanded');
            });
        });

        // Rate-limit "Custom…" toggle: show/hide the integer input below each
        // limit <select> based on whether __custom__ is the active option.
        document.querySelectorAll('.knet-rate-limit-limit-select').forEach(sel => {
            const targetId = sel.getAttribute('data-knet-custom-target');
            if (!targetId) return;
            const field = document.querySelector('[data-knet-custom-for="' + targetId + '"]');
            if (!field) return;
            const sync = () => {
                if (sel.value === '__custom__') {
                    field.hidden = false;
                } else {
                    field.hidden = true;
                }
            };
            sel.addEventListener('change', sync);
            sync();
        });

        // =====================================================
        // Mobile Menu Functionality
        // =====================================================
        const mobileMenuBtn = document.querySelector('.knet-mobile-menu-btn');
        const mobileMenuClose = document.querySelector('.knet-mobile-menu-close');
        const mobileMenu = document.querySelector('.knet-mobile-menu');
        const mobileOverlay = document.querySelector('.knet-mobile-overlay');
        const mobileNavLinks = document.querySelectorAll('.knet-mobile-nav-link, .knet-mobile-nav-sub-link');

        function openMobileMenu() {
            mobileMenu.classList.add('open');
            mobileOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileMenu.classList.remove('open');
            mobileOverlay.classList.remove('open');
            document.body.style.overflow = '';
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', openMobileMenu);
        }

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', closeMobileMenu);
        }

        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', closeMobileMenu);
        }

        // Mobile navigation links
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const target = this.dataset.target;
                const parentId = this.dataset.parent;

                // If this is an expandable parent link
                if (parentId && !target) {
                    const subNav = document.querySelector('.knet-mobile-nav-sub[data-parent="' + parentId + '"]');
                    if (subNav) {
                        const isExpanded = subNav.classList.contains('expanded');
                        // Collapse all sub navs
                        document.querySelectorAll('.knet-mobile-nav-sub').forEach(nav => {
                            nav.classList.remove('expanded');
                        });
                        document.querySelectorAll('.knet-mobile-nav-link').forEach(l => {
                            l.classList.remove('expanded');
                        });
                        // Toggle this one
                        if (!isExpanded) {
                            subNav.classList.add('expanded');
                            this.classList.add('expanded');
                        }
                    }
                }
                // If this is a direct link or sub-link with a target
                else if (target) {
                    // Update mobile nav active state
                    document.querySelectorAll('.knet-mobile-nav-link, .knet-mobile-nav-sub-link').forEach(l => {
                        l.classList.remove('active');
                    });
                    this.classList.add('active');

                    // Also update desktop sidebar nav
                    const desktopLink = document.querySelector('.knet-sidebar [data-target="' + target + '"]');
                    if (desktopLink) {
                        setActiveNav(desktopLink);
                        const parentItem = desktopLink.closest('.knet-nav-item');
                        if (parentItem && desktopLink.classList.contains('knet-nav-sub-link')) {
                            parentItem.classList.add('expanded');
                        }
                    }

                    // Show section and close menu
                    showSection(target);
                    history.replaceState(null, null, '#' + target);
                    closeMobileMenu();
                }
            });
        });

        // Close mobile menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('open')) {
                closeMobileMenu();
            }
        });
    });
    </script>
    <?php
}

/**
 * Helper function to render a field with consistent wrapper
 */
function knittnet_render_field_wrapper($id, $label, $callback, $description = '') {
    ?>
    <div class="knet-field">
        <label class="knet-field-label"><?php echo esc_html($label); ?></label>
        <div class="knet-field-control">
            <?php $callback(); ?>
        </div>
        <?php if ($description): ?>
        <p class="knet-field-description"><?php echo esc_html($description); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Render rate limits as collapsible accordion
 */
function knittnet_render_rate_limits_accordion($admin_instance) {
    $all_options = get_option('knittnet_options', []);
    $rate_limits = array('1', '3', '5', '10', '15', '20', '50', '100', 'unlimited');
    $timeframes = array(
        'hourly' => __('Per Hour', 'knittnet'),
        'daily' => __('Per Day', 'knittnet'),
        'weekly' => __('Per Week', 'knittnet'),
        'monthly' => __('Per Month', 'knittnet')
    );

    $roles = wp_roles()->get_names();
    $roles['logged_out'] = __('Logged Out Users', 'knittnet');

    // Whole-chatbot global cap (sits above per-role; defaults to unlimited so
    // existing installs are unchanged). Stored under knittnet_options['rate_limits_global'].
    $global_cfg = isset($all_options['rate_limits_global']) && is_array($all_options['rate_limits_global'])
        ? $all_options['rate_limits_global']
        : array();
    $global_limit_raw     = isset($global_cfg['limit']) ? (string) $global_cfg['limit'] : 'unlimited';
    $global_timeframe     = isset($global_cfg['timeframe']) ? (string) $global_cfg['timeframe'] : 'daily';
    $global_is_unlimited  = ($global_limit_raw === '' || $global_limit_raw === 'unlimited');
    $global_is_preset     = !$global_is_unlimited && in_array($global_limit_raw, $rate_limits, true);
    $global_select_value  = $global_is_unlimited ? 'unlimited' : ($global_is_preset ? $global_limit_raw : '__custom__');
    $global_custom_value  = (!$global_is_unlimited && !$global_is_preset && ctype_digit($global_limit_raw))
        ? $global_limit_raw
        : '';

    echo '<div class="knet-rate-limits">';

    // --- Global cap card -------------------------------------------------
    ?>
    <div class="knet-rate-limit-item knet-rate-limit-global knittnet-autosave-section">
        <div class="knet-rate-limit-header">
            <span class="knet-rate-limit-role"><?php esc_html_e('Total chatbot message limit', 'knittnet'); ?></span>
            <div class="knet-rate-limit-summary">
                <span class="knet-rate-limit-badge knet-rate-limit-global-badge">
                    <?php
                    if ($global_is_unlimited) {
                        esc_html_e('Unlimited', 'knittnet');
                    } else {
                        $tf_label = isset($timeframes[$global_timeframe]) ? $timeframes[$global_timeframe] : $global_timeframe;
                        echo esc_html(($global_custom_value !== '' ? $global_custom_value : $global_limit_raw) . ' / ' . $tf_label);
                    }
                    ?>
                </span>
                <svg class="knet-rate-limit-arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
        </div>
        <div class="knet-rate-limit-body">
            <p class="knet-field-description" style="margin: 0 0 12px;">
                <?php esc_html_e('A single ceiling across all users and all roles, per timeframe. Independent of the per-role limits below. When both a per-role limit and this global cap are configured, whichever is hit first stops the conversation. Default is Unlimited — existing installs are unchanged.', 'knittnet'); ?>
            </p>
            <div class="knet-rate-limit-controls">
                <div class="knet-field">
                    <label class="knet-field-label" for="rate_limits_global_limit"><?php esc_html_e('Limit', 'knittnet'); ?></label>
                    <select id="rate_limits_global_limit"
                            name="knittnet_options[rate_limits_global][limit]"
                            class="knet-select knittnet-autosave-field knet-rate-limit-limit-select"
                            data-knet-custom-target="rate_limits_global_limit_custom">
                        <?php foreach ($rate_limits as $limit): ?>
                            <option value="<?php echo esc_attr($limit); ?>" <?php selected($global_select_value, $limit); ?>>
                                <?php echo esc_html($limit === 'unlimited' ? __('Unlimited', 'knittnet') : $limit); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="__custom__" <?php selected($global_select_value, '__custom__'); ?>><?php esc_html_e('Custom…', 'knittnet'); ?></option>
                    </select>
                </div>
                <div class="knet-field knet-rate-limit-custom-field" data-knet-custom-for="rate_limits_global_limit_custom" <?php echo $global_select_value === '__custom__' ? '' : 'hidden'; ?>>
                    <label class="knet-field-label" for="rate_limits_global_limit_custom"><?php esc_html_e('Custom limit', 'knittnet'); ?></label>
                    <input type="number"
                           min="1"
                           step="1"
                           id="rate_limits_global_limit_custom"
                           name="knittnet_options[rate_limits_global][limit_custom]"
                           class="knet-input knittnet-autosave-field knet-rate-limit-custom-input"
                           placeholder="e.g. 250"
                           value="<?php echo esc_attr($global_custom_value); ?>" />
                </div>
                <div class="knet-field">
                    <label class="knet-field-label" for="rate_limits_global_timeframe"><?php esc_html_e('Timeframe', 'knittnet'); ?></label>
                    <select id="rate_limits_global_timeframe"
                            name="knittnet_options[rate_limits_global][timeframe]"
                            class="knet-select knittnet-autosave-field">
                        <?php foreach ($timeframes as $value => $label): ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected($global_timeframe, $value); ?>><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php
            // --- Current usage readout (read-only; mirrors the integrator's
            // window-reset math at READ time — never writes the counter here).
            // v1 surfaces the DEFAULT bot's pool. TODO: per-bot selector if asked.
            $global_usage_limit_int = (!$global_is_unlimited && ctype_digit($global_limit_raw))
                ? (int) $global_limit_raw
                : 0;
            $usage_raw   = get_option('knittnet_chat_limit_default_global', array('count' => 0, 'timestamp' => time()));
            $usage_count = isset($usage_raw['count']) ? (int) $usage_raw['count'] : 0;
            $usage_ts    = isset($usage_raw['timestamp']) ? (int) $usage_raw['timestamp'] : time();
            $usage_windows = array('hourly' => 3600, 'daily' => 86400, 'weekly' => 604800, 'monthly' => 2592000);
            $usage_window  = isset($usage_windows[$global_timeframe]) ? $usage_windows[$global_timeframe] : 86400;
            $usage_now = time();
            if (($usage_now - $usage_ts) >= $usage_window) {
                // Window elapsed since the stored timestamp → effective count is 0.
                $usage_count = 0;
                $usage_reset_at = $usage_now + $usage_window;
            } else {
                $usage_reset_at = $usage_ts + $usage_window;
            }
            $usage_left = max(0, $global_usage_limit_int - $usage_count);
            $usage_pct  = ($global_usage_limit_int > 0)
                ? min(100, (int) round(($usage_count / $global_usage_limit_int) * 100))
                : 0;
            $usage_reset_nonce = wp_create_nonce('knittnet_reset_global_usage');
            ?>
            <div class="knet-rate-limit-usage"
                 id="knet-global-usage"
                 data-bot-id="default"
                 data-reset-nonce="<?php echo esc_attr($usage_reset_nonce); ?>"
                 <?php echo $global_is_unlimited ? 'hidden' : ''; ?>>
                <div class="knet-rate-limit-usage-head">
                    <span class="knet-field-label" style="margin:0;"><?php esc_html_e('Current usage', 'knittnet'); ?></span>
                    <button type="button" class="knet-btn knet-btn-secondary knet-btn-sm" id="knet-global-usage-reset"><?php esc_html_e('Reset counter', 'knittnet'); ?></button>
                </div>
                <div class="knet-progress-bar">
                    <div class="knet-progress-bar-fill" id="knet-global-usage-fill" style="width: <?php echo esc_attr($usage_pct); ?>%;"></div>
                </div>
                <p class="knet-progress-label" id="knet-global-usage-text" style="margin:0;">
                    <?php
                    printf(
                        /* translators: 1: used count, 2: limit, 3: remaining, 4: human-readable time until reset */
                        esc_html__('%1$s of %2$s used · %3$s left · resets in %4$s', 'knittnet'),
                        esc_html(number_format_i18n($usage_count)),
                        esc_html(number_format_i18n($global_usage_limit_int)),
                        esc_html(number_format_i18n($usage_left)),
                        esc_html(human_time_diff($usage_now, $usage_reset_at))
                    );
                    ?>
                </p>
            </div>
            <p class="knet-field-description knet-rate-limit-usage-unlimited"
               id="knet-global-usage-unlimited"
               style="margin: 12px 0 0;"
               <?php echo $global_is_unlimited ? '' : 'hidden'; ?>>
                <?php esc_html_e('Unlimited — no cap. Usage tracking applies only when a numeric limit is set.', 'knittnet'); ?>
            </p>
        </div>
    </div>
    <?php
    // ---------------------------------------------------------------------

    foreach ($roles as $role_id => $role_name) {
        $default_limit = ($role_id === 'logged_out') ? '10' : '100';
        $default_timeframe = 'daily';
        $default_message = __('Rate limit exceeded. Please try again later.', 'knittnet');

        $selected_limit = isset($all_options['rate_limits'][$role_id]['limit'])
            ? (string) $all_options['rate_limits'][$role_id]['limit']
            : $default_limit;

        $selected_timeframe = isset($all_options['rate_limits'][$role_id]['timeframe'])
            ? $all_options['rate_limits'][$role_id]['timeframe']
            : $default_timeframe;

        $custom_message = isset($all_options['rate_limits'][$role_id]['message'])
            ? $all_options['rate_limits'][$role_id]['message']
            : $default_message;

        $timeframe_label = isset($timeframes[$selected_timeframe]) ? $timeframes[$selected_timeframe] : $selected_timeframe;

        // Custom-value handling: if the stored limit is not in the preset list
        // and not 'unlimited', it's a custom integer. The select shows __custom__
        // and the number input below carries the actual value.
        $is_preset_limit = in_array($selected_limit, $rate_limits, true);
        $role_select_value = $is_preset_limit ? $selected_limit : '__custom__';
        $role_custom_value = (!$is_preset_limit && ctype_digit($selected_limit)) ? $selected_limit : '';
        $custom_field_id   = 'rate_limits_' . $role_id . '_limit_custom';
        ?>
        <div class="knet-rate-limit-item">
            <div class="knet-rate-limit-header">
                <span class="knet-rate-limit-role"><?php echo esc_html($role_name); ?></span>
                <div class="knet-rate-limit-summary">
                    <span class="knet-rate-limit-badge"><?php echo esc_html($selected_limit . ' / ' . $timeframe_label); ?></span>
                    <svg class="knet-rate-limit-arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
            </div>
            <div class="knet-rate-limit-body knittnet-autosave-section">
                <div class="knet-rate-limit-controls">
                    <div class="knet-field">
                        <label class="knet-field-label" for="rate_limits_<?php echo esc_attr($role_id); ?>_limit"><?php esc_html_e('Limit', 'knittnet'); ?></label>
                        <select id="rate_limits_<?php echo esc_attr($role_id); ?>_limit"
                                name="knittnet_options[rate_limits][<?php echo esc_attr($role_id); ?>][limit]"
                                class="knet-select knittnet-autosave-field knet-rate-limit-limit-select"
                                data-knet-custom-target="<?php echo esc_attr($custom_field_id); ?>">
                            <?php foreach ($rate_limits as $limit): ?>
                            <option value="<?php echo esc_attr($limit); ?>" <?php selected($role_select_value, $limit); ?>>
                                <?php echo esc_html($limit === 'unlimited' ? __('Unlimited', 'knittnet') : $limit); ?>
                            </option>
                            <?php endforeach; ?>
                            <option value="__custom__" <?php selected($role_select_value, '__custom__'); ?>><?php esc_html_e('Custom…', 'knittnet'); ?></option>
                        </select>
                    </div>
                    <div class="knet-field knet-rate-limit-custom-field" data-knet-custom-for="<?php echo esc_attr($custom_field_id); ?>" <?php echo $role_select_value === '__custom__' ? '' : 'hidden'; ?>>
                        <label class="knet-field-label" for="<?php echo esc_attr($custom_field_id); ?>"><?php esc_html_e('Custom limit', 'knittnet'); ?></label>
                        <input type="number"
                               min="1"
                               step="1"
                               id="<?php echo esc_attr($custom_field_id); ?>"
                               name="knittnet_options[rate_limits][<?php echo esc_attr($role_id); ?>][limit_custom]"
                               class="knet-input knittnet-autosave-field knet-rate-limit-custom-input"
                               placeholder="e.g. 250"
                               value="<?php echo esc_attr($role_custom_value); ?>" />
                    </div>
                    <div class="knet-field">
                        <label class="knet-field-label" for="rate_limits_<?php echo esc_attr($role_id); ?>_timeframe"><?php esc_html_e('Timeframe', 'knittnet'); ?></label>
                        <select id="rate_limits_<?php echo esc_attr($role_id); ?>_timeframe"
                                name="knittnet_options[rate_limits][<?php echo esc_attr($role_id); ?>][timeframe]"
                                class="knet-select knittnet-autosave-field">
                            <?php foreach ($timeframes as $value => $label): ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected($selected_timeframe, $value); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="knet-field">
                    <label class="knet-field-label" for="rate_limits_<?php echo esc_attr($role_id); ?>_message"><?php esc_html_e('Custom Message', 'knittnet'); ?></label>
                    <textarea id="rate_limits_<?php echo esc_attr($role_id); ?>_message"
                              name="knittnet_options[rate_limits][<?php echo esc_attr($role_id); ?>][message]"
                              class="knet-textarea knittnet-autosave-field"
                              rows="2"
                              placeholder="<?php esc_attr_e('Message shown when rate limit is reached', 'knittnet'); ?>"><?php echo esc_textarea($custom_message); ?></textarea>
                </div>
            </div>
        </div>
        <?php
    }

    echo '</div>';
}
