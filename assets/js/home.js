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
    });
}());