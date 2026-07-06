/**
 * KnittNet Transcripts Page JavaScript - v3.0
 * Split-panel layout with chat list and conversation view
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

        // Reset scroll position of content area
        $('.knet-content').scrollTop(0);

        // Also update mobile nav if open
        $('.knet-mobile-nav-link').removeClass('active');
        $('.knet-mobile-nav-link[data-target="' + target + '"]').addClass('active');

        // Load transcripts when switching to all-chats
        if (target === 'all-chats' && !transcriptsLoaded) {
            loadChatList(1, '');
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

        // Reset scroll position of content area
        $('.knet-content').scrollTop(0);

        $('.knet-nav-link').removeClass('active');
        $('.knet-nav-link[data-target="' + target + '"]').addClass('active');

        $('.knet-mobile-menu').removeClass('open');
        $('.knet-mobile-overlay').removeClass('open');
    });

    // Quick action buttons
    $('.knet-quick-action-btn[data-action="view-chats"]').on('click', function() {
        $('.knet-nav-link[data-target="all-chats"]').trigger('click');
    });

    $('.knet-quick-action-btn[data-action="settings"]').on('click', function() {
        $('.knet-nav-link[data-target="notifications"]').trigger('click');
    });

    // ==========================================================================
    // Mobile Panel Management
    // ==========================================================================

    function isMobile() {
        return window.innerWidth <= 782;
    }

    function showMobileConversationPanel() {
        if (isMobile()) {
            $('.knet-chat-list-panel').addClass('panel-hidden');
            $('#knet-conversation-panel').addClass('panel-active');
            $('#knet-transcript-back-btn').show();
        }
    }

    function hideMobileConversationPanel() {
        if (isMobile()) {
            $('#knet-conversation-panel').removeClass('panel-active');
            $('.knet-chat-list-panel').removeClass('panel-hidden');
            $('#knet-transcript-back-btn').hide();
        }
    }

    // Mobile back button handler
    $('#knet-transcript-back-btn').on('click', function(e) {
        e.preventDefault();
        hideMobileConversationPanel();
        $('.knet-chat-item').removeClass('active');
        currentSessionId = null;
    });

    // Handle window resize
    $(window).on('resize', function() {
        if (!isMobile()) {
            // Reset panel states when switching to desktop
            $('.knet-chat-list-panel').removeClass('panel-hidden');
            $('#knet-conversation-panel').removeClass('panel-active');
            $('#knet-transcript-back-btn').hide();
        }
        updateMobileViewportHeight();
    });

    // Fix for mobile browser address bar - sets CSS custom property for accurate viewport height
    function updateMobileViewportHeight() {
        if (isMobile()) {
            // Use visualViewport if available (most reliable for mobile)
            const vh = window.visualViewport ? window.visualViewport.height : window.innerHeight;
            document.documentElement.style.setProperty('--knet-mobile-vh', vh + 'px');
        }
    }

    // Update on load and viewport changes
    updateMobileViewportHeight();
    if (window.visualViewport) {
        window.visualViewport.addEventListener('resize', updateMobileViewportHeight);
    }

    // ==========================================================================
    // Chat List - Split Panel
    // ==========================================================================

    let currentPage = 1;
    const perPage = 50;
    let totalPages = 1;
    let currentSessionId = null;
    let transcriptsLoaded = false;
    let selectedSessions = new Set();
    let currentSortOrder = 'desc'; // newest first

    // Load chat list on page load
    loadChatList(1, '');

    // Search functionality with debounce
    let searchTimeout;
    $('#knet-search-transcripts').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val().toLowerCase();

        searchTimeout = setTimeout(function() {
            currentPage = 1;
            loadChatList(currentPage, searchTerm);
        }, 300);
    });

    // Refresh button
    $('#knet-refresh-list').on('click', function() {
        const $btn = $(this);
        $btn.addClass('spinning');
        loadChatList(currentPage, $('#knet-search-transcripts').val());
        setTimeout(() => $btn.removeClass('spinning'), 500);
    });

    // ==========================================================================
    // Bulk Selection & Actions
    // ==========================================================================

    // Select all checkbox
    $('#knet-select-all').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.knet-chat-checkbox').prop('checked', isChecked);

        if (isChecked) {
            $('.knet-chat-item').each(function() {
                // Use .attr() — jQuery's .data() coerces "null"/"true"/numeric strings to JS types,
                // which causes fetch/delete of those sessions to silently fail.
                selectedSessions.add($(this).attr('data-session-id'));
                $(this).addClass('selected');
            });
            $('#knet-chat-list').addClass('selection-mode');
        } else {
            selectedSessions.clear();
            $('.knet-chat-item').removeClass('selected');
            $('#knet-chat-list').removeClass('selection-mode');
        }

        updateSelectionUI();
    });

    // Update selection UI
    function updateSelectionUI() {
        const count = selectedSessions.size;
        const $countEl = $('#knet-selected-count');
        const $deleteBtn = $('#knet-delete-selected');

        if (count > 0) {
            $countEl.text(count + ' selected').addClass('has-selection');
            $deleteBtn.prop('disabled', false);
            $('#knet-chat-list').addClass('selection-mode');
        } else {
            $countEl.removeClass('has-selection');
            $deleteBtn.prop('disabled', true);
            $('#knet-chat-list').removeClass('selection-mode');
        }

        // Update select all checkbox state
        const totalItems = $('.knet-chat-checkbox').length;
        const checkedItems = $('.knet-chat-checkbox:checked').length;
        $('#knet-select-all').prop('checked', totalItems > 0 && checkedItems === totalItems);
        $('#knet-select-all').prop('indeterminate', checkedItems > 0 && checkedItems < totalItems);
    }

    // Sort button — opens a small menu with 4 sort modes (plan-a5b006 adds rating sorts).
    $('#knet-sort-btn').on('click', function(e) {
        e.stopPropagation();
        const $btn = $(this);
        let $menu = $('#knet-sort-menu');
        if (!$menu.length) {
            $menu = $(
                '<div id="knet-sort-menu" class="knet-sort-menu" role="menu">' +
                '  <button type="button" class="knet-sort-option" data-sort="desc" role="menuitem">Newest first</button>' +
                '  <button type="button" class="knet-sort-option" data-sort="asc" role="menuitem">Oldest first</button>' +
                '  <button type="button" class="knet-sort-option" data-sort="rating_positive" role="menuitem"><span class="knet-sort-option-icon knet-rating-thumb-up"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H7"/><path d="M3 10h4"/></svg></span>Positive ratings first</button>' +
                '  <button type="button" class="knet-sort-option" data-sort="rating_negative" role="menuitem"><span class="knet-sort-option-icon knet-rating-thumb-down"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 14V2"/><path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H17"/><path d="M21 14h-4"/></svg></span>Negative ratings first</button>' +
                '</div>'
            );
            $('body').append($menu);
            $menu.on('click', '.knet-sort-option', function() {
                currentSortOrder = $(this).data('sort');
                $menu.find('.knet-sort-option').removeClass('active');
                $(this).addClass('active');
                $menu.hide();
                loadChatList(1, $('#knet-search-transcripts').val());
            });
            $(document).on('click.knetSortMenu', function(ev) {
                if (!$(ev.target).closest('#knet-sort-menu, #knet-sort-btn').length) {
                    $menu.hide();
                }
            });
        }
        $menu.find('.knet-sort-option').removeClass('active');
        $menu.find('[data-sort="' + currentSortOrder + '"]').addClass('active');
        const offset = $btn.offset();
        const btnHeight = $btn.outerHeight();
        $menu.css({
            position: 'absolute',
            top: (offset.top + btnHeight + 4) + 'px',
            left: offset.left + 'px'
        }).toggle();
    });

    // Render a rating badge for a session row. Uses inline SVG so the glyph
    // renders the same across OSes (emoji fonts vary). Tooltip surfaces the
    // optional feedback text.
    function renderRatingBadge(session) {
        const value = (session && typeof session.rating_value === 'number') ? session.rating_value : null;
        const feedback = (session && session.rating_feedback) ? session.rating_feedback : '';
        if (value === 1) {
            const title = feedback ? ('Visitor: ' + feedback) : 'Visitor rated this chat positively';
            return '<span class="knet-chat-rating knet-chat-rating-up" title="' + escapeHtml(title) + '" aria-label="' + escapeHtml(title) + '"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H7"/><path d="M3 10h4"/></svg></span>';
        }
        if (value === -1) {
            const title = feedback ? ('Visitor: ' + feedback) : 'Visitor rated this chat negatively';
            return '<span class="knet-chat-rating knet-chat-rating-down" title="' + escapeHtml(title) + '" aria-label="' + escapeHtml(title) + '"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 14V2"/><path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H17"/><path d="M21 14h-4"/></svg></span>';
        }
        return '<span class="knet-chat-rating knet-chat-rating-empty" title="No rating yet" aria-label="No rating yet">—</span>';
    }

    // Delete selected button — opens the shared confirm modal with the "also delete lead" checkbox.
    $('#knet-delete-selected').on('click', function() {
        const count = selectedSessions.size;
        if (count === 0) return;
        openTranscriptConfirm(Array.from(selectedSessions), count);
    });

    // Transcript delete confirm (shared by bulk + individual) ---------------------------
    let transcriptConfirmSessionIds = [];

    function openTranscriptConfirm(sessionIds, count) {
        transcriptConfirmSessionIds = sessionIds.slice();
        const n = count || sessionIds.length;
        $('#knet-transcript-confirm-title').text(n === 1 ? 'Delete conversation?' : 'Delete ' + n + ' conversations?');
        $('#knet-transcript-confirm-body').text(
            n === 1
                ? 'This removes the conversation and its messages.'
                : 'This removes ' + n + ' conversations and their messages.'
        );
        $('#knet-transcript-also-delete-lead').prop('checked', false);
        $('#knet-transcript-confirm').fadeIn(120);
    }

    function closeTranscriptConfirm() {
        $('#knet-transcript-confirm').fadeOut(120);
        transcriptConfirmSessionIds = [];
    }

    $(document).on('click', '[data-knet-transcript-close]', closeTranscriptConfirm);

    $('#knet-transcript-confirm-go').on('click', function() {
        const ids = transcriptConfirmSessionIds.slice();
        if (!ids.length) { closeTranscriptConfirm(); return; }
        const alsoDeleteLead = $('#knet-transcript-also-delete-lead').is(':checked');
        closeTranscriptConfirm();
        deleteMultipleSessions(ids, alsoDeleteLead);
    });

    // Delete multiple sessions
    function deleteMultipleSessions(sessionIds, alsoDeleteLead) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'knittnet_delete_chat_history',
                delete_session_ids: sessionIds,
                also_delete_lead: alsoDeleteLead ? '1' : '0',
                security: $('#knittnet_delete_chat_nonce').val()
            },
            success: function(response) {
                try {
                    const jsonResponse = typeof response === 'object' ? response : JSON.parse(response);

                    if (jsonResponse.success) {
                        // Clear selection
                        selectedSessions.clear();
                        $('#knet-select-all').prop('checked', false);
                        updateSelectionUI();

                        // If current conversation was deleted, reset panel
                        if (sessionIds.includes(currentSessionId)) {
                            currentSessionId = null;
                            $('#knet-conversation-content').hide();
                            $('#knet-conversation-empty').show();
                            $('#knet-details-drawer').hide();
                        }

                        // Reload list
                        loadChatList(currentPage, $('#knet-search-transcripts').val());

                        // The Leads tab shares this data (Chat deleted pill, nav badge,
                        // stats) — invalidate it so switching tabs re-fetches instead of
                        // showing stale "active lead" rows.
                        invalidateLeadsData();
                    } else if (jsonResponse.error) {
                        alert('Error: ' + jsonResponse.error);
                    }
                } catch (e) {
                    alert('An error occurred while processing the response.');
                }
            },
            error: function() {
                alert('An error occurred while deleting conversations.');
            }
        });
    }

    // Load chat list function
    function loadChatList(page, searchTerm) {
        const $container = $('#knet-chat-list');
        $container.html('<div class="knet-list-loading"><span class="spinner is-active"></span></div>');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'knittnet_fetch_chat_history',
                page: page,
                per_page: perPage,
                search: searchTerm,
                sort_order: currentSortOrder
            },
            success: function(response) {
                transcriptsLoaded = true;

                if (response.success && response.sessions && response.sessions.length > 0) {
                    renderChatList(response.sessions);
                    currentPage = response.page;
                    totalPages = response.total_pages;
                    updateChatCount(response.showing_start, response.showing_end, response.total_sessions);
                    renderPagination(response.page, response.total_pages, searchTerm);
                } else {
                    $container.html('<div class="knet-list-empty"><p>No chats found</p></div>');
                    updateChatCount(0, 0, 0);
                    $('#knet-pagination').html('');
                }
            },
            error: function() {
                $container.html('<div class="knet-list-empty"><p>Error loading chats</p></div>');
            }
        });
    }

    // Render chat list items
    function renderChatList(sessions) {
        const $container = $('#knet-chat-list');
        let html = '';

        sessions.forEach(function(session) {
            const isActive = session.session_id === currentSessionId ? ' active' : '';
            const isSelected = selectedSessions.has(session.session_id) ? ' selected' : '';
            const isChecked = selectedSessions.has(session.session_id) ? ' checked' : '';
            html += `
                <div class="knet-chat-item${isActive}${isSelected}" data-session-id="${escapeHtml(session.session_id)}">
                    <input type="checkbox" class="knet-chat-checkbox"${isChecked}>
                    <div class="knet-chat-avatar">
                        <span>${escapeHtml(session.initials)}</span>
                    </div>
                    <div class="knet-chat-info">
                        <div class="knet-chat-name">${escapeHtml(session.display_name)}</div>
                        <div class="knet-chat-preview">${escapeHtml(session.preview)}</div>
                    </div>
                    <div class="knet-chat-meta">
                        ${renderRatingBadge(session)}
                        <span class="knet-chat-time">${escapeHtml(session.time_display)}</span>
                        <span class="knet-chat-count">${session.message_count}</span>
                    </div>
                </div>
            `;
        });

        $container.html(html);

        // Attach checkbox handlers
        $('.knet-chat-checkbox').on('click', function(e) {
            e.stopPropagation(); // Prevent triggering chat item click
            const $item = $(this).closest('.knet-chat-item');
            const sessionId = $item.attr('data-session-id');

            if ($(this).is(':checked')) {
                selectedSessions.add(sessionId);
                $item.addClass('selected');
            } else {
                selectedSessions.delete(sessionId);
                $item.removeClass('selected');
            }

            updateSelectionUI();
        });

        // Attach click handlers for selecting chat
        $('.knet-chat-item').on('click', function(e) {
            // Don't trigger if clicking on checkbox
            if ($(e.target).is('.knet-chat-checkbox')) return;

            const sessionId = $(this).attr('data-session-id');
            selectChat(sessionId);

            // Update active state
            $('.knet-chat-item').removeClass('active');
            $(this).addClass('active');

            // Show conversation panel on mobile
            showMobileConversationPanel();
        });

        // Update selection UI after render
        updateSelectionUI();
    }

    // Update chat count display
    function updateChatCount(start, end, total) {
        if (total === 0) {
            $('#knet-chat-count').text('0 chats');
        } else {
            $('#knet-chat-count').text(`${start}-${end} / ${total} chats`);
        }
    }

    // Render pagination
    function renderPagination(currentPage, totalPages, searchTerm) {
        const $container = $('#knet-pagination');

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
            loadChatList(pageNum, searchTerm);
        });
    }

    // ==========================================================================
    // Conversation Panel
    // ==========================================================================

    // Select and load a chat conversation
    function selectChat(sessionId) {
        currentSessionId = sessionId;

        // Reset translation state when selecting new chat
        if (typeof resetTranslationState === 'function') {
            resetTranslationState();
        }

        // Show loading in conversation panel
        $('#knet-conversation-empty').hide();
        $('#knet-conversation-content').show();
        $('#knet-messages-area').html('<div class="knet-messages-loading"><span class="spinner is-active"></span> Loading conversation...</div>');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'knittnet_fetch_conversation',
                session_id: sessionId
            },
            success: function(response) {
                if (response.success) {
                    renderConversation(response);
                    // Load saved translation after rendering
                    if (typeof loadSavedTranslation === 'function') {
                        setTimeout(function() {
                            loadSavedTranslation(sessionId);
                        }, 100);
                    }
                } else {
                    $('#knet-messages-area').html('<div class="knet-messages-error">Failed to load conversation</div>');
                }
            },
            error: function() {
                $('#knet-messages-area').html('<div class="knet-messages-error">Error loading conversation</div>');
            }
        });
    }

    // Render conversation content
    function renderConversation(data) {
        // Update header
        $('#knet-user-avatar span').text(data.user.initials);
        $('#knet-user-name').text(data.user.name);
        $('#knet-user-meta').text(data.user.sub);

        // Update details drawer
        $('#knet-detail-messages').text(data.message_count);
        $('#knet-detail-started').text(data.started);

        if (data.page.url) {
            $('#knet-detail-page').html(`<a href="${escapeHtml(data.page.url)}" target="_blank">${escapeHtml(data.page.title || data.page.url)}</a>`);
        } else {
            $('#knet-detail-page').text('-');
        }

        if (data.user.email) {
            $('#knet-detail-email').text(data.user.email);
            $('#knet-detail-email-row').show();
        } else {
            $('#knet-detail-email-row').hide();
        }

        // Feedback row — .text() (not .html()) for XSS-safe display of user-submitted text.
        // rating_feedback is already sanitize_text_field()'d + mb_substr(200) on save.
        var feedback = (data && data.rating_feedback) ? String(data.rating_feedback).trim() : '';
        if (feedback) {
            $('#knet-detail-feedback').text(feedback);
            $('#knet-detail-feedback-row').show();
        } else {
            $('#knet-detail-feedback-row').hide();
        }

        // Clicked links
        if (data.clicked_urls && data.clicked_urls.length > 0) {
            let linksHtml = '';
            data.clicked_urls.forEach(function(url) {
                linksHtml += `<a href="${escapeHtml(url)}" target="_blank" class="knet-clicked-link">${escapeHtml(url)}</a>`;
            });
            $('#knet-clicked-links').html(linksHtml);
            $('#knet-clicked-section').show();
        } else {
            $('#knet-clicked-section').hide();
        }

        // Render messages
        let messagesHtml = '';
        data.messages.forEach(function(msg) {
            if (msg.is_user) {
                messagesHtml += `
                    <div class="knet-message knet-message-user" data-message-id="${msg.id}">
                        <div class="knet-message-row">
                            <div class="knet-message-bubble">
                                ${msg.content}
                            </div>
                        </div>
                        <div class="knet-message-time">${escapeHtml(msg.timestamp)}</div>
                    </div>
                `;
            } else {
                const ragLink = msg.has_rag ? `<a href="#" class="knet-rag-link" data-message-id="${msg.id}">Sources</a>` : '';
                messagesHtml += `
                    <div class="knet-message knet-message-bot" data-message-id="${msg.id}">
                        <div class="knet-message-header">
                            <span class="knet-bot-label">AI Assistant</span>
                            ${ragLink}
                        </div>
                        <div class="knet-message-row">
                            <div class="knet-message-bubble">
                                ${msg.content}
                            </div>
                        </div>
                        <div class="knet-message-time">${escapeHtml(msg.timestamp)}</div>
                    </div>
                `;
            }
        });

        $('#knet-messages-area').html(messagesHtml);

        // Scroll to bottom
        const $area = $('#knet-messages-area');
        $area.scrollTop($area[0].scrollHeight);

        // Attach RAG link handlers
        $('.knet-rag-link').on('click', function(e) {
            e.preventDefault();
            const messageId = $(this).data('message-id');
            if (messageId) {
                openRagContextModal(messageId);
            }
        });
    }

    // Toggle details drawer
    $('#knet-toggle-details').on('click', function() {
        const $drawer = $('#knet-details-drawer');
        const $btn = $(this);

        if ($drawer.is(':visible')) {
            $drawer.slideUp(200);
            $btn.removeClass('active');
        } else {
            $drawer.slideDown(200);
            $btn.addClass('active');
        }
    });

    // Delete current chat — opens the shared confirm modal.
    $('#knet-delete-current').on('click', function() {
        if (!currentSessionId) return;
        openTranscriptConfirm([currentSessionId], 1);
    });

    // Delete session function
    function deleteSession(sessionId, alsoDeleteLead) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'knittnet_delete_chat_history',
                delete_session_ids: [sessionId],
                also_delete_lead: alsoDeleteLead ? '1' : '0',
                security: $('#knittnet_delete_chat_nonce').val()
            },
            success: function(response) {
                try {
                    const jsonResponse = typeof response === 'object' ? response : JSON.parse(response);

                    if (jsonResponse.success) {
                        // Reset conversation panel
                        currentSessionId = null;
                        $('#knet-conversation-content').hide();
                        $('#knet-conversation-empty').show();
                        $('#knet-details-drawer').hide();

                        // Reload list
                        loadChatList(currentPage, $('#knet-search-transcripts').val());
                    } else if (jsonResponse.error) {
                        alert('Error: ' + jsonResponse.error);
                    }
                } catch (e) {
                    alert('An error occurred while processing the response.');
                }
            },
            error: function() {
                alert('An error occurred while deleting the conversation.');
            }
        });
    }

    // ==========================================================================
    // Export Functionality
    // ==========================================================================

    $('#knet-export-btn, #knet-export-current').on('click', function() {
        const $button = $(this);
        $button.prop('disabled', true).addClass('loading');

        const $form = $('<form>', {
            method: 'post',
            action: ajaxurl
        });

        $form.append($('<input>', {
            type: 'hidden',
            name: 'action',
            value: 'knittnet_export_transcripts'
        }));

        $form.append($('<input>', {
            type: 'hidden',
            name: 'security',
            value: knittnetAdmin.export_nonce
        }));

        $form.appendTo('body').submit();

        setTimeout(function() {
            $button.prop('disabled', false).removeClass('loading');
        }, 2000);
    });

    // ==========================================================================
    // Translation Functionality
    // ==========================================================================

    // Store original messages for reverting
    let originalMessages = null;
    let isTranslated = false;
    let currentTranslationLang = null;

    // Load saved language preference from localStorage
    const savedLang = localStorage.getItem('knet_translate_lang');
    if (savedLang) {
        $('#knet-translate-lang').val(savedLang);
    }

    // Save language preference when changed
    $('#knet-translate-lang').on('change', function() {
        localStorage.setItem('knet_translate_lang', $(this).val());
    });

    // Apply translations to messages
    function applyTranslations(translations) {
        // Store original messages if not already stored
        if (!originalMessages) {
            originalMessages = [];
            $('#knet-messages-area .knet-message-bubble').each(function() {
                originalMessages.push($(this).html());
            });
        }

        // Apply translations
        translations.forEach(function(item) {
            const $bubble = $('#knet-messages-area .knet-message-bubble').eq(item.index);
            if ($bubble.length) {
                $bubble.html(item.translated);
                $bubble.addClass('translated');
            }
        });

        isTranslated = true;
        $('#knet-show-original-btn').show();
    }

    // Load saved translation for current session
    function loadSavedTranslation(sessionId) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'knittnet_get_transcript_translation',
                session_id: sessionId
            },
            success: function(response) {
                if (response.success && response.has_translation) {
                    currentTranslationLang = response.language;
                    applyTranslations(response.translations);
                    // Update language selector to show saved language
                    $('#knet-translate-lang').val(response.language);
                }
            }
        });
    }

    // Translate button click handler
    $('#knet-translate-btn').on('click', function() {
        if (!currentSessionId) return;

        const $btn = $(this);
        const targetLang = $('#knet-translate-lang').val();

        // Disable button and show loading state
        $btn.prop('disabled', true);
        $btn.find('.knet-translate-text').text('Translating...');
        $btn.find('svg').addClass('knet-translate-spinner');

        // Store original messages before translation
        if (!originalMessages) {
            originalMessages = [];
            $('#knet-messages-area .knet-message-bubble').each(function() {
                originalMessages.push($(this).html());
            });
        }

        // If already translated, restore originals first before re-translating
        if (isTranslated) {
            $('#knet-messages-area .knet-message-bubble').each(function(index) {
                if (originalMessages[index]) {
                    $(this).html(originalMessages[index]);
                    $(this).removeClass('translated');
                }
            });
        }

        // Collect all message content (from originals)
        const messages = [];
        originalMessages.forEach(function(html, index) {
            // Create temp element to get text content
            const $temp = $('<div>').html(html);
            messages.push({
                index: index,
                content: $temp.text().trim()
            });
        });

        // Send translation request
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'knittnet_translate_messages',
                session_id: currentSessionId,
                target_lang: targetLang,
                messages: JSON.stringify(messages),
                security: knittnetAdmin.translate_nonce || ''
            },
            success: function(response) {
                if (response.success && response.translations) {
                    currentTranslationLang = response.language;
                    applyTranslations(response.translations);
                    $btn.find('.knet-translate-text').text('Translate');
                } else {
                    alert(response.error || 'Translation failed. Please try again.');
                    $btn.find('.knet-translate-text').text('Translate');
                }
            },
            error: function() {
                alert('Translation request failed. Please try again.');
                $btn.find('.knet-translate-text').text('Translate');
            },
            complete: function() {
                $btn.prop('disabled', false);
                $btn.find('svg').removeClass('knet-translate-spinner');
            }
        });
    });

    // Show original button click handler
    $('#knet-show-original-btn').on('click', function() {
        if (!originalMessages) return;

        // Restore original messages
        $('#knet-messages-area .knet-message-bubble').each(function(index) {
            if (originalMessages[index]) {
                $(this).html(originalMessages[index]);
                $(this).removeClass('translated');
            }
        });

        isTranslated = false;
        $(this).hide();
    });

    // Reset translation state (called when selecting new chat)
    function resetTranslationState() {
        originalMessages = null;
        isTranslated = false;
        currentTranslationLang = null;
        $('#knet-show-original-btn').hide();
    }

    // Make functions available to selectChat
    window.resetTranslationState = resetTranslationState;
    window.loadSavedTranslation = loadSavedTranslation;

    // ==========================================================================
    // RAG Context Modal (Sources & Actions Tabs)
    // ==========================================================================

    function openRagContextModal(messageId) {
        const $modal = $('#knet-rag-modal');
        const $loading = $modal.find('.knet-rag-loading');
        const $sourcesContent = $modal.find('.knet-rag-content');
        const $actionsContent = $modal.find('.knet-actions-content');

        // Reset to Sources tab
        $modal.find('.knet-context-tab').removeClass('active');
        $modal.find('.knet-context-tab[data-tab="sources"]').addClass('active');
        $('#knet-tab-sources').show();
        $('#knet-tab-actions').hide();

        // Reset badge counts
        $('#knet-sources-count, #knet-actions-count').hide().text('0');

        $modal.fadeIn(200);
        $loading.show();
        $sourcesContent.html('');
        $actionsContent.html('');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'knittnet_get_rag_context',
                message_id: messageId
            },
            success: function(response) {
                $loading.hide();

                if (response.success && response.data) {
                    // Render sources tab
                    renderRagContext(response.data, $sourcesContent);

                    // Render actions tab
                    renderActionsContext(response.data, $actionsContent);

                    // Update badge counts
                    const sourcesCount = response.data.top_matches ? response.data.top_matches.length : 0;
                    const actionsCount = response.data.action_analysis ? response.data.action_analysis.length : 0;

                    if (sourcesCount > 0) {
                        $('#knet-sources-count').text(sourcesCount).show();
                    }
                    if (actionsCount > 0) {
                        $('#knet-actions-count').text(actionsCount).show();
                    }
                } else {
                    $sourcesContent.html('<div class="knet-rag-error">Unable to load document context.</div>');
                    $actionsContent.html('<div class="knet-rag-error">No action data available.</div>');
                }
            },
            error: function() {
                $loading.hide();
                $sourcesContent.html('<div class="knet-rag-error">Error loading context. Please try again.</div>');
                $actionsContent.html('<div class="knet-rag-error">Error loading context. Please try again.</div>');
            }
        });
    }

    // Tab switching
    $(document).on('click', '.knet-context-tab', function() {
        const $tab = $(this);
        const tabName = $tab.data('tab');

        // Update active tab
        $('.knet-context-tab').removeClass('active');
        $tab.addClass('active');

        // Show/hide content
        $('.knet-tab-content').hide();
        $('#knet-tab-' + tabName).show();
    });

    function renderRagContext(data, $container) {
        let html = '';

        // Check if we have any source data
        if (!data.top_matches || data.top_matches.length === 0) {
            html += '<div class="knet-no-results"><p>No document matches found for this response.</p></div>';
            $container.html(html);
            return;
        }

        html += '<div class="knet-rag-summary">';
        html += '<div class="knet-rag-summary-item"><span class="knet-rag-label">Knowledge Base:</span> <span class="knet-rag-value">' + escapeHtml(data.knowledge_base_type || 'WordPress Database') + '</span></div>';
        html += '<div class="knet-rag-summary-item"><span class="knet-rag-label">Similarity Threshold:</span> <span class="knet-rag-value">' + Math.round((data.similarity_threshold || 0.35) * 100) + '%</span></div>';
        html += '<div class="knet-rag-summary-item"><span class="knet-rag-label">Documents Checked:</span> <span class="knet-rag-value">' + (data.total_documents_checked || 0) + '</span></div>';
        html += '</div>';

        const groupedByUrl = {};

        data.top_matches.forEach(function(match) {
            const url = match.source_display || 'Unknown';
            if (!groupedByUrl[url]) {
                groupedByUrl[url] = {
                    url: url,
                    isUrl: url.startsWith('http'),
                    bestScore: 0,
                    usedForContext: false,
                    matchedChunks: []
                };
            }

            if (match.similarity_percentage > groupedByUrl[url].bestScore) {
                groupedByUrl[url].bestScore = match.similarity_percentage;
            }

            if (match.used_for_context) {
                groupedByUrl[url].usedForContext = true;
            }

            groupedByUrl[url].matchedChunks.push({
                chunkIndex: match.chunk_index,
                score: match.similarity_percentage,
                usedForContext: match.used_for_context
            });
        });

        const urlGroups = Object.values(groupedByUrl).sort((a, b) => b.bestScore - a.bestScore);
        const usedUrlCount = data.sources_used > 0 ? data.sources_used : urlGroups.filter(g => g.usedForContext).length;
        const chunksInfo = data.total_chunks_used > 0 ? data.total_chunks_used + ' chunks sent to AI' : '';

        html += '<div class="knet-rag-matches">';
        html += '<h3>Retrieved Documents</h3>';
        html += '<p style="color: var(--knet-text-secondary); font-size: 13px; margin-bottom: 16px;">' + usedUrlCount + ' source' + (usedUrlCount === 1 ? '' : 's') + ' used for response' + (chunksInfo ? ' &middot; ' + chunksInfo : '') + '</p>';

        urlGroups.forEach(function(group) {
            const cardClass = group.usedForContext ? 'knet-rag-match-used' : 'knet-rag-match-below';
            const statusIcon = group.usedForContext ? '&#10003;' : '&#10007;';
            const statusLabel = group.usedForContext ? 'Used' : 'Not Used';

            html += '<div class="knet-rag-match-card ' + cardClass + '">';
            html += '<div class="knet-rag-match-header">';
            html += '<span class="knet-rag-match-score">' + group.bestScore + '%</span>';

            if (group.matchedChunks.length > 1) {
                html += '<span class="knet-rag-chunk-badge">' + group.matchedChunks.length + ' chunks</span>';
            }

            html += '<span class="knet-rag-match-status ' + (group.usedForContext ? 'status-used' : 'status-below') + '">' + statusIcon + ' ' + statusLabel + '</span>';
            html += '</div>';

            html += '<div class="knet-rag-match-source">';
            if (group.isUrl) {
                html += '<a href="' + escapeHtml(group.url) + '" target="_blank">' + escapeHtml(group.url) + '</a>';
            } else {
                html += escapeHtml(group.url);
            }
            html += '</div>';
            html += '</div>';
        });

        html += '</div>';
        $container.html(html);
    }

    function renderActionsContext(data, $container) {
        let html = '';

        // Check if we have action analysis data
        if (!data.action_analysis || data.action_analysis.length === 0) {
            html += '<div class="knet-no-results"><p>No action analysis available for this message.</p><p style="color: var(--knet-text-secondary); font-size: 13px; margin-top: 8px;">Actions are only evaluated when enabled in your bot configuration.</p></div>';
            $container.html(html);
            return;
        }

        const actions = data.action_analysis;
        const triggeredAction = actions.find(a => a.triggered);
        const actionsAboveThreshold = actions.filter(a => a.above_threshold).length;

        // Summary section
        html += '<div class="knet-rag-summary">';
        html += '<div class="knet-rag-summary-item"><span class="knet-rag-label">Actions Evaluated:</span> <span class="knet-rag-value">' + actions.length + '</span></div>';
        html += '<div class="knet-rag-summary-item"><span class="knet-rag-label">Above Threshold:</span> <span class="knet-rag-value">' + actionsAboveThreshold + '</span></div>';
        if (triggeredAction) {
            html += '<div class="knet-rag-summary-item"><span class="knet-rag-label">Triggered:</span> <span class="knet-rag-value" style="color: #10b981; font-weight: 600;">' + escapeHtml(triggeredAction.intent_label) + '</span></div>';
        }
        html += '</div>';

        // Actions list
        html += '<div class="knet-rag-matches">';
        html += '<h3>Action Scores</h3>';
        html += '<p style="color: var(--knet-text-secondary); font-size: 13px; margin-bottom: 16px;">Showing all evaluated actions sorted by similarity score</p>';

        actions.forEach(function(action) {
            let cardClass = 'knet-rag-match-below';
            let statusIcon = '&#10007;';
            let statusLabel = 'Below Threshold';

            if (action.triggered) {
                cardClass = 'knet-action-triggered';
                statusIcon = '&#9889;';
                statusLabel = 'Triggered';
            } else if (action.above_threshold) {
                cardClass = 'knet-rag-match-used';
                statusIcon = '&#10003;';
                statusLabel = 'Above Threshold';
            }

            html += '<div class="knet-rag-match-card ' + cardClass + '">';
            html += '<div class="knet-rag-match-header">';
            html += '<span class="knet-rag-match-score">' + action.similarity_percentage + '%</span>';
            html += '<span class="knet-action-threshold-badge">Threshold: ' + action.threshold_percentage + '%</span>';
            html += '<span class="knet-rag-match-status ' + (action.triggered ? 'status-triggered' : (action.above_threshold ? 'status-used' : 'status-below')) + '">' + statusIcon + ' ' + statusLabel + '</span>';
            html += '</div>';

            html += '<div class="knet-action-details">';
            html += '<div class="knet-action-label">' + escapeHtml(action.intent_label) + '</div>';
            html += '<div class="knet-action-callback"><span class="knet-action-callback-label">Callback:</span> ' + escapeHtml(action.callback_function) + '</div>';
            html += '</div>';

            // Score bar visualization
            const scoreBarWidth = Math.min(action.similarity_percentage, 100);
            const thresholdPos = Math.min(action.threshold_percentage, 100);
            html += '<div class="knet-action-score-bar">';
            html += '<div class="knet-action-score-fill" style="width: ' + scoreBarWidth + '%;"></div>';
            html += '<div class="knet-action-threshold-marker" style="left: ' + thresholdPos + '%;"></div>';
            html += '</div>';

            html += '</div>';
        });

        html += '</div>';
        $container.html(html);
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Close RAG modal
    $('.knet-modal-close').on('click', function() {
        $(this).closest('.knet-modal-overlay').fadeOut(200);
    });

    $('.knet-modal-overlay').on('click', function(e) {
        if ($(e.target).is('.knet-modal-overlay')) {
            $(this).fadeOut(200);
        }
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('.knet-modal-overlay').fadeOut(200);
        }
    });

    // ==========================================================================
    // Activity Chart
    // ==========================================================================

    // Simple chart implementation (no external dependencies)
    class SimpleChart {
        constructor(canvas, config) {
            this.canvas = canvas;
            this.ctx = canvas.getContext('2d');
            this.config = config;
            this.padding = { top: 20, right: 20, bottom: 40, left: 50 };
            this.render();
        }

        destroy() {
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        }

        render() {
            const dpr = window.devicePixelRatio || 1;
            const rect = this.canvas.getBoundingClientRect();

            this.canvas.width = rect.width * dpr;
            this.canvas.height = rect.height * dpr;
            this.ctx.scale(dpr, dpr);

            this.canvas.style.width = rect.width + 'px';
            this.canvas.style.height = rect.height + 'px';

            const width = rect.width - this.padding.left - this.padding.right;
            const height = rect.height - this.padding.top - this.padding.bottom;

            // Find max value
            let maxValue = 0;
            this.config.datasets.forEach(dataset => {
                const max = Math.max(...dataset.data);
                if (max > maxValue) maxValue = max;
            });

            // Add some padding to max value
            maxValue = Math.ceil(maxValue * 1.1);
            if (maxValue === 0) maxValue = 10;

            // Draw grid lines
            this.ctx.strokeStyle = '#e5e7eb';
            this.ctx.lineWidth = 1;
            const gridLines = 5;

            for (let i = 0; i <= gridLines; i++) {
                const y = this.padding.top + (height / gridLines) * i;
                this.ctx.beginPath();
                this.ctx.moveTo(this.padding.left, y);
                this.ctx.lineTo(this.padding.left + width, y);
                this.ctx.stroke();

                // Draw y-axis labels
                const value = maxValue - (maxValue / gridLines) * i;
                this.ctx.fillStyle = '#6b7280';
                this.ctx.font = '12px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
                this.ctx.textAlign = 'right';
                this.ctx.fillText(Math.round(value), this.padding.left - 10, y + 4);
            }

            // Draw datasets
            this.config.datasets.forEach(dataset => {
                const points = [];
                const xStep = width / (this.config.labels.length - 1 || 1);

                dataset.data.forEach((value, index) => {
                    const x = this.padding.left + (xStep * index);
                    const y = this.padding.top + height - (value / maxValue * height);
                    points.push({ x, y, value });
                });

                // Draw filled area
                if (dataset.fill && dataset.backgroundColor) {
                    this.ctx.fillStyle = dataset.backgroundColor;
                    this.ctx.beginPath();
                    this.ctx.moveTo(points[0].x, this.padding.top + height);
                    points.forEach(point => {
                        this.ctx.lineTo(point.x, point.y);
                    });
                    this.ctx.lineTo(points[points.length - 1].x, this.padding.top + height);
                    this.ctx.closePath();
                    this.ctx.fill();
                }

                // Draw line
                this.ctx.strokeStyle = dataset.borderColor;
                this.ctx.lineWidth = 3;
                this.ctx.lineCap = 'round';
                this.ctx.lineJoin = 'round';

                this.ctx.beginPath();
                points.forEach((point, index) => {
                    if (index === 0) {
                        this.ctx.moveTo(point.x, point.y);
                    } else {
                        this.ctx.lineTo(point.x, point.y);
                    }
                });
                this.ctx.stroke();

                // Draw points
                points.forEach(point => {
                    this.ctx.fillStyle = '#ffffff';
                    this.ctx.beginPath();
                    this.ctx.arc(point.x, point.y, 5, 0, Math.PI * 2);
                    this.ctx.fill();
                    this.ctx.strokeStyle = dataset.borderColor;
                    this.ctx.lineWidth = 2;
                    this.ctx.stroke();
                });
            });

            // Draw x-axis labels
            const xStep = width / (this.config.labels.length - 1 || 1);
            this.ctx.fillStyle = '#6b7280';
            this.ctx.font = '12px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
            this.ctx.textAlign = 'center';

            this.config.labels.forEach((label, index) => {
                const x = this.padding.left + (xStep * index);
                this.ctx.fillText(label, x, this.padding.top + height + 20);
            });
        }
    }

    // Initialize activity chart
    function initActivityChart() {
        console.log('[KnittNet Chart] initActivityChart called');

        const canvas = document.getElementById('knittnet-activity-chart');
        console.log('[KnittNet Chart] Canvas element:', canvas);

        if (!canvas) {
            console.log('[KnittNet Chart] Canvas not found, aborting');
            return;
        }

        console.log('[KnittNet Chart] knittnetChartData exists:', typeof knittnetChartData !== 'undefined');
        if (typeof knittnetChartData === 'undefined') {
            console.log('[KnittNet Chart] knittnetChartData is undefined, aborting');
            return;
        }

        console.log('[KnittNet Chart] Raw knittnetChartData:', knittnetChartData);

        // Check if chart already exists and destroy it
        if (canvas.chartInstance) {
            canvas.chartInstance.destroy();
        }

        const ctx = canvas.getContext('2d');
        console.log('[KnittNet Chart] Canvas context:', ctx);
        console.log('[KnittNet Chart] Canvas dimensions:', canvas.getBoundingClientRect());

        // Create gradient for chats line
        const chatsGradient = ctx.createLinearGradient(0, 0, 0, 300);
        chatsGradient.addColorStop(0, 'rgba(102, 126, 234, 0.3)');
        chatsGradient.addColorStop(1, 'rgba(102, 126, 234, 0.05)');

        // Create gradient for messages line
        const messagesGradient = ctx.createLinearGradient(0, 0, 0, 300);
        messagesGradient.addColorStop(0, 'rgba(118, 75, 162, 0.3)');
        messagesGradient.addColorStop(1, 'rgba(118, 75, 162, 0.05)');

        // Convert wp_localize_script objects to arrays (WordPress converts indexed arrays to objects)
        const labels = Object.values(knittnetChartData.labels);
        const chatsData = Object.values(knittnetChartData.chats).map(Number);
        const messagesData = Object.values(knittnetChartData.messages).map(Number);

        console.log('[KnittNet Chart] Processed labels:', labels);
        console.log('[KnittNet Chart] Processed chatsData:', chatsData);
        console.log('[KnittNet Chart] Processed messagesData:', messagesData);

        // Create chart
        try {
            canvas.chartInstance = new SimpleChart(canvas, {
                labels: labels,
                datasets: [
                    {
                        label: 'Chats',
                        data: chatsData,
                        borderColor: '#667eea',
                        backgroundColor: chatsGradient,
                        fill: true
                    },
                    {
                        label: 'Messages',
                        data: messagesData,
                        borderColor: '#764ba2',
                        backgroundColor: messagesGradient,
                        fill: true
                    }
                ]
            });
            console.log('[KnittNet Chart] Chart created successfully');
        } catch (error) {
            console.error('[KnittNet Chart] Error creating chart:', error);
        }
    }

    // Initialize chart on page load (dashboard is shown by default)
    setTimeout(function() {
        initActivityChart();
    }, 100);

    // Reinitialize chart on window resize
    let resizeTimeout;
    $(window).on('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            initActivityChart();
        }, 250);
    });

    // ==========================================================================
    // Leads Tab
    // ==========================================================================

    const leadsState = {
        loaded: false,
        page: 1,
        perPage: 25,
        totalPages: 1,
        totalCount: 0,
        selected: new Set(),
        filters: {
            search: '',
            dateRange: 'all',
            status: 'all',
            pageUrl: '',
            pageTitle: ''
        },
        pendingDelete: [],
        leadsRows: [] // last-rendered rows for quick lookup
    };

    function $leads() { return $('#leads'); }

    // Called after a transcript delete from the All Chats side. Marks the Leads tab
    // data stale so the next tab visit re-fetches, and refreshes immediately if the
    // Leads tab happens to already be visible.
    function invalidateLeadsData() {
        leadsState.loaded = false;
        if ($('#leads').hasClass('active')) {
            loadLeads(1);
        }
    }

    function escapeHtmlLeads(s) {
        if (s === null || typeof s === 'undefined') return '';
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function leadsFiltersActive() {
        const f = leadsState.filters;
        return f.search !== '' || f.dateRange !== 'all' || f.status !== 'all' || f.pageUrl !== '';
    }

    function updateClearFiltersButton() {
        if (leadsFiltersActive()) {
            $('#knet-leads-clear-filters').show();
        } else {
            $('#knet-leads-clear-filters').hide();
        }
    }

    function setPageFilterChip(url, title) {
        leadsState.filters.pageUrl = url || '';
        leadsState.filters.pageTitle = title || url || '';
        const $chip = $('#knet-leads-active-page-filter');
        if (url) {
            $chip.find('.knet-leads-page-chip-label').text('Page: ' + (title || url));
            $chip.show();
        } else {
            $chip.hide();
        }
        updateClearFiltersButton();
    }

    function loadLeads(page) {
        if (typeof page === 'number') leadsState.page = page;

        const $tbody = $('#knet-leads-tbody');
        $tbody.html('<tr><td colspan="6" class="knet-leads-loading"><span class="spinner is-active"></span></td></tr>');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'knittnet_fetch_leads',
                page: leadsState.page,
                per_page: leadsState.perPage,
                search: leadsState.filters.search,
                date_range: leadsState.filters.dateRange,
                status: leadsState.filters.status,
                page_url: leadsState.filters.pageUrl
            },
            success: function(response) {
                leadsState.loaded = true;
                if (!response || !response.success) {
                    $tbody.html('<tr><td colspan="7" class="knet-leads-empty">Error loading leads</td></tr>');
                    return;
                }
                leadsState.totalPages = response.total_pages || 1;
                leadsState.totalCount = response.total_count || 0;
                leadsState.leadsRows = response.leads || [];

                renderLeadsStats(response.stats || {});
                renderLeadsTopPages(response.top_pages || []);
                renderLeadsTable(response.leads || []);
                renderLeadsCount(response.showing_start, response.showing_end, response.total_count);
                renderLeadsPagination(response.page, response.total_pages);

                // Nav badge
                if (response.stats && typeof response.stats.total_leads === 'number') {
                    const $badge = $('#knet-leads-nav-badge');
                    if (response.stats.total_leads > 0) {
                        $badge.text(response.stats.total_leads).show();
                    } else {
                        $badge.hide();
                    }
                }
            },
            error: function() {
                $tbody.html('<tr><td colspan="7" class="knet-leads-empty">Error loading leads</td></tr>');
            }
        });
    }

    function renderLeadsStats(stats) {
        $('#knet-leads-stat-total').text(stats.total_leads || 0);
        $('#knet-leads-stat-new').text(stats.new_this_week || 0);
        $('#knet-leads-stat-avg').text(stats.avg_convos || 0);
        const pct = stats.orphan_pct || 0;
        $('#knet-leads-stat-orphan').text(pct + '%');
        const orphanCount = stats.orphan_count || 0;
        $('#knet-leads-stat-orphan-sub').text(orphanCount + (orphanCount === 1 ? ' lead captured but never chatted' : ' leads captured but never chatted'));
    }

    function renderLeadsTopPages(pages) {
        const $wrap = $('#knet-leads-toppages-list');
        if (!pages || pages.length === 0) {
            $wrap.html('<div class="knet-leads-empty-mini">No page data yet.</div>');
            return;
        }
        let html = '';
        pages.forEach(function(p) {
            const isActive = leadsState.filters.pageUrl === p.url ? ' is-active' : '';
            html += `
                <button type="button" class="knet-leads-toppage-row${isActive}" data-url="${escapeHtmlLeads(p.url)}" data-title="${escapeHtmlLeads(p.title)}">
                    <span class="knet-leads-toppage-title">${escapeHtmlLeads(p.title || p.url)}</span>
                    <span class="knet-leads-toppage-count">${p.lead_count}</span>
                </button>
            `;
        });
        $wrap.html(html);
    }

    function renderLeadsTable(rows) {
        const $tbody = $('#knet-leads-tbody');
        if (!rows || rows.length === 0) {
            $tbody.html(`
                <tr><td colspan="6" class="knet-leads-empty">
                    <div class="knet-leads-empty-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        <p>No leads match the current filters.</p>
                    </div>
                </td></tr>
            `);
            return;
        }

        let html = '';
        rows.forEach(function(r) {
            const emailKey = (r.email || '').toLowerCase();
            const isChecked = leadsState.selected.has(emailKey) ? ' checked' : '';
            // Status: 'active' (has conversations), 'chat_deleted' (admin removed the chat), 'orphan' (no chat ever).
            const status = r.status || (r.is_orphan ? 'orphan' : 'active');
            const isOrphan = (status === 'orphan');
            const isChatDeleted = (status === 'chat_deleted');
            const nameLine = r.name
                ? `<span class="knet-leads-lead-name">${escapeHtmlLeads(r.name)}</span>`
                : '';
            const leadCell = `
                <div class="knet-leads-lead-cell">
                    <span class="knet-leads-lead-email" title="${escapeHtmlLeads(r.email)}">${escapeHtmlLeads(r.email)}</span>
                    ${nameLine}
                </div>`;
            let countCell;
            if (isOrphan) {
                countCell = `<span class="knet-leads-pill knet-leads-pill-orphan">Orphan</span>`;
            } else if (isChatDeleted) {
                countCell = `<span class="knet-leads-pill knet-leads-pill-deleted" title="Chat was deleted by an admin">Chat deleted</span>`;
            } else {
                countCell = `<span class="knet-leads-pill">${r.conversation_count}</span>`;
            }
            const lastCell = escapeHtmlLeads(r.last_seen_display || (isOrphan ? 'No conversation yet' : ''));
            const pageCell = r.top_page_url
                ? `<a href="${escapeHtmlLeads(r.top_page_url)}" target="_blank" rel="noopener" class="knet-leads-page-link" title="${escapeHtmlLeads(r.top_page_url)}">${escapeHtmlLeads(r.top_page_title || r.top_page_url)}</a>`
                : '<span class="knet-leads-muted">—</span>';
            // View Convo only for active leads (orphans and chat_deleted have no viewable session).
            const viewBtn = (status === 'active' && r.latest_session_id)
                ? `<button type="button" class="knet-btn knet-btn-ghost knet-btn-sm knet-leads-view" data-session-id="${escapeHtmlLeads(r.latest_session_id)}" title="View latest conversation">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <span>View convo</span>
                    </button>`
                : '';
            const deleteBtn = `<button type="button" class="knet-btn knet-btn-ghost knet-btn-sm knet-btn-danger-ghost knet-leads-delete-row" data-email="${escapeHtmlLeads(r.email)}" title="Delete lead">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>`;

            const rowStateClass = isOrphan ? ' is-orphan' : (isChatDeleted ? ' is-chat-deleted' : '');
            html += `
                <tr class="knet-leads-row${rowStateClass}" data-email="${escapeHtmlLeads(r.email)}">
                    <td class="knet-leads-col-check"><input type="checkbox" class="knet-leads-rowcheck"${isChecked}></td>
                    <td class="knet-leads-col-lead">${leadCell}</td>
                    <td class="knet-leads-col-count">${countCell}</td>
                    <td class="knet-leads-col-last">${lastCell}</td>
                    <td class="knet-leads-col-page">${pageCell}</td>
                    <td class="knet-leads-col-actions">${viewBtn}${deleteBtn}</td>
                </tr>
            `;
        });

        $tbody.html(html);
        updateLeadsSelectionUI();
    }

    function renderLeadsCount(start, end, total) {
        if (!total) {
            $('#knet-leads-count').text('0 leads');
        } else {
            $('#knet-leads-count').text(start + '-' + end + ' / ' + total + ' leads');
        }
    }

    function renderLeadsPagination(currentPage, totalPages) {
        const $c = $('#knet-leads-pagination');
        if (!totalPages || totalPages <= 1) { $c.html(''); return; }
        let html = '<div class="knet-pagination-btns">';
        if (currentPage > 1) {
            html += `<button class="knet-page-btn" data-page="${currentPage - 1}">&laquo;</button>`;
        }
        html += `<span class="knet-page-info">${currentPage} / ${totalPages}</span>`;
        if (currentPage < totalPages) {
            html += `<button class="knet-page-btn" data-page="${currentPage + 1}">&raquo;</button>`;
        }
        html += '</div>';
        $c.html(html);
    }

    function updateLeadsSelectionUI() {
        const count = leadsState.selected.size;
        const $countEl = $('#knet-leads-selected-count');
        const $del = $('#knet-leads-delete-selected');
        if (count > 0) {
            $countEl.text(count + ' selected').addClass('has-selection');
            $del.prop('disabled', false);
        } else {
            $countEl.text('0').removeClass('has-selection');
            $del.prop('disabled', true);
        }
        // Selected-scope export menu items
        $('#knet-leads-export-menu button[data-scope="selected"]').prop('disabled', count === 0);

        // Select-all checkbox state
        const $checks = $('.knet-leads-rowcheck');
        const checked = $checks.filter(':checked').length;
        const total = $checks.length;
        $('#knet-leads-select-all').prop('checked', total > 0 && checked === total);
        $('#knet-leads-select-all').prop('indeterminate', checked > 0 && checked < total);
    }

    // Trigger leads load when switching to the tab (works alongside the main nav handler above).
    $('.knet-nav-link[data-target="leads"], .knet-mobile-nav-link[data-target="leads"]').on('click', function() {
        if (!leadsState.loaded) {
            loadLeads(1);
        }
    });

    // Filter: search (debounced)
    let leadsSearchTimer;
    $('#knet-leads-search').on('input', function() {
        clearTimeout(leadsSearchTimer);
        const val = $(this).val();
        leadsSearchTimer = setTimeout(function() {
            leadsState.filters.search = (val || '').trim();
            updateClearFiltersButton();
            loadLeads(1);
        }, 300);
    });

    // Filter: date range
    $('#knet-leads-date-range').on('change', function() {
        leadsState.filters.dateRange = $(this).val();
        updateClearFiltersButton();
        loadLeads(1);
    });

    // Filter: status
    $('#knet-leads-status').on('change', function() {
        leadsState.filters.status = $(this).val();
        updateClearFiltersButton();
        loadLeads(1);
    });

    // Clear filters
    $('#knet-leads-clear-filters').on('click', function() {
        leadsState.filters = { search: '', dateRange: 'all', status: 'all', pageUrl: '', pageTitle: '' };
        $('#knet-leads-search').val('');
        $('#knet-leads-date-range').val('all');
        $('#knet-leads-status').val('all');
        setPageFilterChip('', '');
        loadLeads(1);
    });

    // Remove page chip
    $leads().on('click', '.knet-leads-page-chip-remove', function() {
        setPageFilterChip('', '');
        loadLeads(1);
    });

    // Top Pages click -> set filter
    $leads().on('click', '.knet-leads-toppage-row', function() {
        const url = $(this).data('url') || '';
        const title = $(this).data('title') || '';
        setPageFilterChip(url, title);
        loadLeads(1);
    });

    // Pagination click
    $leads().on('click', '#knet-leads-pagination .knet-page-btn', function() {
        const p = parseInt($(this).data('page'), 10);
        if (p > 0) loadLeads(p);
    });

    // Select-all
    $('#knet-leads-select-all').on('change', function() {
        const on = $(this).is(':checked');
        $('.knet-leads-rowcheck').prop('checked', on);
        $('.knet-leads-row').each(function() {
            const email = ($(this).data('email') || '').toString().toLowerCase();
            if (on) {
                leadsState.selected.add(email);
            } else {
                leadsState.selected.delete(email);
            }
        });
        updateLeadsSelectionUI();
    });

    // Row checkbox
    $leads().on('change', '.knet-leads-rowcheck', function() {
        const email = ($(this).closest('.knet-leads-row').data('email') || '').toString().toLowerCase();
        if ($(this).is(':checked')) {
            leadsState.selected.add(email);
        } else {
            leadsState.selected.delete(email);
        }
        updateLeadsSelectionUI();
    });

    // View convo -> jump to All Chats tab and open the session
    $leads().on('click', '.knet-leads-view', function() {
        const sid = $(this).attr('data-session-id');
        if (!sid) return;
        $('.knet-nav-link[data-target="all-chats"]').trigger('click');
        // selectChat is defined earlier in this closure
        if (typeof selectChat === 'function') {
            setTimeout(function() { selectChat(sid); }, 30);
        }
    });

    // Row delete -> confirm for one
    $leads().on('click', '.knet-leads-delete-row', function() {
        const email = $(this).data('email');
        if (!email) return;
        openLeadsConfirm([String(email)]);
    });

    // Bulk delete -> confirm for N
    $('#knet-leads-delete-selected').on('click', function() {
        if (leadsState.selected.size === 0) return;
        openLeadsConfirm(Array.from(leadsState.selected));
    });

    function openLeadsConfirm(emails) {
        leadsState.pendingDelete = emails;
        const count = emails.length;
        const msg = count === 1
            ? 'Delete lead "' + emails[0] + '" and all of their conversations?'
            : 'Delete ' + count + ' leads and all of their conversations?';
        $('#knet-leads-confirm-body').text(msg);
        $('#knet-leads-confirm').fadeIn(120);
    }

    function closeLeadsConfirm() {
        $('#knet-leads-confirm').fadeOut(120);
        leadsState.pendingDelete = [];
    }

    $leads().on('click', '[data-knet-leads-close]', closeLeadsConfirm);

    $('#knet-leads-confirm-go').on('click', function() {
        const emails = leadsState.pendingDelete.slice();
        if (!emails.length) { closeLeadsConfirm(); return; }

        const $btn = $(this).prop('disabled', true).text('Deleting...');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'knittnet_delete_leads',
                security: $('#knittnet_leads_delete_nonce').val(),
                emails: emails
            },
            success: function(response) {
                $btn.prop('disabled', false).text('Delete permanently');
                closeLeadsConfirm();
                if (response && response.success) {
                    emails.forEach(function(e) { leadsState.selected.delete(e.toLowerCase()); });
                    loadLeads(leadsState.page);
                } else {
                    alert((response && response.data && response.data.message) || 'Failed to delete leads.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('Delete permanently');
                alert('Network error while deleting.');
            }
        });
    });

    // Export dropdown
    $('#knet-leads-export-btn').on('click', function(e) {
        e.stopPropagation();
        $('#knet-leads-export-menu').toggleClass('is-open');
    });

    $(document).on('click', function() {
        $('#knet-leads-export-menu').removeClass('is-open');
    });

    $('#knet-leads-export-menu').on('click', function(e) { e.stopPropagation(); });

    $('#knet-leads-export-menu button').on('click', function() {
        if ($(this).prop('disabled')) return;
        const scope = $(this).data('scope') || 'all';
        const fields = $(this).data('fields') || 'email_and_name';
        submitLeadsExport(scope, fields);
        $('#knet-leads-export-menu').removeClass('is-open');
    });

    function submitLeadsExport(scope, fields) {
        const $form = $('<form>', { method: 'POST', action: ajaxurl, style: 'display:none;' });
        $form.append($('<input>', { type: 'hidden', name: 'action', value: 'knittnet_export_leads' }));
        $form.append($('<input>', { type: 'hidden', name: 'security', value: $('#knittnet_leads_export_nonce').val() }));
        $form.append($('<input>', { type: 'hidden', name: 'scope', value: scope }));
        $form.append($('<input>', { type: 'hidden', name: 'fields', value: fields }));
        if (scope === 'selected') {
            Array.from(leadsState.selected).forEach(function(e) {
                $form.append($('<input>', { type: 'hidden', name: 'emails[]', value: e }));
            });
        }
        $form.appendTo('body').submit().remove();
    }

    // Preload leads metadata on page load (for the nav badge count only) without rendering.
    // We keep this light — the full fetch only runs when the tab is clicked.
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: { action: 'knittnet_fetch_leads', page: 1, per_page: 1 },
        success: function(response) {
            if (response && response.success && response.stats) {
                const total = response.stats.total_leads || 0;
                const $badge = $('#knet-leads-nav-badge');
                if (total > 0) $badge.text(total).show();
            }
        }
    });
});
