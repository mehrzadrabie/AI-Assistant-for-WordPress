/**
 * KnittNet Pro & Extensions Page JavaScript
 * Handles navigation, license management, and mobile interactions
 */
(function($) {
    'use strict';

    // ==========================================================================
    // Mobile Detection
    // ==========================================================================
    function isMobile() {
        return window.innerWidth <= 782;
    }

    // ==========================================================================
    // Section Navigation
    // ==========================================================================
    function showSection(targetId) {
        // Hide all sections
        $('.knet-section').removeClass('active');

        // Show target section
        $('#' + targetId).addClass('active');

        // Update sidebar nav active states
        $('.knet-nav-link, .knet-addon-nav-link').removeClass('active');
        $('[data-target="' + targetId + '"]').addClass('active');

        // Update mobile nav active states
        $('.knet-mobile-nav-link').removeClass('active');
        $('.knet-mobile-nav-link[data-target="' + targetId + '"]').addClass('active');

        // Close mobile menu if open
        closeMobileMenu();

        // Handle mobile detail panel
        if (isMobile() && targetId.startsWith('addon-')) {
            showMobileDetailPanel(targetId);
        }

        // Scroll to top of content
        $('.knet-content').scrollTop(0);
    }

    // ==========================================================================
    // Mobile Menu
    // ==========================================================================
    function openMobileMenu() {
        $('.knet-mobile-menu').addClass('open');
        $('.knet-mobile-overlay').addClass('open');
        $('body').addClass('knet-mobile-menu-open');
    }

    function closeMobileMenu() {
        $('.knet-mobile-menu').removeClass('open');
        $('.knet-mobile-overlay').removeClass('open');
        $('body').removeClass('knet-mobile-menu-open');
    }

    // ==========================================================================
    // Mobile Detail Panel
    // ==========================================================================
    function showMobileDetailPanel(targetId) {
        if (isMobile()) {
            $('#' + targetId).addClass('mobile-active');
            $('body').addClass('knet-mobile-panel-open');
        }
    }

    function hideMobileDetailPanel() {
        $('.knet-addon-detail').removeClass('mobile-active');
        $('body').removeClass('knet-mobile-panel-open');
    }

    // ==========================================================================
    // License Activation - HANDLED BY activation-script.js
    // ==========================================================================
    // License activation is handled by activation-script.js which provides
    // better status feedback and error recovery with domain linking.
    // Do not add duplicate handlers here.

    // ==========================================================================
    // License Deactivation - HANDLED BY activation-script.js
    // ==========================================================================
    // License deactivation is handled by activation-script.js.
    // Do not add duplicate handlers here.

    // ==========================================================================
    // Domain Linking - HANDLED BY activation-script.js
    // ==========================================================================
    // Domain linking is handled by activation-script.js.
    // Do not add duplicate handlers here.

    // ==========================================================================
    // Initialize
    // ==========================================================================
    $(document).ready(function() {
        // Sidebar navigation clicks
        $(document).on('click', '.knet-nav-link[data-target], .knet-addon-nav-link[data-target]', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            if (target) {
                showSection(target);
            }
        });

        // Extension card clicks (overview grid)
        $(document).on('click', '.knet-extension-card', function(e) {
            // Don't trigger if clicking on a button inside
            if ($(e.target).closest('button, a').length) {
                return;
            }
            var addon = $(this).data('addon');
            if (addon) {
                showSection('addon-' + addon);
            }
        });

        // View addon button clicks
        $(document).on('click', '.knet-view-addon-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var target = $(this).data('target');
            if (target) {
                showSection(target);
            }
        });

        // Back button clicks (supports both .knet-back-btn and .knet-back-link)
        $(document).on('click', '.knet-back-btn, .knet-back-link', function(e) {
            e.preventDefault();
            var target = $(this).data('target') || 'overview';
            hideMobileDetailPanel();
            showSection(target);
        });

        // Mobile menu toggle
        $(document).on('click', '.knet-mobile-menu-btn', function(e) {
            e.preventDefault();
            openMobileMenu();
        });

        // Mobile menu close
        $(document).on('click', '.knet-mobile-menu-close, .knet-mobile-overlay', function(e) {
            e.preventDefault();
            closeMobileMenu();
        });

        // Mobile nav link clicks
        $(document).on('click', '.knet-mobile-nav-link', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            if (target) {
                showSection(target);
            }
        });

        // License activation, deactivation, and domain linking are handled by
        // activation-script.js - do not add duplicate handlers here.

        // Activate license button in sidebar (when inactive)
        $(document).on('click', '.knet-activate-license-btn', function(e) {
            e.preventDefault();
            showSection('overview');
            // Focus on email field after section loads
            setTimeout(function() {
                $('#knittnet_pro_email').focus();
            }, 300);
        });

        // Handle window resize
        $(window).on('resize', function() {
            if (!isMobile()) {
                hideMobileDetailPanel();
                closeMobileMenu();
            }
        });

        // Handle escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileMenu();
                if (isMobile()) {
                    hideMobileDetailPanel();
                    showSection('overview');
                }
            }
        });
    });

})(jQuery);
