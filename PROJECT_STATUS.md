# PROJECT STATUS — KnittNet AI Assistant (WordPress plugin)

> Handoff document generated from a full audit of this repository on **2026-11-10**.
> Audited ref: `claude/plugin-white-label-rebrand-om9ohv` (default & only branch) — 10 commits, all 2026-07-06. Repo is **public**.

## 1. Snapshot

| Item | Value |
|---|---|
| What it is | White-label rebrand of the open-source **MxChat v3.2.12** WordPress plugin into **KnittNet AI Assistant** (knittnet.ai): AI chatbot with RAG knowledge base, WooCommerce assistant, Slack live-agent handoff, AI content generator (posts/landing pages with images & SEO) |
| Key change | All AI traffic consolidated to **OpenRouter only**; all licence/activation gating removed (every feature unlocked) |
| Type | WordPress plugin, PHP 7.2+, WP 5.0+, GPLv2+ |
| Version | 1.0.0 (`knittnet-ai-assistant.php`) |
| Maturity | **Rebrand + refactor done (~70%)**; not field-tested after large deletions |

## 2. Structure

```
knittnet-ai-assistant.php   # bootstrap; DB migrations (incl. legacy MxChat version notes e.g. 2.5.1, 3.1.2)
readme.txt                  # WP.org-style readme, rewritten (OpenRouter-only positioning)
includes/
  ai/                       # NEW unified OpenRouter subsystem: interface, provider, manager (KnittNet_AI_Manager),
                            #   prompt / conversation / streaming / token / error helpers
  class-knittnet-admin.php, class-knittnet-public.php, class-rest-api.php
  class-knittnet-chunker.php, class-knittnet-word-handler.php, pdf-parser/   # RAG ingestion (sitemaps, PDFs, URLs, manual)
  class-knittnet-content-generator.php, class-knittnet-meta-box.php           # AI content generator
  class-knittnet-woocommerce.php                                               # product cards, cart actions
  class-knittnet-model-catalog.php, class-knittnet-tool-registry.php, class-knittnet-integrator.php
  class-knittnet-cache-purge.php, class-knittnet-addons.php, class-knittnet-user.php, class-knittnet-utils.php
  admin-*-page.php          # settings, api, knowledge, content, actions, transcripts, onboarding, pro
admin/, css/, js/ (knet- prefixed), images/, languages/, wpml-config.xml
```

Vector store: Pinecone references remain (RAG) — check which embedding path is active now that only OpenRouter is used (OpenRouter has limited embedding support).

## 3. Phases done (commits)

1. Import MxChat v3.2.12 baseline
2. Rename MxChat → KnittNet across code, identifiers, assets
3. `mxch-` → `knet-` CSS/JS prefix; new plugin icon
4. Rewrite `readme.txt`
5. Add unified OpenRouter-only AI subsystem
6. Delete native OpenAI/Claude/Gemini/xAI/DeepSeek/Custom chat, image & function-calling code
7. Remove licence/activation gating
8. Route non-streaming chat via `KnittNet_AI_Manager`; remove duplicate error helper
9. Fix fatal on Settings (deleted migration banner call); clean licence-upsell UI
10. Fix pre-existing fatal (calls to never-implemented cache-invalidation methods after Pinecone deletion)

## 4. Remaining / risks

- [ ] **Full regression test** in a real WP + WooCommerce site: chat (streaming & non-streaming), RAG ingest, content generator, image generation (was native-provider code deleted?), Slack handoff, transcripts
- [ ] **Embeddings**: confirm RAG embeddings work via OpenRouter or keep a dedicated embedding provider
- [ ] Legal: MxChat is GPL — keep GPL licence, copyright notices and give credit; check trademark use of "MxChat" in remaining strings/assets
- [ ] Repo is public — commercial positioning and branding decisions are visible
- [ ] Update readme "Tested up to", add changelog for 1.0.0, prepare release ZIP
- [ ] No automated tests
