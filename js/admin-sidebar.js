/**
 * Shared admin shell JS for the KnittNet admin design system.
 *
 * Wires tab switching, mobile menu open/close, and copy-to-clipboard
 * inside every .knet-admin-wrapper on the page. Scoped to each wrapper
 * so multiple admin shells could in theory coexist (today we have one
 * per page, but no global side effects).
 *
 * Source of truth for the inline-script logic previously duplicated in
 * knittnet-basic/includes/admin-api-page.php and
 * knittnet-mcp/includes/admin-mcp-page.php.
 *
 * @package KnittNet
 */
(function () {
    function wire(wrapper) {
        if (!wrapper || wrapper.dataset.knetAdminWired === '1') {
            return;
        }
        wrapper.dataset.knetAdminWired = '1';

        var copiedLabel = (window.KnittNetAdminSidebarI18n && window.KnittNetAdminSidebarI18n.copied) || 'Copied';

        // Tab switcher: clicking any [data-target] button toggles .knet-section.active.
        var navButtons = wrapper.querySelectorAll('[data-target]');
        navButtons.forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var targetId = btn.getAttribute('data-target');
                if (!targetId) {
                    return;
                }

                wrapper.querySelectorAll('.knet-section').forEach(function (s) {
                    s.classList.remove('active');
                });
                var target = wrapper.querySelector('#' + targetId);
                if (target) {
                    target.classList.add('active');
                }

                wrapper.querySelectorAll('.knet-nav-link, .knet-mobile-nav-link').forEach(function (l) {
                    l.classList.remove('active');
                });
                wrapper.querySelectorAll('[data-target="' + targetId + '"]').forEach(function (l) {
                    l.classList.add('active');
                });

                var mobileMenu = wrapper.querySelector('.knet-mobile-menu');
                if (mobileMenu) { mobileMenu.classList.remove('open'); }
                var mobileOverlay = wrapper.querySelector('.knet-mobile-overlay');
                if (mobileOverlay) { mobileOverlay.classList.remove('open'); }
            });
        });

        // Mobile menu open/close.
        var mobileBtn = wrapper.querySelector('.knet-mobile-menu-btn');
        var mobileMenu = wrapper.querySelector('.knet-mobile-menu');
        var mobileOverlay = wrapper.querySelector('.knet-mobile-overlay');
        var mobileClose = wrapper.querySelector('.knet-mobile-menu-close');

        function openMenu() {
            if (mobileMenu) { mobileMenu.classList.add('open'); }
            if (mobileOverlay) { mobileOverlay.classList.add('open'); }
        }
        function closeMenu() {
            if (mobileMenu) { mobileMenu.classList.remove('open'); }
            if (mobileOverlay) { mobileOverlay.classList.remove('open'); }
        }
        if (mobileBtn) { mobileBtn.addEventListener('click', openMenu); }
        if (mobileClose) { mobileClose.addEventListener('click', closeMenu); }
        if (mobileOverlay) { mobileOverlay.addEventListener('click', closeMenu); }

        // Copy-to-clipboard.
        wrapper.querySelectorAll('[data-knet-copy]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var val = btn.getAttribute('data-knet-copy');
                if (!val) {
                    return;
                }
                var label = btn.querySelector('span');
                var original = label ? label.textContent : '';
                var done = function () {
                    if (label) { label.textContent = copiedLabel; }
                    setTimeout(function () {
                        if (label) { label.textContent = original; }
                    }, 1500);
                };
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(val).then(done).catch(function () { done(); });
                } else {
                    var ta = document.createElement('textarea');
                    ta.value = val;
                    document.body.appendChild(ta);
                    ta.select();
                    try { document.execCommand('copy'); } catch (e) {}
                    document.body.removeChild(ta);
                    done();
                }
            });
        });
    }

    function init() {
        document.querySelectorAll('.knet-admin-wrapper').forEach(wire);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
