/* ================================================================
   CrossFit Kouvola — FAQ Accordion Toggle & Site Interactivity
   ================================================================ */
(function () {
    'use strict';

    // Guard against multiple executions if script is enqueued more than once
    if (window.__cwHomeJsInitialized) {
        return;
    }
    window.__cwHomeJsInitialized = true;

    function initThemeFeatures() {
        // 1. FAQ Accordions
        function setupAccordions(itemSelector, btnSelector) {
            var items = document.querySelectorAll(itemSelector);
            items.forEach(function (item) {
                var btn = item.querySelector(btnSelector);
                if (!btn || btn.dataset.accordionBound) return;
                btn.dataset.accordionBound = 'true';

                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var wasOpen = item.classList.contains('is-open');

                    // Close other items in the same list container
                    var parentList = item.closest('.hp-faq-list, .page-faq-list') || item.parentElement;
                    if (parentList) {
                        var siblings = parentList.querySelectorAll(itemSelector);
                        siblings.forEach(function (i) {
                            i.classList.remove('is-open');
                        });
                    } else {
                        items.forEach(function (i) {
                            i.classList.remove('is-open');
                        });
                    }

                    if (!wasOpen) {
                        item.classList.add('is-open');
                    }
                });
            });
        }

        setupAccordions('.hp-faq-item, .page-faq-item', '.hp-faq-q, .page-faq-q');

        // 2. Read More / Read Less Toggle for Recommendation & Content Sections
        var contentContainers = document.querySelectorAll('.cw-read-more-container');
        contentContainers.forEach(function (container) {
            if (container.dataset.readMoreBound) return;
            container.dataset.readMoreBound = 'true';

            var moreBtn = container.querySelector('.cw-read-more-btn');
            var lessBtn = container.querySelector('.cw-read-less-btn');
            var targetSection = container.closest('.hp-content-section') || container;

            if (moreBtn) {
                moreBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    container.classList.add('is-expanded');
                });
            }
            if (lessBtn) {
                lessBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    container.classList.remove('is-expanded');
                    if (targetSection) {
                        targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            }
        });

        // 3. Mobile Menu Navigation
        var hamburger = document.querySelector('.mobile-humburger');
        if (hamburger && !hamburger.dataset.menuBound) {
            hamburger.dataset.menuBound = 'true';
            hamburger.addEventListener('click', function (e) {
                e.preventDefault();
                var nav = document.querySelector('nav.main-navigation');
                if (nav) nav.classList.add('active');
            });
        }

        var closeMenu = document.querySelector('.close-menu');
        if (closeMenu && !closeMenu.dataset.menuBound) {
            closeMenu.dataset.menuBound = 'true';
            closeMenu.addEventListener('click', function (e) {
                e.preventDefault();
                var nav = document.querySelector('nav.main-navigation');
                if (nav) nav.classList.remove('active');
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initThemeFeatures);
    } else {
        initThemeFeatures();
    }
}());