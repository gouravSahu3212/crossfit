/* ================================================================
   CrossFit Kouvola — FAQ Accordion Toggle
   ================================================================ */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        function setupAccordions(itemSelector, btnSelector) {
            var items = document.querySelectorAll(itemSelector);
            items.forEach(function (item) {
                var btn = item.querySelector(btnSelector);
                if (!btn) return;
                btn.addEventListener('click', function () {
                    var wasOpen = item.classList.contains('is-open');
                    items.forEach(function (i) { i.classList.remove('is-open'); });
                    if (!wasOpen) item.classList.add('is-open');
                });
            });
        }

        setupAccordions('.hp-faq-item', '.hp-faq-q');
        setupAccordions('.page-faq-item', '.page-faq-q');

        // Read More / Read Less Toggle for Recommendation & Content Sections
        var contentContainers = document.querySelectorAll('.cw-read-more-container');
        contentContainers.forEach(function (container) {
            var moreBtn = container.querySelector('.cw-read-more-btn');
            var lessBtn = container.querySelector('.cw-read-less-btn');
            var targetSection = container.closest('.hp-content-section') || container;

            if (moreBtn) {
                moreBtn.addEventListener('click', function () {
                    container.classList.add('is-expanded');
                });
            }
            if (lessBtn) {
                lessBtn.addEventListener('click', function () {
                    container.classList.remove('is-expanded');
                    if (targetSection) {
                        targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            }
        });

        // Mobile Menu Navigation
        var hamburger = document.querySelector('.mobile-humburger');
        if (hamburger) {
            hamburger.addEventListener('click', function () {
                var nav = document.querySelector('nav.main-navigation');
                if (nav) nav.classList.add('active');
            });
        }

        var closeMenu = document.querySelector('.close-menu');
        if (closeMenu) {
            closeMenu.addEventListener('click', function () {
                var nav = document.querySelector('nav.main-navigation');
                if (nav) nav.classList.remove('active');
            });
        }
    });
}());