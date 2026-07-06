/**
 * KnittNet Actions Page JavaScript - v2.0
 * Split-panel layout with action list and editor
 */
jQuery(document).ready(function($) {
    // ==========================================================================
    // Sidebar Navigation
    // ==========================================================================

    // Desktop sidebar navigation
    $('.knet-nav-link').on('click', function(e) {
        e.preventDefault();
        const target = $(this).data('target');

        // Update active states
        $('.knet-nav-link').removeClass('active');
        $(this).addClass('active');

        // Show target section
        $('.knet-section').removeClass('active');
        $('#' + target).addClass('active');

        // Also update mobile nav if open
        $('.knet-mobile-nav-link').removeClass('active');
        $('.knet-mobile-nav-link[data-target="' + target + '"]').addClass('active');

        // Load actions when switching to all-actions
        if (target === 'all-actions' && !actionsLoaded) {
            loadActionList(1, '', '');
        }
    });

    // Mobile menu toggle
    $('.knet-mobile-menu-btn').on('click', function() {
        $('.knet-mobile-menu').addClass('open');
        $('.knet-mobile-overlay').addClass('open');
    });

    // Close mobile menu
    $('.knet-mobile-menu-close, .knet-mobile-overlay').on('click', function() {
        $('.knet-mobile-menu').removeClass('open');
        $('.knet-mobile-overlay').removeClass('open');
    });

    // Mobile navigation
    $('.knet-mobile-nav-link').on('click', function(e) {
        e.preventDefault();
        const target = $(this).data('target');

        $('.knet-mobile-nav-link').removeClass('active');
        $(this).addClass('active');

        $('.knet-section').removeClass('active');
        $('#' + target).addClass('active');

        $('.knet-nav-link').removeClass('active');
        $('.knet-nav-link[data-target="' + target + '"]').addClass('active');

        $('.knet-mobile-menu').removeClass('open');
        $('.knet-mobile-overlay').removeClass('open');
    });

    // ==========================================================================
    // AI Tools: autosave the function-calling toggle + per-tool checklist
    // (matches KnittNet's autosave UX — no Save button). plan a41dee.
    // ==========================================================================
    var $fc = $('#knet-fc-settings');
    if ($fc.length) {
        var fcNonce = $fc.data('fc-nonce');
        var $fcStatus = $('#knet-fc-save-status');
        var fcTimer = null;

        function fcCollectAndSave() {
            // Presence = active (plan d450a7): the global on/off toggle is gone, so
            // the gate is derived from whether any tool is enabled. The server
            // derives this too; we send it for an immediate, correct nav badge.
            var enabled = $fc.find('input[name="knittnet_fc_tools[]"]:checked').length > 0 ? '1' : '0';
            var allTools = $fc.find('input[name="knittnet_fc_all_tools[]"]').map(function () { return $(this).val(); }).get();
            var checked = $fc.find('input[name="knittnet_fc_tools[]"]:checked').map(function () { return $(this).val(); }).get();
            // Per-tool "when to use" hints, keyed by callback.
            var hints = {};
            $fc.find('.knet-fc-hint-input').each(function () {
                var cb = $(this).data('fc-callback');
                if (cb) { hints[cb] = $(this).val(); }
            });

            if ($fcStatus.length) { $fcStatus.text('Saving…').removeClass('is-saved is-error'); }

            $.post(knetActionsData.ajaxUrl, {
                action: 'knittnet_fc_autosave',
                nonce: fcNonce,
                enabled: enabled,
                all_tools: allTools,
                tools: checked,
                hints: hints
            }).done(function () {
                if ($fcStatus.length) {
                    $fcStatus.text('Saved').addClass('is-saved');
                    setTimeout(function () { $fcStatus.text('').removeClass('is-saved'); }, 2000);
                }
            }).fail(function () {
                if ($fcStatus.length) { $fcStatus.text('Save failed — try again').addClass('is-error'); }
            });
        }

        // The hidden store's checkboxes are toggled programmatically by the
        // list/detail/modal flow (which calls fcCollectAndSave() directly). Keep
        // a change-bound autosave as a safety net for any direct mutation.
        $fc.on('change', 'input[name="knittnet_fc_tools[]"]', function () {
            clearTimeout(fcTimer);
            fcTimer = setTimeout(fcCollectAndSave, 200);
        });

        // ======================================================================
        // AI Tools MASTER-DETAIL list + detail panel (plan d450a7).
        // Clones the Trigger Phrases list/detail UX: the left list shows the
        // ACTIVE tools (a tool in the list = active — no global toggle, no
        // per-tool enable checkbox); selecting one shows its detail on the right
        // with Edit (usage note) + Remove. "Add" opens the same two-step
        // capability picker. Pure view layer over the hidden #knet-fc-tool-store
        // inputs — the unchanged knittnet_fc_autosave contract (knittnet_fc_tools[] /
        // knittnet_fc_all_tools[] + hints) stays the source of truth. FC-specific
        // IDs/classes keep the Trigger Phrases JS off these elements.
        // ======================================================================
        var $fcStore = $('#knet-fc-tool-store');
        if ($fcStore.length) {
            var $fcPanel  = $('#knet-fc-panel');
            var $fcList   = $('#knet-fc-list');
            var $fcCount  = $('#knet-fc-count');
            var $fcEmpty  = $('#knet-fc-empty');
            var $fcView   = $('#knet-fc-view');
            var $fcDetail = $('#knet-fc-detail-panel');
            var $fcSearch = $('#knet-fc-search');
            var $fcModal  = $('#knet-fc-tool-modal');
            var fcCurrentCb = null; // tool being added/edited in the modal
            var fcViewCb    = null; // tool currently shown in the detail panel
            var fcSelected  = new Set(); // callbacks ticked for bulk remove (plan 5f7409)

            var fcI18n = {
                tools:     $fcPanel.data('i18n-tools') || 'tools',
                tool:      $fcPanel.data('i18n-tool') || 'tool',
                active:    $fcPanel.data('i18n-active') || 'Active',
                addon:     $fcPanel.data('i18n-addon') || 'Add-on',
                sensitive: $fcPanel.data('i18n-sensitive') || 'Sensitive',
                noHint:    $fcPanel.data('i18n-nohint') || 'No usage note — the AI decides on its own when to use this.'
            };
            // Localized Add/Edit titles + button labels read from the modal DOM.
            var $fcStep2Title = $('#knet-fc-modal-step2-title');
            var $fcSaveBtn = $('#knet-fc-modal-save');
            var FC_TITLE_ADD  = $fcStep2Title.data('add-title') || 'Add Tool';
            var FC_TITLE_EDIT = $fcStep2Title.data('edit-title') || 'Edit Tool';
            var FC_BTN_ADD    = $fcSaveBtn.data('add-label') || 'Add Tool';
            var FC_BTN_SAVE   = $fcSaveBtn.data('save-label') || 'Save Changes';

            function fcEsc(s) { return $('<div>').text(s == null ? '' : String(s)).html(); }
            function fcEntry(cb) {
                return $fcStore.find('.knet-fc-tool').filter(function () {
                    return String($(this).data('fc-callback')) === String(cb);
                });
            }
            function fcIsOn($t) { return $t.find('input[name="knittnet_fc_tools[]"]').is(':checked'); }
            function fcIsMobile() { return window.innerWidth <= 782; }

            // Show the empty "Select a tool" state in the detail panel.
            function fcShowEmpty() {
                fcViewCb = null;
                if ($fcView.length) $fcView.hide();
                if ($fcEmpty.length) $fcEmpty.show();
                $fcList.find('.knet-fc-list-item').removeClass('active');
                if (fcIsMobile()) { $fcDetail.removeClass('mobile-active'); $('body').removeClass('knet-mobile-panel-open'); }
            }

            // Build the left list from the store (active tools only), applying the
            // current search filter. Updates the count + resets the view if the
            // shown tool was just removed.
            function fcRenderList() {
                if (!$fcList.length) return;
                var q = $.trim(($fcSearch.val() || '')).toLowerCase();
                $fcList.empty();
                var total = 0;
                $fcStore.find('.knet-fc-tool').each(function () {
                    var $t = $(this);
                    if (!fcIsOn($t)) return;
                    total++;
                    var cb = $t.data('fc-callback');
                    var label = $t.data('fc-label') || cb;
                    var desc = $t.data('fc-desc') || '';
                    var icon = $t.data('fc-icon') || 'admin-generic';
                    var isAddon = String($t.data('fc-addon')) === '1';
                    var isCautious = String($t.data('fc-cautious')) === '1';
                    if (q && String(label).toLowerCase().indexOf(q) === -1 && String(desc).toLowerCase().indexOf(q) === -1) return;
                    var typeBits = [];
                    if (isAddon) typeBits.push(fcI18n.addon);
                    if (isCautious) typeBits.push(fcI18n.sensitive);
                    var typeLabel = typeBits.length ? typeBits.join(' · ') : fcI18n.active;
                    var activeCls = (String(cb) === String(fcViewCb)) ? ' active' : '';
                    var isSel = fcSelected.has(String(cb));
                    var selCls = isSel ? ' selected' : '';
                    $fcList.append(
                        '<div class="knet-fc-list-item' + activeCls + selCls + '" data-fc-callback="' + fcEsc(cb) + '">' +
                            '<input type="checkbox" class="knet-fc-checkbox"' + (isSel ? ' checked' : '') + '>' +
                            '<div class="knet-action-icon"><span class="dashicons dashicons-' + fcEsc(icon) + '"></span></div>' +
                            '<div class="knet-action-info">' +
                                '<div class="knet-action-name">' + fcEsc(label) + '</div>' +
                                '<div class="knet-action-type-label">' + fcEsc(typeLabel) + '</div>' +
                            '</div>' +
                            '<div class="knet-action-meta">' +
                                '<span class="knet-action-status enabled">' + fcEsc(fcI18n.active) + '</span>' +
                            '</div>' +
                        '</div>'
                    );
                });
                if ($fcCount.length) {
                    $fcCount.text(total + ' ' + (total === 1 ? fcI18n.tool : fcI18n.tools));
                }
                // Drop any selected callbacks that are no longer in the list (e.g.
                // filtered out or removed) so the bulk count stays truthful.
                var fcPresent = {};
                $fcList.find('.knet-fc-list-item').each(function () {
                    fcPresent[String($(this).data('fc-callback'))] = true;
                });
                fcSelected.forEach(function (selCb) {
                    if (!fcPresent[String(selCb)]) { fcSelected.delete(selCb); }
                });
                fcUpdateSelectionUI();
                // If the tool currently in the detail panel is no longer active, reset.
                if (fcViewCb && !fcIsOn(fcEntry(fcViewCb))) { fcShowEmpty(); }
            }

            // Populate + show the detail view for one tool.
            function fcSelectTool(cb) {
                var $t = fcEntry(cb);
                if (!$t.length || !fcIsOn($t)) { fcShowEmpty(); return; }
                fcViewCb = String(cb);
                var label = $t.data('fc-label') || cb;
                var desc = $t.data('fc-desc') || '';
                var icon = $t.data('fc-icon') || 'admin-generic';
                var isAddon = String($t.data('fc-addon')) === '1';
                var isCautious = String($t.data('fc-cautious')) === '1';
                var hint = $.trim($t.find('.knet-fc-hint-input').val() || '');
                var typeBits = [];
                if (isAddon) typeBits.push(fcI18n.addon);
                if (isCautious) typeBits.push(fcI18n.sensitive);
                $('#knet-fc-view-icon').html('<span class="dashicons dashicons-' + fcEsc(icon) + '"></span>');
                $('#knet-fc-view-label').text(label);
                $('#knet-fc-view-type').text(typeBits.join(' · '));
                $('#knet-fc-view-desc').text(desc);
                var $hint = $('#knet-fc-view-hint');
                if (hint) { $hint.text(hint).removeClass('is-muted'); }
                else { $hint.text(fcI18n.noHint).addClass('is-muted'); }
                // Setup requirement (plan 183856): show the tool's setup note (e.g. the
                // Brave-key requirement on Web/Image Search) when present, else hide it.
                var setup = $.trim(String($t.data('fc-setup') || ''));
                var $setupWrap = $('#knet-fc-view-setup-wrap');
                if (setup) { $('#knet-fc-view-setup').text(setup); $setupWrap.show(); }
                else { $setupWrap.hide(); }
                $fcEmpty.hide();
                $fcView.show();
                $fcList.find('.knet-fc-list-item').removeClass('active')
                    .filter(function () { return String($(this).data('fc-callback')) === String(cb); }).addClass('active');
                if (fcIsMobile()) { $fcDetail.addClass('mobile-active'); $('body').addClass('knet-mobile-panel-open'); }
            }

            // ---- Add/Edit modal (two-step capability picker) ----
            function fcShowStep(n) {
                $fcModal.find('.knet-fc-modal-step').hide();
                $fcModal.find('.knet-fc-modal-step[data-step="' + n + '"]').show();
            }
            function fcCloseModal() { $fcModal.hide(); fcCurrentCb = null; }

            // Step 1: grid of tools not yet added.
            function fcOpenAddModal() {
                var $grid = $('#knet-fc-modal-grid').empty();
                var addable = 0;
                $fcStore.find('.knet-fc-tool').each(function () {
                    var $t = $(this);
                    if (fcIsOn($t)) return; // already active
                    addable++;
                    var cb = $t.data('fc-callback');
                    var label = $t.data('fc-label') || cb;
                    var desc = $t.data('fc-desc') || '';
                    var icon = $t.data('fc-icon') || 'admin-generic';
                    var isAddon = String($t.data('fc-addon')) === '1';
                    var isCautious = String($t.data('fc-cautious')) === '1';
                    var badges = '';
                    if (isAddon) { badges += ' <span class="knet-fc-badge knet-fc-badge-addon">' + fcEsc(fcI18n.addon) + '</span>'; }
                    if (isCautious) { badges += ' <span class="knet-fc-badge knet-fc-badge-sensitive">' + fcEsc(fcI18n.sensitive) + '</span>'; }
                    $grid.append(
                        '<div class="knet-type-card knet-fc-type-card" data-fc-callback="' + fcEsc(cb) + '">' +
                            '<div class="knet-type-icon"><span class="dashicons dashicons-' + fcEsc(icon) + '"></span></div>' +
                            '<div class="knet-type-info">' +
                                '<h4>' + fcEsc(label) + badges + '</h4>' +
                                '<p>' + fcEsc(desc) + '</p>' +
                            '</div>' +
                        '</div>'
                    );
                });
                $('#knet-fc-modal-allset').toggle(addable === 0);
                $grid.toggle(addable > 0);
                fcShowStep(1);
                $fcModal.css('display', 'flex');
            }

            // Step 2: confirm + when-to-use (shared by Add and Edit).
            function fcOpenConfigStep(cb) {
                var $t = fcEntry(cb);
                if (!$t.length) return;
                fcCurrentCb = cb;
                var editing = fcIsOn($t);
                $('#knet-fc-modal-selected-icon').html('<span class="dashicons dashicons-' + fcEsc($t.data('fc-icon') || 'admin-generic') + '"></span>');
                $('#knet-fc-modal-selected-label').text($t.data('fc-label') || cb);
                $('#knet-fc-modal-selected-desc').text($t.data('fc-desc') || '');
                $('#knet-fc-modal-hint').val($t.find('.knet-fc-hint-input').val() || '');
                $('#knet-fc-modal-sensitive').toggle(String($t.data('fc-cautious')) === '1');
                $fcStep2Title.text(editing ? FC_TITLE_EDIT : FC_TITLE_ADD);
                $fcSaveBtn.text(editing ? FC_BTN_SAVE : FC_BTN_ADD);
                fcShowStep(2);
                $fcModal.css('display', 'flex');
            }

            // ---- wiring ----
            // "Add" buttons (panel header + empty state) — delegated on $fc.
            $fc.on('click', '.js-knet-fc-add', fcOpenAddModal);
            $fcModal.on('click', '.knet-fc-type-card', function () { fcOpenConfigStep($(this).data('fc-callback')); });
            $('#knet-fc-modal-back').on('click', fcOpenAddModal);
            $fcModal.on('click', '[data-fc-modal-close]', fcCloseModal);
            $fcModal.on('click', function (e) { if (e.target === this) fcCloseModal(); }); // backdrop
            $(document).on('keydown', function (e) { if (e.key === 'Escape' && $fcModal.is(':visible')) fcCloseModal(); });

            // Modal Save (Add or Edit): write the store inputs, autosave, refresh
            // the list, and show the tool's detail on the right.
            $fcSaveBtn.on('click', function () {
                if (!fcCurrentCb) return;
                var $t = fcEntry(fcCurrentCb);
                if (!$t.length) return;
                var cb = fcCurrentCb;
                $t.find('.knet-fc-hint-input').val($('#knet-fc-modal-hint').val() || '');
                $t.find('input[name="knittnet_fc_tools[]"]').prop('checked', true);
                fcCloseModal();
                fcRenderList();
                fcSelectTool(cb);
                fcCollectAndSave();
            });

            // List item click → show that tool's detail.
            $fcList.on('click', '.knet-fc-list-item', function () {
                fcSelectTool($(this).data('fc-callback'));
            });

            // Detail Edit → reopen the config step for the viewed tool.
            $('#knet-fc-edit-btn').on('click', function () {
                if (fcViewCb) fcOpenConfigStep(fcViewCb);
            });
            // Detail Remove → disable the tool (keep its hint for a later re-add),
            // refresh the list, and return to the empty state.
            $('#knet-fc-remove-btn').on('click', function () {
                if (!fcViewCb) return;
                var $t = fcEntry(fcViewCb);
                if (!$t.length) return;
                $t.find('input[name="knittnet_fc_tools[]"]').prop('checked', false);
                fcShowEmpty();
                fcRenderList();
                fcCollectAndSave();
            });

            // ---- Bulk selection + bulk remove ----
            // Clones the Trigger Phrases #all-actions bulk toolbar behavior, but
            // wires removal to the SAME path the single Remove button uses (uncheck
            // each tool's store checkbox, then one autosave). FC-specific IDs keep
            // this isolated from the Trigger Phrases bulk JS (plan 5f7409).
            var $fcSelectAll  = $('#knet-fc-select-all');
            var $fcBulkDelete = $('#knet-fc-delete-selected');
            var $fcSelCount   = $('#knet-fc-selected-count');

            function fcUpdateSelectionUI() {
                var count = fcSelected.size;
                if (count > 0) {
                    $fcSelCount.text(count + ' selected').addClass('has-selection');
                    $fcBulkDelete.prop('disabled', false);
                    $fcList.addClass('selection-mode');
                } else {
                    $fcSelCount.removeClass('has-selection');
                    $fcBulkDelete.prop('disabled', true);
                    $fcList.removeClass('selection-mode');
                }
                var totalItems = $fcList.find('.knet-fc-checkbox').length;
                var checkedItems = $fcList.find('.knet-fc-checkbox:checked').length;
                $fcSelectAll.prop('checked', totalItems > 0 && checkedItems === totalItems);
                $fcSelectAll.prop('indeterminate', checkedItems > 0 && checkedItems < totalItems);
            }

            // Select-all toggles every visible tool row.
            $fcSelectAll.on('change', function () {
                var on = $(this).is(':checked');
                fcSelected.clear();
                $fcList.find('.knet-fc-list-item').each(function () {
                    var $row = $(this);
                    var cb = String($row.data('fc-callback'));
                    $row.find('.knet-fc-checkbox').prop('checked', on);
                    if (on) { fcSelected.add(cb); $row.addClass('selected'); }
                    else { $row.removeClass('selected'); }
                });
                fcUpdateSelectionUI();
            });

            // Per-row checkbox: toggle selection without opening the detail view.
            $fcList.on('click', '.knet-fc-checkbox', function (e) {
                e.stopPropagation();
                var $row = $(this).closest('.knet-fc-list-item');
                var cb = String($row.data('fc-callback'));
                if ($(this).is(':checked')) { fcSelected.add(cb); $row.addClass('selected'); }
                else { fcSelected.delete(cb); $row.removeClass('selected'); }
                fcUpdateSelectionUI();
            });

            // Bulk remove: same removal path as single Remove — uncheck each tool's
            // store checkbox (presence = active), then refresh + autosave once.
            $fcBulkDelete.on('click', function () {
                if (!fcSelected.size) return;
                var msg = (window.knetActionsData && knetActionsData.i18n && knetActionsData.i18n.confirmBulkDelete)
                    || 'Remove the selected tools? They stay available to add back later.';
                if (!window.confirm(msg)) return;
                var removingViewed = false;
                fcSelected.forEach(function (cb) {
                    var $t = fcEntry(cb);
                    if ($t.length) { $t.find('input[name="knittnet_fc_tools[]"]').prop('checked', false); }
                    if (String(cb) === String(fcViewCb)) { removingViewed = true; }
                });
                fcSelected.clear();
                if (removingViewed) { fcShowEmpty(); }
                fcRenderList();
                fcCollectAndSave();
            });

            // Search filter.
            $fcSearch.on('input', function () { fcRenderList(); });

            // Refresh — re-render the list from the store (parity with the Trigger
            // Phrases refresh affordance; the list is client-built so this re-syncs
            // the view rather than re-fetching).
            $('#knet-fc-refresh').on('click', function () {
                var $btn = $(this);
                $btn.addClass('spinning');
                fcRenderList();
                setTimeout(function () { $btn.removeClass('spinning'); }, 500);
            });

            fcRenderList();
        }
    }

    // Quick action buttons
    $('.knet-quick-action-btn[data-action="view-actions"]').on('click', function() {
        $('.knet-nav-link[data-target="all-actions"]').trigger('click');
    });

    // Dashboard explainer cards — generic "jump to this section" (e.g. Set up AI Tools).
    $('.knet-approach-btn[data-approach-target]').on('click', function() {
        var target = $(this).data('approach-target');
        $('.knet-nav-link[data-target="' + target + '"]').trigger('click');
    });

    // "Add Trigger Phrase" on the dashboard → jump to Trigger Phrases + open the editor.
    $('#knet-add-action-dashboard-btn').on('click', function() {
        $('.knet-nav-link[data-target="all-actions"]').trigger('click');
        setTimeout(function() {
            openActionEditor();
        }, 100);
    });

    // ==========================================================================
    // Mobile Detection & Panel Management
    // ==========================================================================

    function isMobile() {
        return window.innerWidth <= 782;
    }

    function showMobileDetailPanel() {
        if (isMobile()) {
            $('.knet-action-detail-panel').addClass('mobile-active');
            $('body').addClass('knet-mobile-panel-open');
        }
    }

    function hideMobileDetailPanel() {
        $('.knet-action-detail-panel').removeClass('mobile-active');
        $('body').removeClass('knet-mobile-panel-open');
    }

    // Mobile back button handler
    $(document).on('click', '.knet-mobile-back-btn', function(e) {
        e.preventDefault();
        hideMobileDetailPanel();
        resetEditorPanel();
        $('.knet-action-item').removeClass('active');
        currentActionId = null;
    });

    // ==========================================================================
    // Action List - Split Panel
    // ==========================================================================

    let currentPage = 1;
    const perPage = 50;
    let totalPages = 1;
    let currentActionId = null;
    let actionsLoaded = false;
    let selectedActions = new Set();
    let currentSortOrder = 'desc';
    let currentFilter = '';

    // ==========================================================================
    // Phrase Tag Management
    // ==========================================================================

    let currentPhrases = []; // Array of {id, text, isLegacy}

    function renderPhraseTags() {
        const $container = $('#knet-phrase-tags');
        let html = '';
        currentPhrases.forEach(function(phrase, index) {
            const legacyClass = phrase.isLegacy ? ' knet-phrase-legacy' : '';
            const badge = phrase.isLegacy ? ' <span class="knet-legacy-badge">legacy</span>' : '';
            html += '<span class="knet-phrase-pill' + legacyClass + '" data-index="' + index + '" data-phrase-id="' + (phrase.id || '') + '">'
                + escapeHtml(phrase.text) + badge
                + ' <button type="button" class="knet-phrase-remove" title="Remove">&times;</button>'
                + '</span>';
        });
        $container.html(html);
    }

    function addPhraseFromInput() {
        const $input = $('#knet-phrase-input');
        const text = $input.val().trim();
        if (!text) return;

        const formMode = $('#knet-form-mode').val();
        const intentId = $('#knet-action-id').val();

        if (formMode === 'edit' && intentId) {
            // Edit mode: AJAX add immediately
            const $btn = $('#knet-add-phrase-btn');
            $btn.prop('disabled', true).text('Adding...');

            $.ajax({
                url: knetActionsData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'knittnet_add_phrase',
                    intent_id: intentId,
                    phrase: text,
                    security: knetActionsData.addPhraseNonce
                },
                success: function(response) {
                    $btn.prop('disabled', false).text('Add');
                    if (response.success) {
                        currentPhrases.push({id: response.data.id, text: text, isLegacy: false});
                        renderPhraseTags();
                        $input.val('').focus();
                    } else {
                        alert(response.data || 'Failed to add phrase.');
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).text('Add');
                    alert('Failed to add phrase. Please try again.');
                }
            });
        } else {
            // Add mode: just push locally
            currentPhrases.push({id: null, text: text, isLegacy: false});
            renderPhraseTags();
            $input.val('').focus();
        }
    }

    // Add phrase on Enter key
    $(document).on('keydown', '#knet-phrase-input', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addPhraseFromInput();
        }
    });

    // Add phrase on button click
    $(document).on('click', '#knet-add-phrase-btn', function() {
        addPhraseFromInput();
    });

    // Remove phrase on X click
    $(document).on('click', '.knet-phrase-remove', function(e) {
        e.stopPropagation();
        const $pill = $(this).closest('.knet-phrase-pill');
        const index = parseInt($pill.data('index'));
        const phrase = currentPhrases[index];

        if (!phrase) return;

        if (phrase.isLegacy) {
            // Delete legacy phrases from main table
            const intentId = $('#knet-action-id').val();
            if (!intentId) {
                currentPhrases.splice(index, 1);
                renderPhraseTags();
                return;
            }

            if (!confirm('Remove all legacy grouped phrases? You can re-add them individually for better matching.')) return;

            $.ajax({
                url: knetActionsData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'knittnet_delete_legacy_phrases',
                    intent_id: intentId,
                    security: knetActionsData.deleteLegacyNonce
                },
                success: function(response) {
                    if (response.success) {
                        currentPhrases.splice(index, 1);
                        renderPhraseTags();
                    } else {
                        alert(response.data || 'Failed to remove legacy phrases.');
                    }
                }
            });
        } else if (phrase.id) {
            // Delete individual phrase via AJAX
            $.ajax({
                url: knetActionsData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'knittnet_delete_phrase',
                    phrase_id: phrase.id,
                    security: knetActionsData.deletePhraseNonce
                },
                success: function(response) {
                    if (response.success) {
                        currentPhrases.splice(index, 1);
                        renderPhraseTags();
                    } else {
                        alert(response.data || 'Failed to delete phrase.');
                    }
                }
            });
        } else {
            // Local-only phrase (add mode, not yet saved)
            currentPhrases.splice(index, 1);
            renderPhraseTags();
        }
    });

    function fetchIndividualPhrases(intentId, callback) {
        $.ajax({
            url: knetActionsData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'knittnet_get_phrases',
                intent_id: intentId,
                security: knetActionsData.getPhrasesNonce
            },
            success: function(response) {
                if (response.success) {
                    callback(response.data.phrases || []);
                } else {
                    callback([]);
                }
            },
            error: function() {
                callback([]);
            }
        });
    }

    // Load action list on page load
    loadActionList(1, '', '');

    // Search functionality with debounce
    let searchTimeout;
    $('#knet-search-actions').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val().toLowerCase();

        searchTimeout = setTimeout(function() {
            currentPage = 1;
            loadActionList(currentPage, searchTerm, currentFilter);
        }, 300);
    });

    // Filter by type
    $('#knet-filter-actions').on('change', function() {
        currentFilter = $(this).val();
        currentPage = 1;
        loadActionList(currentPage, $('#knet-search-actions').val(), currentFilter);
    });

    // Refresh button
    $('#knet-refresh-actions').on('click', function() {
        const $btn = $(this);
        $btn.addClass('spinning');
        loadActionList(currentPage, $('#knet-search-actions').val(), currentFilter);
        setTimeout(() => $btn.removeClass('spinning'), 500);
    });

    // ==========================================================================
    // Bulk Selection & Actions
    // ==========================================================================

    // Select all checkbox
    $('#knet-select-all-actions').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.knet-action-checkbox').prop('checked', isChecked);

        if (isChecked) {
            $('.knet-action-item').each(function() {
                selectedActions.add($(this).data('action-id'));
                $(this).addClass('selected');
            });
            $('#knet-action-list').addClass('selection-mode');
        } else {
            selectedActions.clear();
            $('.knet-action-item').removeClass('selected');
            $('#knet-action-list').removeClass('selection-mode');
        }

        updateSelectionUI();
    });

    // Update selection UI
    function updateSelectionUI() {
        const count = selectedActions.size;
        const $countEl = $('#knet-selected-action-count');
        const $deleteBtn = $('#knet-delete-selected-actions');

        if (count > 0) {
            $countEl.text(count + ' selected').addClass('has-selection');
            $deleteBtn.prop('disabled', false);
            $('#knet-action-list').addClass('selection-mode');
        } else {
            $countEl.removeClass('has-selection');
            $deleteBtn.prop('disabled', true);
            $('#knet-action-list').removeClass('selection-mode');
        }

        // Update select all checkbox state
        const totalItems = $('.knet-action-checkbox').length;
        const checkedItems = $('.knet-action-checkbox:checked').length;
        $('#knet-select-all-actions').prop('checked', totalItems > 0 && checkedItems === totalItems);
        $('#knet-select-all-actions').prop('indeterminate', checkedItems > 0 && checkedItems < totalItems);
    }

    // Delete selected button
    $('#knet-delete-selected-actions').on('click', function() {
        const count = selectedActions.size;
        if (count === 0) return;

        if (!confirm(knetActionsData.i18n.confirmBulkDelete)) {
            return;
        }

        deleteMultipleActions(Array.from(selectedActions));
    });

    // Delete multiple actions
    function deleteMultipleActions(actionIds) {
        showLoading();

        $.ajax({
            url: knetActionsData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'knittnet_bulk_delete_actions',
                action_ids: actionIds,
                security: knetActionsData.deleteNonce
            },
            success: function(response) {
                hideLoading();

                if (response.success) {
                    // Clear selection
                    selectedActions.clear();
                    $('#knet-select-all-actions').prop('checked', false);
                    updateSelectionUI();

                    // If current action was deleted, reset panel
                    if (actionIds.includes(currentActionId)) {
                        currentActionId = null;
                        resetEditorPanel();
                    }

                    // Reload list
                    loadActionList(currentPage, $('#knet-search-actions').val(), currentFilter);
                } else {
                    alert(response.data || knetActionsData.i18n.error);
                }
            },
            error: function() {
                hideLoading();
                alert(knetActionsData.i18n.error);
            }
        });
    }

    // Load action list function
    function loadActionList(page, searchTerm, filter) {
        const $container = $('#knet-action-list');
        $container.html('<div class="knet-list-loading"><span class="spinner is-active"></span></div>');

        $.ajax({
            url: knetActionsData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'knittnet_fetch_actions_list',
                page: page,
                per_page: perPage,
                search: searchTerm,
                callback_filter: filter,
                sort_order: currentSortOrder,
                security: knetActionsData.nonce
            },
            success: function(response) {
                actionsLoaded = true;

                if (response.success && response.data.actions && response.data.actions.length > 0) {
                    renderActionList(response.data.actions);
                    currentPage = response.data.page;
                    totalPages = response.data.total_pages;
                    updateActionCount(response.data.showing_start, response.data.showing_end, response.data.total_actions);
                    renderPagination(response.data.page, response.data.total_pages, searchTerm, filter);
                } else {
                    $container.html('<div class="knet-list-empty"><p>No trigger phrases found</p></div>');
                    updateActionCount(0, 0, 0);
                    $('#knet-actions-pagination').html('');
                }
            },
            error: function() {
                $container.html('<div class="knet-list-empty"><p>Error loading trigger phrases</p></div>');
            }
        });
    }

    // Render action list items
    function renderActionList(actions) {
        const $container = $('#knet-action-list');
        let html = '';

        actions.forEach(function(action) {
            const isActive = action.id == currentActionId ? ' active' : '';
            const isSelected = selectedActions.has(action.id) ? ' selected' : '';
            const isChecked = selectedActions.has(action.id) ? ' checked' : '';
            const isDisabled = !action.enabled ? ' disabled' : '';
            const statusClass = action.enabled ? 'enabled' : 'disabled';
            const statusText = action.enabled ? 'Enabled' : 'Disabled';

            html += `
                <div class="knet-action-item${isActive}${isSelected}${isDisabled}"
                     data-action-id="${action.id}"
                     data-label="${escapeHtml(action.label)}"
                     data-phrases="${escapeHtml(action.phrases)}"
                     data-threshold="${action.threshold}"
                     data-callback="${escapeHtml(action.callback_function)}"
                     data-enabled="${action.enabled ? '1' : '0'}"
                     data-enabled-bots='${escapeHtml(JSON.stringify(action.enabled_bots))}'
                     data-has-legacy="${action.has_legacy_vector ? '1' : '0'}"
                     data-individual-count="${action.individual_phrase_count || 0}">
                    <input type="checkbox" class="knet-action-checkbox"${isChecked}>
                    <div class="knet-action-icon">
                        <span class="dashicons dashicons-${escapeHtml(action.icon || 'admin-generic')}"></span>
                    </div>
                    <div class="knet-action-info">
                        <div class="knet-action-name">${escapeHtml(action.label)}</div>
                        <div class="knet-action-type-label">${escapeHtml(action.callback_label)}</div>
                    </div>
                    <div class="knet-action-meta">
                        <span class="knet-action-status ${statusClass}">${statusText}</span>
                    </div>
                </div>
            `;
        });

        $container.html(html);

        // Attach checkbox handlers
        $('.knet-action-checkbox').on('click', function(e) {
            e.stopPropagation();
            const $item = $(this).closest('.knet-action-item');
            const actionId = $item.data('action-id');

            if ($(this).is(':checked')) {
                selectedActions.add(actionId);
                $item.addClass('selected');
            } else {
                selectedActions.delete(actionId);
                $item.removeClass('selected');
            }

            updateSelectionUI();
        });

        // Attach click handlers for selecting action
        $('.knet-action-item').on('click', function(e) {
            if ($(e.target).is('.knet-action-checkbox')) return;

            const actionId = $(this).data('action-id');
            selectAction(actionId, $(this));

            // Update active state
            $('.knet-action-item').removeClass('active');
            $(this).addClass('active');
        });

        // Update selection UI after render
        updateSelectionUI();
    }

    // Update action count display
    function updateActionCount(start, end, total) {
        if (total === 0) {
            $('#knet-action-count').text('0 trigger phrases');
        } else {
            $('#knet-action-count').text(`${start}-${end} / ${total} trigger phrases`);
        }
    }

    // Render pagination
    function renderPagination(currentPage, totalPages, searchTerm, filter) {
        const $container = $('#knet-actions-pagination');

        if (totalPages <= 1) {
            $container.html('');
            return;
        }

        let html = '<div class="knet-pagination-btns">';

        if (currentPage > 1) {
            html += `<button class="knet-page-btn" data-page="${currentPage - 1}">&laquo;</button>`;
        }

        html += `<span class="knet-page-info">${currentPage} / ${totalPages}</span>`;

        if (currentPage < totalPages) {
            html += `<button class="knet-page-btn" data-page="${currentPage + 1}">&raquo;</button>`;
        }

        html += '</div>';
        $container.html(html);

        // Pagination click handlers
        $('.knet-page-btn').on('click', function() {
            const pageNum = $(this).data('page');
            loadActionList(pageNum, searchTerm, filter);
        });
    }

    // ==========================================================================
    // Action Editor Panel
    // ==========================================================================

    // Select and view an action
    function selectAction(actionId, $item) {
        currentActionId = actionId;

        // Get data from item attributes
        const data = {
            id: actionId,
            label: $item.data('label'),
            phrases: $item.data('phrases'),
            threshold: $item.data('threshold'),
            callback_function: $item.data('callback'),
            enabled: $item.data('enabled') == '1',
            enabled_bots: $item.data('enabled-bots') || ['default'],
            has_legacy: $item.data('has-legacy') == '1',
            individualPhrases: []
        };

        // Fetch individual phrases then show view
        fetchIndividualPhrases(actionId, function(phrases) {
            data.individualPhrases = phrases;
            showActionView(data);
        });
    }

    // Show action view (details)
    function showActionView(action) {
        $('#knet-action-empty').hide();
        $('#knet-action-editor').show();

        // Hide editor steps, show view
        $('.knet-editor-step').removeClass('active');
        $('#knet-action-view').addClass('active');

        // Show detail panel on mobile
        showMobileDetailPanel();

        // Populate view
        $('#knet-view-title').text(action.label);
        $('#knet-view-label').text(action.label);

        // Get callback label from filter dropdown
        const callbackLabel = $('#knet-filter-actions option[value="' + action.callback_function + '"]').text() || action.callback_function;
        $('#knet-view-type').text(callbackLabel);

        // Get icon from type grid
        const $typeCard = $('.knet-type-card[data-value="' + action.callback_function + '"]');
        const iconClass = $typeCard.find('.dashicons').attr('class') || 'dashicons dashicons-admin-generic';
        $('#knet-view-icon').html('<span class="' + iconClass + '"></span>');
        $('#knet-detail-icon').html('<span class="' + iconClass + '"></span>');

        // Phrases - show legacy grouped phrases and individual phrases
        let phrasesHtml = '';
        const legacyPhrases = action.phrases ? String(action.phrases).trim() : '';
        if (legacyPhrases) {
            phrasesHtml += '<span class="knet-phrase-tag knet-phrase-legacy">' + escapeHtml(legacyPhrases) + ' <span class="knet-legacy-badge">legacy</span></span>';
        }
        if (action.individualPhrases && action.individualPhrases.length) {
            action.individualPhrases.forEach(function(p) {
                phrasesHtml += '<span class="knet-phrase-tag">' + escapeHtml(p.phrase) + '</span>';
            });
        }
        if (!phrasesHtml) {
            phrasesHtml = '<span class="knet-no-phrases">No trigger phrases configured</span>';
        }
        $('#knet-view-phrases').html(phrasesHtml);

        // Settings
        $('#knet-view-threshold').text(action.threshold + '%');

        const statusText = action.enabled ? 'Enabled' : 'Disabled';
        const statusClass = action.enabled ? 'knet-status-enabled' : 'knet-status-disabled';
        $('#knet-view-status').text(statusText).removeClass('knet-status-enabled knet-status-disabled').addClass(statusClass);

        // Toggle switch
        $('#knet-action-enabled-toggle').prop('checked', action.enabled);

        // Bots
        let botsHtml = '';
        const enabledBots = Array.isArray(action.enabled_bots) ? action.enabled_bots : ['default'];
        enabledBots.forEach(function(bot) {
            const botName = bot === 'default' ? 'Default Bot' : bot;
            botsHtml += '<span class="knet-bot-tag">' + escapeHtml(botName) + '</span>';
        });
        $('#knet-view-bots').html(botsHtml);

        // Store current action data for editing
        $('#knet-action-view').data('current-action', action);
    }

    // Open action editor (for creating new)
    function openActionEditor() {
        currentActionId = null;

        $('#knet-action-empty').hide();
        $('#knet-action-editor').show();

        // Show step 1
        $('.knet-editor-step').removeClass('active');
        $('#knet-editor-step-1').addClass('active');

        // Reset form
        resetForm();

        // Show detail panel on mobile
        showMobileDetailPanel();
    }

    // Open action editor for editing
    function openActionEditorForEdit(action) {
        currentActionId = action.id;

        $('#knet-action-empty').hide();
        $('#knet-action-editor').show();

        // Go directly to step 2
        $('.knet-editor-step').removeClass('active');
        $('#knet-editor-step-2').addClass('active');

        // Populate form
        $('#knet-action-id').val(action.id);
        $('#knet-callback-function').val(action.callback_function);
        $('#knet-form-mode').val('edit');
        $('#knet-action-label').val(action.label);
        $('#knet-action-threshold').val(action.threshold);
        $('#knet-threshold-value').text(action.threshold + '%');

        // Populate phrase tags
        currentPhrases = [];
        const legacyPhrases = action.phrases ? String(action.phrases).trim() : '';
        if (legacyPhrases) {
            currentPhrases.push({id: 'legacy', text: legacyPhrases, isLegacy: true});
        }
        if (action.individualPhrases && action.individualPhrases.length) {
            action.individualPhrases.forEach(function(p) {
                currentPhrases.push({id: p.id, text: p.phrase, isLegacy: false});
            });
        }
        renderPhraseTags();

        // Set selected type display
        const $typeCard = $('.knet-type-card[data-value="' + action.callback_function + '"]');
        const iconHtml = $typeCard.find('.knet-type-icon').html();
        const typeLabel = $typeCard.data('label');
        const typeDesc = $typeCard.find('p').text();

        $('#knet-selected-type-icon').html(iconHtml);
        $('#knet-selected-type-label').text(typeLabel);
        $('#knet-selected-type-desc').text(typeDesc);
        $('#knet-config-title').text('Edit Trigger Phrase');

        // Set enabled bots
        const enabledBots = Array.isArray(action.enabled_bots) ? action.enabled_bots : ['default'];
        $('#knet-bot-selector input[type="checkbox"]').each(function() {
            $(this).prop('checked', enabledBots.includes($(this).val()));
        });

        // Update save button text
        $('#knet-save-action').text('Update Trigger Phrase');
    }

    // Reset form
    function resetForm() {
        $('#knet-action-id').val('');
        $('#knet-callback-function').val('');
        $('#knet-form-mode').val('add');
        $('#knet-action-label').val('');
        $('#knet-action-threshold').val(85);

        // Clear phrase tags
        currentPhrases = [];
        renderPhraseTags();
        $('#knet-threshold-value').text('85%');
        $('#knet-config-title').text('Configure Trigger Phrase');
        $('#knet-save-action').text('Save Trigger Phrase');

        // Reset bot selection
        $('#knet-bot-selector input[type="checkbox"]').prop('checked', false);
        $('#knet-bot-selector input[value="default"]').prop('checked', true);

        // Reset type selection
        $('.knet-type-card').removeClass('selected');
    }

    // Reset editor panel to empty state
    function resetEditorPanel() {
        $('#knet-action-editor').hide();
        $('#knet-action-empty').show();
        $('.knet-editor-step').removeClass('active');

        // Hide mobile panel
        hideMobileDetailPanel();
    }

    // ==========================================================================
    // Editor Event Handlers
    // ==========================================================================

    // Add new action buttons
    $('#knet-add-action-btn, #knet-create-first-action-btn').on('click', function() {
        openActionEditor();
    });

    // Close editor buttons
    $('#knet-close-editor, #knet-close-editor-2').on('click', function() {
        resetEditorPanel();
        $('.knet-action-item').removeClass('active');
        currentActionId = null;
    });

    // Cancel button
    $('#knet-cancel-action').on('click', function() {
        if (currentActionId && $('#knet-form-mode').val() === 'edit') {
            // Go back to view mode
            const action = $('#knet-action-view').data('current-action');
            if (action) {
                showActionView(action);
            }
        } else {
            resetEditorPanel();
        }
    });

    // Back to step 1
    $('#knet-back-to-step-1').on('click', function() {
        if ($('#knet-form-mode').val() === 'edit') {
            // If editing, go back to view
            const action = $('#knet-action-view').data('current-action');
            if (action) {
                showActionView(action);
            }
        } else {
            // If creating, go back to step 1
            $('.knet-editor-step').removeClass('active');
            $('#knet-editor-step-1').addClass('active');
        }
    });

    // Edit action button
    $('#knet-edit-action-btn').on('click', function() {
        const action = $('#knet-action-view').data('current-action');
        if (action) {
            openActionEditorForEdit(action);
        }
    });

    // Toggle enabled status
    $('#knet-action-enabled-toggle').on('change', function() {
        if (!currentActionId) return;

        const isEnabled = $(this).is(':checked');
        toggleActionStatus(currentActionId, isEnabled);
    });

    // Delete action button
    $('#knet-delete-action-btn').on('click', function() {
        if (!currentActionId) return;
        showDeleteModal(currentActionId);
    });

    // ==========================================================================
    // Action Type Selection (Step 1)
    // ==========================================================================

    // Type search
    $('#knet-type-search-input').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterTypeCards(searchTerm, 'all');
    });

    // Category buttons
    $('.knet-category-btn').on('click', function() {
        $('.knet-category-btn').removeClass('active');
        $(this).addClass('active');

        const category = $(this).data('category');
        const searchTerm = $('#knet-type-search-input').val().toLowerCase();
        filterTypeCards(searchTerm, category);
    });

    // Filter type cards
    function filterTypeCards(searchTerm, category) {
        $('.knet-type-card').each(function() {
            const $card = $(this);
            const cardCategory = $card.data('category');
            const cardLabel = $card.data('label').toLowerCase();
            const cardDesc = $card.find('p').text().toLowerCase();

            const matchesCategory = category === 'all' || cardCategory === category;
            const matchesSearch = !searchTerm || cardLabel.includes(searchTerm) || cardDesc.includes(searchTerm);

            if (matchesCategory && matchesSearch) {
                $card.show();
            } else {
                $card.hide();
            }
        });
    }

    // Type card click
    $('.knet-type-card').on('click', function() {
        const $card = $(this);

        // Check if pro required
        if ($card.hasClass('knet-type-pro') && !knetActionsData.isActivated) {
            alert(knetActionsData.i18n.proRequired);
            return;
        }

        // Check if addon required
        if ($card.hasClass('knet-type-addon') && $card.data('installed') !== true) {
            alert(knetActionsData.i18n.addonRequired);
            return;
        }

        // Select this card
        $('.knet-type-card').removeClass('selected');
        $card.addClass('selected');

        // Get card data
        const callback = $card.data('value');
        const label = $card.data('label');
        const iconHtml = $card.find('.knet-type-icon').html();
        const desc = $card.find('p').text();

        // Populate step 2
        $('#knet-callback-function').val(callback);
        $('#knet-selected-type-icon').html(iconHtml);
        $('#knet-selected-type-label').text(label);
        $('#knet-selected-type-desc').text(desc);

        // Go to step 2
        $('.knet-editor-step').removeClass('active');
        $('#knet-editor-step-2').addClass('active');
    });

    // ==========================================================================
    // Form Handling
    // ==========================================================================

    // Threshold slider
    $('#knet-action-threshold').on('input', function() {
        $('#knet-threshold-value').text($(this).val() + '%');
    });

    // Form submission
    $('#knet-action-form').on('submit', function(e) {
        e.preventDefault();

        const formMode = $('#knet-form-mode').val();
        const actionId = $('#knet-action-id').val();
        const callbackFunction = $('#knet-callback-function').val();
        const label = $('#knet-action-label').val().trim();
        const threshold = $('#knet-action-threshold').val();

        // Validation
        if (!callbackFunction) {
            alert('Please select an action type.');
            return;
        }

        if (!label) {
            alert('Please enter an action label.');
            $('#knet-action-label').focus();
            return;
        }

        // Check phrases exist (from tag input)
        if (currentPhrases.length === 0) {
            alert('Please add at least one trigger phrase.');
            $('#knet-phrase-input').focus();
            return;
        }

        // Get enabled bots
        const enabledBots = [];
        $('#knet-bot-selector input[type="checkbox"]:checked').each(function() {
            enabledBots.push($(this).val());
        });

        if (enabledBots.length === 0) {
            enabledBots.push('default');
        }

        showLoading();

        const data = {
            action: formMode === 'edit' ? 'knittnet_edit_intent_ajax' : 'knittnet_add_intent_ajax',
            intent_label: label,
            similarity_threshold: threshold,
            callback_function: callbackFunction,
            enabled_bots: enabledBots,
            security: formMode === 'edit' ? knetActionsData.editNonce : knetActionsData.addNonce
        };

        if (formMode === 'edit') {
            data.intent_id = actionId;
            // In edit mode, phrases are managed via individual AJAX calls already
            // Send empty phrases to skip legacy re-embedding
            data.phrases = '';
        } else {
            // Add mode: send individual phrases for per-phrase embedding
            const nonLegacyPhrases = currentPhrases.filter(p => !p.isLegacy).map(p => p.text);
            if (nonLegacyPhrases.length > 0) {
                data.individual_phrases = nonLegacyPhrases;
                data.phrases = '';
            } else {
                data.phrases = '';
            }
        }

        $.ajax({
            url: knetActionsData.ajaxUrl,
            type: 'POST',
            data: data,
            success: function(response) {
                hideLoading();

                if (response.success) {
                    // Reload list
                    loadActionList(currentPage, $('#knet-search-actions').val(), currentFilter);

                    // Reset and close editor
                    resetEditorPanel();
                    currentActionId = null;

                    // Show success message (could use a toast notification)
                    // For now, just reload
                } else {
                    alert(response.data || knetActionsData.i18n.error);
                }
            },
            error: function() {
                hideLoading();
                alert(knetActionsData.i18n.error);
            }
        });
    });

    // ==========================================================================
    // Toggle Action Status
    // ==========================================================================

    function toggleActionStatus(actionId, isEnabled) {
        // Optimistic UI update - update immediately before AJAX completes
        const $item = $('.knet-action-item[data-action-id="' + actionId + '"]');
        const $status = $item.find('.knet-action-status');
        const $toggle = $('#knet-action-enabled-toggle');

        // Immediately update UI
        $item.data('enabled', isEnabled ? '1' : '0');
        if (isEnabled) {
            $status.removeClass('disabled').addClass('enabled').text('Enabled');
            $item.removeClass('disabled');
        } else {
            $status.removeClass('enabled').addClass('disabled').text('Disabled');
            $item.addClass('disabled');
        }

        // Update view status immediately
        const statusText = isEnabled ? 'Enabled' : 'Disabled';
        const statusClass = isEnabled ? 'knet-status-enabled' : 'knet-status-disabled';
        $('#knet-view-status').text(statusText).removeClass('knet-status-enabled knet-status-disabled').addClass(statusClass);

        // Update stored data immediately
        const currentData = $('#knet-action-view').data('current-action');
        if (currentData) {
            currentData.enabled = isEnabled;
            $('#knet-action-view').data('current-action', currentData);
        }

        $.ajax({
            url: knetActionsData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'knittnet_toggle_action_status',
                action_id: actionId,
                enabled: isEnabled ? 1 : 0,
                security: knetActionsData.toggleNonce
            },
            success: function(response) {
                if (!response.success) {
                    // Revert optimistic update on failure
                    revertToggleUI(!isEnabled, $item, $status, $toggle);
                    alert(response.data || knetActionsData.i18n.error);
                }
            },
            error: function() {
                // Revert optimistic update on error
                revertToggleUI(!isEnabled, $item, $status, $toggle);
                alert(knetActionsData.i18n.error);
            }
        });
    }

    // Helper to revert toggle UI on error
    function revertToggleUI(revertEnabled, $item, $status, $toggle) {
        $toggle.prop('checked', revertEnabled);
        $item.data('enabled', revertEnabled ? '1' : '0');
        if (revertEnabled) {
            $status.removeClass('disabled').addClass('enabled').text('Enabled');
            $item.removeClass('disabled');
        } else {
            $status.removeClass('enabled').addClass('disabled').text('Disabled');
            $item.addClass('disabled');
        }
        const statusText = revertEnabled ? 'Enabled' : 'Disabled';
        const statusClass = revertEnabled ? 'knet-status-enabled' : 'knet-status-disabled';
        $('#knet-view-status').text(statusText).removeClass('knet-status-enabled knet-status-disabled').addClass(statusClass);
    }

    // ==========================================================================
    // Delete Modal
    // ==========================================================================

    function showDeleteModal(actionId) {
        $('#knet-delete-modal').show();
        $('#knet-confirm-delete').data('action-id', actionId);
    }

    $('#knet-delete-modal .knet-modal-close, #knet-cancel-delete').on('click', function() {
        $('#knet-delete-modal').hide();
    });

    $('#knet-confirm-delete').on('click', function() {
        const actionId = $(this).data('action-id');
        $('#knet-delete-modal').hide();
        deleteAction(actionId);
    });

    function deleteAction(actionId) {
        showLoading();

        $.ajax({
            url: knetActionsData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'knittnet_delete_intent_ajax',
                intent_id: actionId,
                security: knetActionsData.deleteNonce
            },
            success: function(response) {
                hideLoading();

                if (response.success) {
                    currentActionId = null;
                    resetEditorPanel();
                    loadActionList(currentPage, $('#knet-search-actions').val(), currentFilter);
                } else {
                    alert(response.data || knetActionsData.i18n.error);
                }
            },
            error: function() {
                hideLoading();
                alert(knetActionsData.i18n.error);
            }
        });
    }

    // ==========================================================================
    // Utility Functions
    // ==========================================================================

    function showLoading() {
        $('#knet-action-loading').show();
    }

    function hideLoading() {
        $('#knet-action-loading').hide();
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }
});
