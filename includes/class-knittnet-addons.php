<?php
/**
 * The admin-specific functionality for the Add Ons page.
 *
 * @package    KnittNet
 * @subpackage KnittNet/admin
 */
class KnittNet_Addons {
    /**
     * Store add-on configuration data
     *
     * @var array
     */
    private $addons_config;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        $this->addons_config = array(

            'knittnet-mcp' => array(
                'title' => __('KnittNet MCP Server', 'knittnet'),
                'sidebar_title' => __('MCP Server', 'knittnet'),
                'description' => __('Turn your KnittNet install into a Model Context Protocol server so Claude, ChatGPT, and Claude Code can list transcripts, push knowledge, and inspect bots directly — no per-message fees, no middleman API.', 'knittnet'),
                'key_benefits' => array(
                    __('JSON-RPC 2.0 MCP endpoint with OAuth + bearer auth', 'knittnet'),
                    __('One-click connect snippets for Claude Code, ChatGPT, mcp-inspector', 'knittnet'),
                    __('WooCommerce-aware tools: products, orders, customers', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#7873f5',
                'url' => 'https://knittnet.ai/add-ons/knittnet-mcp/',
                'download_url' => 'https://knittnet.ai/add-ons/knittnet-mcp/',
                'plugin_file' => 'knittnet-mcp/knittnet-mcp.php',
                'config_page' => 'knittnet-mcp',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
                        'title' => __('Open Protocol', 'knittnet'),
                        'desc' => __('Standards-based MCP over Streamable HTTP', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
                        'title' => __('OAuth + Bearer', 'knittnet'),
                        'desc' => __('Reuses your KnittNet REST token', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>',
                        'title' => __('Chatbot Ops Tools', 'knittnet'),
                        'desc' => __('Transcripts, knowledge, bots, search', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>',
                        'title' => __('WooCommerce Aware', 'knittnet'),
                        'desc' => __('Products, orders, customer lookups', 'knittnet'),
                    ),
                ),
            ),

            'knittnet-advanced-content' => array(
                'title' => __('KnittNet Advanced Content Editor', 'knittnet'),
                'sidebar_title' => __('Content Gen.', 'knittnet'),
                'description' => __('Supercharge your AI content generation workflow. Add advanced editing tools, image management, SEO meta controls, and internal linking to the KnittNet Content Generator—turning simple prompts into publish-ready posts and landing pages.', 'knittnet'),
                'key_benefits' => array(
                    __('AI-powered image regeneration & uploads', 'knittnet'),
                    __('SEO meta title, description & excerpt editor', 'knittnet'),
                    __('Automatic internal linking between posts', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#6366f1',
                'url' => 'https://knittnet.ai/add-ons/knittnet-advanced-content/',
                'download_url' => 'https://knittnet.ai/add-ons/knittnet-advanced-content/',
                'plugin_file' => 'knittnet-advanced-content/knittnet-advanced-content.php',
                'config_page' => 'knittnet-content',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
                        'title' => __('Image Management', 'knittnet'),
                        'desc' => __('Regenerate, upload, and swap AI images', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
                        'title' => __('SEO Meta Editor', 'knittnet'),
                        'desc' => __('Title, description & excerpt controls', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>',
                        'title' => __('Internal Linking', 'knittnet'),
                        'desc' => __('Auto-link to related posts & pages', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>',
                        'title' => __('Chat-Based Editing', 'knittnet'),
                        'desc' => __('Refine content through conversation', 'knittnet'),
                    ),
                ),
            ),

            'knittnet-theme' => array(
                'title' => __('KnittNet Chat Themes', 'knittnet'),
                'description' => __('Make your chatbot uniquely yours. Generate beautiful themes instantly using simple descriptions or customize manually with precise color controls—zero coding required. Perfect for matching your brand identity.', 'knittnet'),
                'key_benefits' => array(
                    __('AI-powered theme generator', 'knittnet'),
                    __('Live preview customizer', 'knittnet'),
                    __('One-click theme application', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#fa73e6',
                'url' => 'https://knittnet.ai/add-ons/knittnet-theme/',
                'plugin_file' => 'knittnet-theme/knittnet-theme.php',
                'config_page' => 'knittnet-theme-settings',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r="2.5"/><path d="M17.5 10.5l4-4"/><circle cx="6" cy="12" r="2"/><path d="M2 14l2-2"/><circle cx="10.5" cy="17.5" r="2.5"/><path d="M14 21l-1-3"/></svg>',
                        'title' => __('AI Theme Generator', 'knittnet'),
                        'desc' => __('Describe your style, AI creates it', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>',
                        'title' => __('Live Preview', 'knittnet'),
                        'desc' => __('See changes in real-time', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4"/><path d="m16.2 7.8 2.9-2.9"/><path d="M18 12h4"/><path d="m16.2 16.2 2.9 2.9"/><path d="M12 18v4"/><path d="m4.9 19.1 2.9-2.9"/><path d="M2 12h4"/><path d="m4.9 4.9 2.9 2.9"/></svg>',
                        'title' => __('Color Controls', 'knittnet'),
                        'desc' => __('Fine-tune every color precisely', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
                        'title' => __('One-Click Apply', 'knittnet'),
                        'desc' => __('Apply themes instantly', 'knittnet'),
                    ),
                ),
            ),

            'knittnet-admin-assistant' => array(
                'title' => __('KnittNet Admin Assistant', 'knittnet'),
                'description' => __('Your AI powerhouse inside WordPress. Chat with multiple AI models, generate images, research the web, and boost productivity—all without leaving your dashboard.', 'knittnet'),
                'key_benefits' => array(
                    __('ChatGPT-like admin interface', 'knittnet'),
                    __('Generate images & research web', 'knittnet'),
                    __('Searchable chat history', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#fa73e6',
                'url' => 'https://knittnet.ai/add-ons/knittnet-admin-chat/',
                'plugin_file' => 'knittnet-admin-chat/knittnet-admin-chat.php',
                'config_page' => 'knittnet-admin-chat',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
                        'title' => __('Admin Chat UI', 'knittnet'),
                        'desc' => __('ChatGPT-like dashboard interface', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
                        'title' => __('Image Generation', 'knittnet'),
                        'desc' => __('Create images with AI prompts', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
                        'title' => __('Web Research', 'knittnet'),
                        'desc' => __('Search the web from your dashboard', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 9 8 12 2 12"/></svg>',
                        'title' => __('Chat History', 'knittnet'),
                        'desc' => __('Searchable conversation archive', 'knittnet'),
                    ),
                ),
            ),

            'knittnet-veo' => array(
                'title' => __('KnittNet Video Generation', 'knittnet'),
                'description' => __('Generate stunning AI videos using Google Veo 2 and Veo 3 models directly from your WordPress dashboard. Create high-quality videos from text prompts with audio, multiple aspect ratios, and optimized generation speeds.', 'knittnet'),
                'key_benefits' => array(
                    __('Multiple Veo models (Veo 2, 3, 3 Fast)', 'knittnet'),
                    __('8-second videos with native audio', 'knittnet'),
                    __('Media library integration', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#ff6b35',
                'url' => 'https://knittnet.ai/add-ons/knittnet-veo/',
                'plugin_file' => 'knittnet-veo/knittnet-veo.php',
                'config_page' => 'knittnet-veo',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>',
                        'title' => __('AI Video Creation', 'knittnet'),
                        'desc' => __('Generate videos from text prompts', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
                        'title' => __('Native Audio', 'knittnet'),
                        'desc' => __('Videos with built-in audio tracks', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7m0-18H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7m0-18v18"/></svg>',
                        'title' => __('Aspect Ratios', 'knittnet'),
                        'desc' => __('Multiple size options available', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>',
                        'title' => __('Media Library', 'knittnet'),
                        'desc' => __('Save directly to WordPress media', 'knittnet'),
                    ),
                ),
            ),

            'knittnet-image-analysis' => array(
                'title' => __('KnittNet Image Analysis', 'knittnet'),
                'description' => __('Enhance your chatbot with AI-powered image analysis. Users can upload images for intelligent descriptions, OCR text extraction, and get answers about visual content using OpenAI Vision and Grok APIs.', 'knittnet'),
                'key_benefits' => array(
                    __('Multiple AI vision models', 'knittnet'),
                    __('Drag & drop image uploads', 'knittnet'),
                    __('Custom analysis prompts', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#fa73e6',
                'url' => 'https://knittnet.ai/add-ons/knittnet-vision/',
                'plugin_file' => 'knittnet-vision/knittnet-vision.php',
                'config_page' => 'knittnet-vision',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',
                        'title' => __('Vision AI Models', 'knittnet'),
                        'desc' => __('OpenAI Vision & Grok analysis', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>',
                        'title' => __('Drag & Drop', 'knittnet'),
                        'desc' => __('Easy image upload in chat', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
                        'title' => __('OCR Extraction', 'knittnet'),
                        'desc' => __('Extract text from images', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>',
                        'title' => __('Custom Prompts', 'knittnet'),
                        'desc' => __('Tailor analysis instructions', 'knittnet'),
                    ),
                ),
            ),

            'knittnet-forms' => array(
                'title' => __('KnittNet Forms', 'knittnet'),
                'description' => __('Convert conversations into data collection. Create smart forms that trigger during chats, collect user information, and turn casual visitors into qualified leads.', 'knittnet'),
                'key_benefits' => array(
                    __('No-code form builder', 'knittnet'),
                    __('Intent-triggered activation', 'knittnet'),
                    __('Export lead data easily', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#fa73e6',
                'url' => 'https://knittnet.ai/add-ons/knittnet-forms/',
                'plugin_file' => 'knittnet-forms/knittnet-forms.php',
                'config_page' => 'knittnet-forms',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="12" y2="17"/></svg>',
                        'title' => __('Form Builder', 'knittnet'),
                        'desc' => __('No-code drag & drop builder', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
                        'title' => __('Smart Triggers', 'knittnet'),
                        'desc' => __('Show forms based on intent', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                        'title' => __('Lead Capture', 'knittnet'),
                        'desc' => __('Turn visitors into contacts', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
                        'title' => __('Data Export', 'knittnet'),
                        'desc' => __('Export leads to CSV easily', 'knittnet'),
                    ),
                ),
            ),
            
            'knittnet-multi-bot' => array(
                'title' => __('KnittNet Multi-Bot Manager', 'knittnet'),
                'description' => __('Create unlimited specialized chatbots for every purpose. Deploy dedicated bots with separate knowledge bases, custom personalities, and page-specific assignments—perfect for support, sales, and department-specific assistance.', 'knittnet'),
                'key_benefits' => array(
                    __('Unlimited specialized chatbots', 'knittnet'),
                    __('Separate Pinecone knowledge bases', 'knittnet'),
                    __('Page-specific bot assignments', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#8b5cf6',
                'url' => 'https://knittnet.ai/add-ons/knittnet-multi-bot/',
                'plugin_file' => 'knittnet-multi-bot/knittnet-multi-bot.php',
                'config_page' => 'knittnet-multi-bot',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
                        'title' => __('Unlimited Bots', 'knittnet'),
                        'desc' => __('Create specialized chatbots', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>',
                        'title' => __('Separate Knowledge', 'knittnet'),
                        'desc' => __('Unique knowledge per bot', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
                        'title' => __('Page Assignments', 'knittnet'),
                        'desc' => __('Assign bots to specific pages', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
                        'title' => __('Custom Personas', 'knittnet'),
                        'desc' => __('Unique personality per bot', 'knittnet'),
                    ),
                ),
            ),

            'knittnet-trigger' => array(
                'title' => __('KnittNet Trigger', 'knittnet'),
                'description' => __('Turn any element into a conversation starter. Add chat trigger buttons and links throughout your website that open KnittNet and send predefined messages—perfect for CTAs, support links, and guided experiences.', 'knittnet'),
                'key_benefits' => array(
                    __('Simple data attribute triggers', 'knittnet'),
                    __('Multi-Bot targeting support', 'knittnet'),
                    __('Built-in button styles & animations', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#3ac9d1',
                'url' => 'https://knittnet.ai/add-ons/knittnet-trigger/',
                'plugin_file' => 'knittnet-trigger/knittnet-trigger.php',
                'config_page' => 'knittnet-triggers',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>',
                        'title' => __('Element Triggers', 'knittnet'),
                        'desc' => __('Turn any element into a trigger', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
                        'title' => __('Preset Messages', 'knittnet'),
                        'desc' => __('Auto-send predefined prompts', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
                        'title' => __('Multi-Bot Targeting', 'knittnet'),
                        'desc' => __('Route to specific bots', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>',
                        'title' => __('Button Styles', 'knittnet'),
                        'desc' => __('Built-in styles & animations', 'knittnet'),
                    ),
                ),
            ),

            'knittnet-anywhere' => array(
                'title' => __('KnittNet Anywhere', 'knittnet'),
                'sidebar_title' => __('KnittNet Anywhere', 'knittnet'),
                'description' => __('Deploy your AI chatbot on any website with a single script tag. Works on static HTML, Shopify, Squarespace, Wix, and custom web apps — no WordPress required on the target site. Full feature parity with Shadow DOM style isolation.', 'knittnet'),
                'key_benefits' => array(
                    __('One script tag — works on any website', 'knittnet'),
                    __('Shadow DOM prevents CSS conflicts', 'knittnet'),
                    __('Domain-validated security keys', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#10b981',
                'url' => 'https://knittnet.ai/add-ons/knittnet-embed/',
                'download_url' => 'https://knittnet.ai/add-ons/knittnet-embed/',
                'plugin_file' => 'knittnet-embed/knittnet-embed.php',
                'config_page' => 'knittnet-embed',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
                        'title' => __('Universal Embed', 'knittnet'),
                        'desc' => __('One script tag on any website', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
                        'title' => __('Style Isolation', 'knittnet'),
                        'desc' => __('Shadow DOM prevents CSS conflicts', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
                        'title' => __('Domain Security', 'knittnet'),
                        'desc' => __('Validated keys per domain', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
                        'title' => __('Zero Dependencies', 'knittnet'),
                        'desc' => __('Vanilla JS, no impact on Core Web Vitals', 'knittnet'),
                    ),
                ),
            ),

            'knittnet-woo' => array(
                'title' => __('KnittNet WooCommerce', 'knittnet'),
                'description' => __('Boost sales with AI-powered shopping assistance. Help customers find products, manage carts, and complete purchases—all through natural conversation.', 'knittnet'),
                'key_benefits' => array(
                    __('Smart product recommendations', 'knittnet'),
                    __('Cart & checkout assistance', 'knittnet'),
                    __('Order history access', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#fa73e6',
                'url' => 'https://knittnet.ai/add-ons/knittnet-woo/',
                'plugin_file' => 'knittnet-woo/knittnet-woo.php',
                'config_page' => 'knittnet-woo',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>',
                        'title' => __('Cart Assistant', 'knittnet'),
                        'desc' => __('Manage cart through conversation', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>',
                        'title' => __('Product Search', 'knittnet'),
                        'desc' => __('Smart product recommendations', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
                        'title' => __('Checkout Help', 'knittnet'),
                        'desc' => __('Guide customers to purchase', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
                        'title' => __('Order History', 'knittnet'),
                        'desc' => __('Look up past orders in chat', 'knittnet'),
                    ),
                ),
            ),

            'knittnet-perplexity' => array(
                'title' => __('KnittNet Perplexity', 'knittnet'),
                'description' => __('Give your chatbot real-time knowledge. Add powerful web search capabilities so your bot can answer questions about current events and time-sensitive information.', 'knittnet'),
                'key_benefits' => array(
                    __('Real-time web search', 'knittnet'),
                    __('Intent-triggered research', 'knittnet'),
                    __('Up-to-date information', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#fa73e6',
                'url' => 'https://knittnet.ai/add-ons/knittnet-perplexity/',
                'plugin_file' => 'knittnet-perplexity/knittnet-perplexity.php',
                'config_page' => 'knittnet-perplexity',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
                        'title' => __('Web Search', 'knittnet'),
                        'desc' => __('Real-time internet searching', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
                        'title' => __('Intent Triggers', 'knittnet'),
                        'desc' => __('Auto-search when needed', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
                        'title' => __('Live Data', 'knittnet'),
                        'desc' => __('Up-to-date information always', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
                        'title' => __('Seamless Chat', 'knittnet'),
                        'desc' => __('Results blend into conversation', 'knittnet'),
                    ),
                ),
            ),

            'knittnet-moderation' => array(
                'title' => __('KnittNet Moderation', 'knittnet'),
                'description' => __('Keep your chat clean and professional. Block unwanted users, filter inappropriate content, and ensure your chatbot represents your brand properly.', 'knittnet'),
                'key_benefits' => array(
                    __('IP & email-based blocking', 'knittnet'),
                    __('Content filtering', 'knittnet'),
                    __('Spam protection', 'knittnet')
                ),
                'license' => 'KnittNet PRO',
                'accent' => '#fa73e6',
                'url' => 'https://knittnet.ai/add-ons/knittnet-moderation/',
                'plugin_file' => 'knittnet-moderation/knittnet-moderation.php',
                'config_page' => 'knittnet-moderation',
                'hero_features' => array(
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
                        'title' => __('Content Filter', 'knittnet'),
                        'desc' => __('Block inappropriate content', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>',
                        'title' => __('User Blocking', 'knittnet'),
                        'desc' => __('IP & email-based bans', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>',
                        'title' => __('Spam Protection', 'knittnet'),
                        'desc' => __('Detect and prevent spam', 'knittnet'),
                    ),
                    array(
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>',
                        'title' => __('Brand Safety', 'knittnet'),
                        'desc' => __('Keep chat professional', 'knittnet'),
                    ),
                ),
            ),

        );
    }

    /**
     * Get the addons configuration array
     *
     * @return array The addons configuration
     */
    public function get_addons_config() {
        return $this->addons_config;
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'knittnet-addons',
            plugin_dir_url(__FILE__) . '../css/admin-add-ons.css',
            array(),
            KNITTNET_VERSION,
            'all'
        );
    }

    /**
     * Check if an addon is installed and active
     *
     * @param string $plugin_file The plugin's main file path
     * @return array Status information
     */
    private function get_addon_status($plugin_file) {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $all_plugins = get_plugins();
        $active_plugins = get_option('active_plugins', array());

        // Find the addon by iterating through configs
        $config_page = '';
        foreach ($this->addons_config as $slug => $addon) {
            if ($addon['plugin_file'] === $plugin_file) {
                $config_page = $addon['config_page'];
                break;
            }
        }

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

    /**
     * Render the Add Ons page content.
     */
    public function render_page() {
        $this->enqueue_styles();
        // Remove the sorting logic and just use the original order
        $sorted_addons = $this->addons_config;
        
        ?>
        <div class="wrap knittnet-addons-wrapper">
                <div class="knittnet-addons-hero">
                    <h1 class="knittnet-main-title">
                        <span class="knittnet-gradient-text">Power Up</span> Your Chatbot
                    </h1>
                    <p class="knittnet-hero-subtitle">
                        <?php esc_html_e('Extend KnittNet with these powerful extensions and unlock advanced features.', 'knittnet'); ?>
                    </p>
                </div>
                
                <div class="knittnet-addons-section">
                    <div class="knittnet-addons-grid">
                        <?php foreach ($sorted_addons as $slug => $addon): ?>
                            <?php $this->render_addon_card($slug, $addon); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="knittnet-cta-section">
                    <h2><?php esc_html_e('Ready to take your chatbot to the next level?', 'knittnet'); ?></h2>
                    <p><?php esc_html_e('Get KnittNet PRO today and access all premium extensions at one low price.', 'knittnet'); ?></p>
                    <a href="https://knittnet.ai/" class="knittnet-cta-button" target="_blank"><?php esc_html_e('Learn More', 'knittnet'); ?></a>
                </div>
            </div>
            <?php
        }

    /**
     * Render individual add-on card
     *
     * @param string $slug The addon slug
     * @param array  $addon The addon configuration array
     */
    private function render_addon_card($slug, $addon) {
        $status_info = $this->get_addon_status($addon['plugin_file']);
        $button_url = $status_info['status'] === 'not-installed' ? $addon['url'] : $status_info['action_url'];
        $button_target = $status_info['status'] === 'not-installed' ? '_blank' : '_self';
        
        // Check if addon is deprecated
        $is_deprecated = isset($addon['status']) && $addon['status'] === 'deprecated';
        ?>
        <div class="knittnet-addon-card <?php echo $is_deprecated ? 'deprecated' : ''; ?>" style="--card-accent: <?php echo esc_attr($addon['accent']); ?>">
            <div class="knittnet-addon-badge">
                <?php if ($is_deprecated): ?>
                    <?php esc_html_e('Deprecated', 'knittnet'); ?>
                <?php else: ?>
                    <?php echo esc_html(ucfirst($addon['license'])); ?>
                <?php endif; ?>
            </div>
            <div class="knittnet-addon-content">
                <h3 class="knittnet-addon-title"><?php echo esc_html($addon['title']); ?></h3>
                <p class="knittnet-addon-description"><?php echo esc_html($addon['description']); ?></p>
                
                <?php if (!empty($addon['key_benefits'])): ?>
                <div class="knittnet-benefits-list">
                    <?php foreach ($addon['key_benefits'] as $benefit): ?>
                        <div class="knittnet-benefit-item">
                            <span class="knittnet-benefit-icon">✓</span>
                            <?php echo esc_html($benefit); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <div class="knittnet-addon-footer">
                    <?php if ($is_deprecated): ?>
                        <div class="knittnet-status-indicator deprecated">
                            <?php esc_html_e('Now Built-in', 'knittnet'); ?>
                        </div>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=knittnet-prompts')); ?>"
                           class="knittnet-action-button deprecated"
                           target="_self">
                            <?php esc_html_e('Go to Knowledge Tab', 'knittnet'); ?>
                        </a>
                    <?php else: ?>
                        <div class="knittnet-status-indicator <?php echo esc_attr($status_info['status']); ?>">
                            <?php echo esc_html(ucfirst(str_replace('-', ' ', $status_info['status']))); ?>
                        </div>
                        <a href="<?php echo esc_url($button_url); ?>"
                           class="knittnet-action-button"
                           target="<?php echo esc_attr($button_target); ?>"
                           data-action="<?php echo esc_attr($status_info['status']); ?>">
                            <?php echo esc_html($status_info['action_text']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

}