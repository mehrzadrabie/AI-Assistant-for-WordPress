=== KnittNet AI Assistant - AI Chatbot & Content Generation for WordPress ===
Contributors: knittnet
Author: [KnittNet](https://knittnet.ai)
Tags: ai chatbot, openrouter, woocommerce, customer support, content generation
Requires at least: 5.0
Tested up to: 7.0
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

KnittNet AI Assistant is an AI chatbot and content-generation plugin for WordPress, powered entirely by OpenRouter. Train it on your website content, generate blog posts and landing pages with AI, and give visitors a WooCommerce-aware assistant with a RAG knowledge base and live-agent handoff.

== Description ==

**KnittNet AI Assistant** connects your WordPress site to any model available through [OpenRouter](https://openrouter.ai/) — GPT, Claude, Gemini, Grok, DeepSeek, Llama, Mistral, and more — through a single API key and a single, unified provider. Train your chatbot on your website content, WooCommerce products, PDFs, and manual entries using RAG (Retrieval-Augmented Generation), and use the built-in AI Content Generator to produce full blog posts and landing pages complete with images, SEO metadata, and inline AI editing.

Every feature in this plugin is unlocked — there is no license key, no activation step, and no separate paid tier. Configure your OpenRouter API key under **KnittNet → AI Configuration** and you're ready to go.

## Why KnittNet AI Assistant?

✅ **One Provider, Every Model** — OpenRouter gives you a single API key and a single settings screen for 100+ models across every major lab
✅ **Train on Your Website Data** — RAG technology learns from sitemaps, PDFs, URLs, or manual input for ultra-relevant responses
✅ **Live Agent Handoff via Slack** — seamlessly escalate from AI to human support when visitors need personal assistance
✅ **Real-Time Debug Panel** — see exactly what your chatbot retrieves and triggers with the admin testing interface
✅ **Boost Sales with WooCommerce** — product cards, cart management, and AI-powered shopping assistance
✅ **AI Content Generator** — create full blog posts and landing pages with AI-generated images, SEO metadata, and real-time preview editing
✅ **Everything Included** — no add-ons to buy, no license to activate; every capability ships enabled

## Core Features

🟢 **Every OpenRouter Model** – access GPT, Claude, Gemini, Grok, DeepSeek, Llama, Mistral, and more from a single OpenRouter API key
🟢 **Lead Management Dashboard** – a dedicated Leads tab inside Transcripts shows every captured email and name, deduplicated by lead, with filters, bulk delete, CSV export, and a one-click jump to each lead's latest conversation
🟢 **Chat Transcripts Dashboard** – review all conversations, analyze chat history, track engagement, and see exactly which knowledge sources the AI used for each response
🟢 **WooCommerce Product Training** – train the chatbot on your entire product catalog, including descriptions, pricing, SKUs, and categories, for intelligent shopping assistance
🟢 **Live Agent Handoff** – escalate conversations from AI to human support via Slack when visitors need personal assistance
🟢 **ACF Field Control** – choose exactly which Advanced Custom Fields are included in your knowledge base
🟢 **Advanced Action Recognition** – Trigger Phrases use vector embeddings to understand intent and fire lead capture, appointments, redirects, and custom actions automatically
🟢 **AI Tools (Function Calling)** – let the chatbot decide on its own when to run an enabled action from natural language, with no phrases to set up (needs a tool-capable model)
🟢 **Document Processing** – let visitors upload and chat with PDFs and Word documents directly on your frontend
🟢 **Pinecone Vector Storage** – optional fast knowledge retrieval for large datasets and enterprise-scale deployments
🟢 **Web Search Integration** – ground responses in real-time information beyond your knowledge base
🟢 **AI Content Generator** – create full blog posts and landing pages from a prompt, with AI images, SEO metadata, real-time preview, and inline AI editing via chat
🟢 **Streaming Responses** – real-time response streaming for the fastest possible chat experience
🟢 **REST API** – bearer-token endpoints to read transcripts, push knowledge, and bulk-delete sessions — wire the assistant into n8n, Zapier, analytics dashboards, GDPR workflows, or your own agents

## Powered Entirely by OpenRouter

KnittNet AI Assistant talks to a single provider — [OpenRouter](https://openrouter.ai/) — for every model it uses. Configure one API key under **KnittNet → AI Configuration** (default model, temperature, max tokens, system prompt, timeout, and streaming), then pick any of the 100+ models OpenRouter routes to, from any lab, without touching a second settings screen or managing multiple keys.

## Lead Management Dashboard — Turn Chat Traffic Into a Real Lead List

Every email and name your chatbot captures lands in one clean, sortable dashboard — so you can see your leads as a list instead of hunting through individual transcripts.

**What You Get:**

- **One Row Per Lead** — emails captured across multiple sessions are automatically deduplicated, so each lead appears once with a conversation count and a "last seen" timestamp
- **Top Pages Capturing Leads** — instantly see which pages on your site are driving the most lead captures; click any page to filter the list to leads from that page only
- **Powerful Filtering** — search by email or name, filter by date range (24h / 7d / 30d / 90d / all-time), or filter by status (leads with a conversation vs. orphan leads who submitted the form but never chatted)
- **Orphan Lead Tracking** — visitors who drop their email in the pre-chat form but never send a message are surfaced separately so you never lose a captured contact
- **One-Click View Conversation** — jump from any lead straight into their latest full conversation in the transcripts viewer
- **Bulk Delete + CSV Export** — select multiple leads to delete or export in one action
- **Zero Setup, Zero Migration** — uses your existing chat data, no new database tables, no configuration

## AI Content Generator — Create Blog Posts & Landing Pages with AI

KnittNet AI Assistant includes a built-in AI Content Generator that lets you create full blog posts and landing pages directly from your WordPress dashboard.

**How It Works:**

1. **Describe Your Content** — enter a topic, title, or detailed prompt and choose between a blog post or landing page layout
2. **AI Generates Everything** — the AI produces fully styled HTML with responsive CSS, ready-to-publish content, and optional AI-generated images
3. **Real-Time Preview** — see your content exactly as it will appear on your site, with desktop and mobile viewport switching
4. **Edit with AI Chat** — use the built-in AI chat panel to request changes like "make the heading bigger" or "change the background to blue"
5. **Publish Instantly** — content is saved as a native WordPress post or page with clean HTML, SEO metadata, and responsive design that works with any theme

**Key Content Generator Features:**

- AI-generated images placed directly into your content with automatic media library uploads
- SEO title and meta description generation for search engine optimization
- Built-in SEO analysis with real-time scoring and one-click AI auto-optimization
- Fullwidth and standard layout options with automatic theme compatibility
- Content history to revisit and manage previously generated posts
- Works with popular page builders and themes including Elementor, Bricks, Divi, Astra, Kadence, and more

## 📱 Mobile-Friendly & Fully Customizable

The chatbot widget adapts seamlessly to all devices — desktop, tablet, or mobile. Customize colors, greetings, and placement to match your brand.

== Developer Hooks & Filters ==

KnittNet AI Assistant provides WordPress filter hooks so developers can extend and customize chatbot behavior without modifying core plugin files.

= knittnet_before_process_post =

Runs during knowledge base indexing and lets you modify a post's data before it is processed. Use this to include custom field data (e.g. product specifications or ACF fields), strip internal content you don't want the chatbot to learn, or transform posts based on bot ID. Receives the WP_Post object and the bot ID.

= knittnet_system_instructions =

Dynamically modify the system prompt before every AI response. Use this to inject live data such as business hours, inventory status, or user-specific context into the prompt at runtime. Receives the instructions text, bot ID, and session ID. Registered shortcodes in the system prompt field are automatically expanded before being sent to the AI.

== Use of Third-Party Services ==

This plugin connects to a single AI service provider to generate responses:

**Service Provider:**
- [OpenRouter](https://openrouter.ai/) - [Terms](https://openrouter.ai/terms) | [Privacy](https://openrouter.ai/privacy)

OpenRouter itself routes requests to the underlying model provider you select (OpenAI, Anthropic, Google, Meta, Mistral, and others); please review OpenRouter's documentation for how it handles provider routing and ensure compliance with applicable terms and data privacy laws.

== Installation ==

1. Upload the `knittnet-ai-assistant` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to **KnittNet → AI Configuration** and enter your OpenRouter API key and preferred model.

== Frequently Asked Questions ==

= What AI models does KnittNet AI Assistant support? =

Any model available through OpenRouter — GPT, Claude, Gemini, Grok, DeepSeek, Llama, Mistral, and more — from a single OpenRouter API key.

= How do I get an API key? =

Create an account at [openrouter.ai](https://openrouter.ai/) and generate an API key. Paste it into **KnittNet → AI Configuration**.

= Can I train the AI chatbot on my website content? =

Yes. The plugin supports unlimited knowledge base training using RAG (Retrieval-Augmented Generation) technology. Import content from WordPress pages, posts, WooCommerce products, custom post types, PDFs, Word documents, text files, sitemaps, and manual Q&A entries.

= What is RAG technology and how does it work? =

RAG (Retrieval-Augmented Generation) retrieves relevant information from your knowledge base before generating a response. When a visitor asks a question, the plugin searches your trained content using vector embeddings, finds the most relevant matches, and provides that context to the AI model.

= Does it work with WooCommerce? =

Yes. The chatbot can be trained on your product catalog including titles, descriptions, pricing, SKUs, categories, and custom fields, with optional auto-sync when products change.

= Can I customize the chatbot appearance? =

Yes — colors, chatbot icon, AI avatar, bubble size, light/dark/system themes, positioning, custom CSS, greeting messages, placeholder text, and widget layout are all configurable.

= How do I add the chatbot to my site? =

Use the shortcode `[knittnet_chatbot floating="yes"]` for a floating widget or `[knittnet_chatbot floating="no"]` for an embedded chat, or enable "Append to Body" in settings to show it on every page automatically.

= Does it support streaming responses? =

Yes, for any streaming-capable model available through OpenRouter.

= What is the Chat Transcripts feature? =

A dashboard for reviewing conversation history, tracking which knowledge sources the AI used for each response, and analyzing engagement.

= Is Pinecone required? =

No. The plugin includes free, unlimited local vector storage in your WordPress database. Pinecone is optional and recommended only for enterprise-scale knowledge bases.

= Does it support multiple languages, including RTL? =

Yes, including right-to-left languages such as Arabic and Hebrew.

= How much do AI API calls cost? =

Costs depend on the model you choose through OpenRouter; OpenRouter bills per token according to the underlying model's rate. You can set rate limits for guests and logged-in users to control usage.

= Is it GDPR compliant? =

Conversation data is stored locally on your WordPress site. Configure automatic data retention policies, export chat transcripts, or delete conversations as needed.

= Can I restrict chatbot access to certain users? =

Yes — restrict the chatbot to logged-in users, specific roles, or keep it public, and restrict specific knowledge base content by role.

= Does it support live chat handoff? =

Yes, via Slack. When the AI can't help or a visitor requests human support, the conversation is transferred to your team.

= What are Actions and how do they work? =

Actions are capabilities your chatbot can perform — lead capture, appointment booking, support tickets, page redirects, image generation, web search, or custom JavaScript — fired either by **Trigger Phrases** (deterministic vector-matched phrases) or **AI Tools** (function calling, where the model decides when to run a tool). Manage both from Actions in your settings.

= Can visitors upload documents to chat? =

Yes. Enable document uploads to let visitors chat with PDFs and Word documents.

= Does it support image generation? =

Yes, through image-capable models available via OpenRouter.

= How does knowledge base auto-sync work? =

Enable auto-sync for posts, pages, WooCommerce products, or custom post types, and the knowledge base updates automatically when you publish or update content.

= Can I use Advanced Custom Fields (ACF)? =

Yes. ACF field data is automatically included when importing content; use ACF Field Settings to control which fields are included or excluded.

= What embedding models are supported? =

Multiple embedding models are supported for vector search. Choose the one that best fits your accuracy and cost requirements in Settings.

= Is anything gated behind a license or paid tier? =

No. Every feature in this plugin is unlocked by default — there is no license key, activation step, or paid add-on.

== Screenshots ==

1. **Chat Transcripts Insights** - Review conversation insights: engagement rate, peak activity, and user distribution.
2. **Knowledge & Sitemap Submission** - Submit custom content to enhance the chatbot's responses.
3. **Action Page** - Set up Trigger Phrases and AI Tools (function calling).
4. **AI Configuration** - Configure your OpenRouter API key, model, and generation settings.
5. **Debug Panel** - Debug content-matching scores, citation URLs, and triggered actions.

== Changelog ==

= 1.0.0 =
* Initial release of KnittNet AI Assistant: a rebuilt, fully unlocked fork with a unified AI provider architecture powered exclusively by OpenRouter, no license or activation step, and a refreshed admin UI.

== License & Warranty ==

This plugin is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

== Credits ==

This plugin incorporates the following third-party library:

- **Smalot PDF Parser**: used for parsing and extracting text from PDF files. Developed by Smalot and distributed under the MIT License. See the [Smalot PDF Parser GitHub repository](https://github.com/smalot/pdfparser).

This plugin was developed by [KnittNet](https://knittnet.ai/).
