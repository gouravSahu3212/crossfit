/* ================================================================
   Codyweb Admin Questions Builder — questions.js
   ================================================================
   State lives entirely in the `questions` array.
   render() rebuilds the DOM and re-initialises drag-drop.
   bindEvents() is called once and uses delegation for all clicks/input.
   ================================================================ */

/* global cwChatbotAdmin */
(function () {
    'use strict';

    var API_BASE = cwChatbotAdmin.apiBase;
    var NONCE    = cwChatbotAdmin.nonce;

    var questions  = [];
    var dragSrcEl  = null;

    /* ----------------------------------------------------------------
       Init
    ---------------------------------------------------------------- */
    document.addEventListener('DOMContentLoaded', function () {
        loadQuestions().then(function () {
            render();
            bindEvents();
        });
    });

    /* ----------------------------------------------------------------
       Load
    ---------------------------------------------------------------- */
    function loadQuestions() {
        return fetch(API_BASE + 'questions', {
            headers: { 'X-WP-Nonce': NONCE }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            questions = Array.isArray(data) ? data : [];
        })
        .catch(function () { questions = []; });
    }

    /* ----------------------------------------------------------------
       Render — full rebuild
    ---------------------------------------------------------------- */
    function render() {
        var app = document.getElementById('cw-questions-app');
        if (!app) return;

        var questionsHtml = questions.length === 0
            ? '<div class="cw-empty-state"><div class="cw-empty-state-icon">&#x2753;</div><h3>No Questions Yet</h3><p>Click &ldquo;+ Add Question&rdquo; below to build your quiz.</p></div>'
            : '<div id="cw-questions-list" class="cw-questions-list">' +
                questions.map(renderQuestion).join('') +
              '</div>';

        app.innerHTML =
            '<div class="cw-page-header">' +
                '<h2>&#x2753; Questions &amp; Answers</h2>' +
                '<p>Build your training quiz. Drag cards to reorder. Click <strong>Save All Changes</strong> when done.</p>' +
            '</div>' +
            questionsHtml +
            '<div class="cw-qa-toolbar">' +
                '<button type="button" id="cw-add-question" class="cw-btn-add-question">+ Add Question</button>' +
                '<button type="button" id="cw-save-questions" class="button button-primary cw-btn-save">&#x1F4BE; Save All Changes</button>' +
            '</div>' +
            '<div id="cw-save-status" class="cw-save-status"></div>';

        setupDragDrop();
    }

    /* ----------------------------------------------------------------
       Question template
    ---------------------------------------------------------------- */
    function renderQuestion(q, idx) {
        var answersHtml = q.answers && q.answers.length > 0
            ? q.answers.map(function (a) { return renderAnswer(a, q.id); }).join('')
            : '<div class="cw-no-answers">No answers yet — add one below.</div>';

        return '<div class="cw-question-card" data-id="' + escAttr(q.id) + '" draggable="true">' +
            '<div class="cw-question-header">' +
                '<span class="cw-drag-handle" title="Drag to reorder">&#x2807;</span>' +
                '<span class="cw-question-number">Q' + (idx + 1) + '</span>' +
                '<input type="text" class="cw-question-text" data-id="' + escAttr(q.id) + '"' +
                    ' value="' + escAttr(q.text) + '" placeholder="Type your question here&hellip;" />' +
                '<button type="button" class="cw-btn-delete-q" data-id="' + escAttr(q.id) + '" title="Delete question">&#x1F5D1;</button>' +
            '</div>' +
            '<div class="cw-answers-section">' +
                '<div class="cw-answers-header"><span>Display Text</span><span>Answer Slug</span><span></span></div>' +
                '<div class="cw-answers-list" data-qid="' + escAttr(q.id) + '">' + answersHtml + '</div>' +
                '<button type="button" class="cw-btn-add-answer" data-qid="' + escAttr(q.id) + '">+ Add Answer</button>' +
            '</div>' +
        '</div>';
    }

    function renderAnswer(a, qid) {
        return '<div class="cw-answer-row" data-id="' + escAttr(a.id) + '">' +
            '<input type="text" class="cw-answer-text"' +
                ' data-qid="' + escAttr(qid) + '" data-id="' + escAttr(a.id) + '"' +
                ' value="' + escAttr(a.text) + '" placeholder="e.g. Lose weight" />' +
            '<input type="text" class="cw-answer-slug"' +
                ' data-qid="' + escAttr(qid) + '" data-id="' + escAttr(a.id) + '"' +
                ' value="' + escAttr(a.slug) + '" placeholder="lose-weight"' +
                (a.slugManuallyEdited ? ' data-manual="1"' : '') + ' />' +
            '<button type="button" class="cw-btn-delete-a"' +
                ' data-qid="' + escAttr(qid) + '" data-id="' + escAttr(a.id) + '"' +
                ' title="Delete answer">&#x1F5D1;</button>' +
        '</div>';
    }

    /* ----------------------------------------------------------------
       Events — delegation (called once)
    ---------------------------------------------------------------- */
    function bindEvents() {
        var app = document.getElementById('cw-questions-app');
        if (!app) return;

        app.addEventListener('click', function (e) {
            var t = e.target;
            if (t.id === 'cw-add-question')               { addQuestion(); return; }
            if (t.id === 'cw-save-questions')             { saveQuestions(); return; }
            if (t.classList.contains('cw-btn-delete-q')) { deleteQuestion(t.dataset.id); return; }
            if (t.classList.contains('cw-btn-add-answer')) { addAnswer(t.dataset.qid); return; }
            if (t.classList.contains('cw-btn-delete-a'))  { deleteAnswer(t.dataset.qid, t.dataset.id); return; }
        });

        app.addEventListener('input', function (e) {
            var t = e.target;
            if (t.classList.contains('cw-question-text')) {
                syncQuestion(t.dataset.id, 'text', t.value);
            }
            if (t.classList.contains('cw-answer-text')) {
                syncAnswer(t.dataset.qid, t.dataset.id, 'text', t.value);
                var slugEl = t.closest('.cw-answer-row').querySelector('.cw-answer-slug');
                if (slugEl && !slugEl.dataset.manual) {
                    var slug = toSlug(t.value);
                    slugEl.value = slug;
                    syncAnswer(t.dataset.qid, t.dataset.id, 'slug', slug);
                }
            }
            if (t.classList.contains('cw-answer-slug')) {
                t.dataset.manual = '1';
                syncAnswer(t.dataset.qid, t.dataset.id, 'slug', t.value);
                syncAnswer(t.dataset.qid, t.dataset.id, 'slugManuallyEdited', true);
            }
        });
    }

    /* ----------------------------------------------------------------
       CRUD
    ---------------------------------------------------------------- */
    function addQuestion() {
        questions.push({ id: uid('q'), order: questions.length, text: '', answers: [] });
        render();
        var last = document.querySelector('.cw-question-text:last-of-type') ||
                   document.querySelector('.cw-question-text');
        if (last) { last.focus(); }
    }

    function deleteQuestion(id) {
        if (!confirm('Delete this question and all its answers?')) return;
        questions = questions.filter(function (q) { return q.id !== id; });
        render();
    }

    function addAnswer(qid) {
        var q = findQ(qid);
        if (!q) return;
        q.answers.push({ id: uid('a'), text: '', slug: '', slugManuallyEdited: false });
        render();
    }

    function deleteAnswer(qid, aid) {
        var q = findQ(qid);
        if (!q) return;
        q.answers = q.answers.filter(function (a) { return a.id !== aid; });
        render();
    }

    /* ----------------------------------------------------------------
       State sync helpers
    ---------------------------------------------------------------- */
    function syncQuestion(id, key, val) {
        var q = findQ(id);
        if (q) q[key] = val;
    }

    function syncAnswer(qid, aid, key, val) {
        var q = findQ(qid);
        if (!q) return;
        var a = q.answers.find(function (x) { return x.id === aid; });
        if (a) a[key] = val;
    }

    function findQ(id) {
        return questions.find(function (q) { return q.id === id; }) || null;
    }

    /* ----------------------------------------------------------------
       Drag & Drop — called after every render()
    ---------------------------------------------------------------- */
    function setupDragDrop() {
        var list = document.getElementById('cw-questions-list');
        if (!list) return;

        list.querySelectorAll('.cw-question-card').forEach(function (card) {
            card.addEventListener('dragstart', function (e) {
                dragSrcEl = card;
                e.dataTransfer.effectAllowed = 'move';
                setTimeout(function () { card.classList.add('is-dragging'); }, 0);
            });
            card.addEventListener('dragend', function () {
                card.classList.remove('is-dragging');
                list.querySelectorAll('.cw-question-card').forEach(function (c) { c.classList.remove('drag-over'); });
                // Re-sync questions order from DOM
                var ids = Array.from(list.querySelectorAll('.cw-question-card')).map(function (c) { return c.dataset.id; });
                questions = ids.map(function (id) {
                    return questions.find(function (q) { return q.id === id; });
                }).filter(Boolean);
                // Update Q-number badges
                list.querySelectorAll('.cw-question-number').forEach(function (el, i) {
                    el.textContent = 'Q' + (i + 1);
                });
            });
            card.addEventListener('dragover', function (e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                if (card === dragSrcEl) return;
                list.querySelectorAll('.cw-question-card').forEach(function (c) { c.classList.remove('drag-over'); });
                card.classList.add('drag-over');
                var rect = card.getBoundingClientRect();
                var mid  = rect.top + rect.height / 2;
                if (e.clientY < mid) {
                    list.insertBefore(dragSrcEl, card);
                } else {
                    list.insertBefore(dragSrcEl, card.nextSibling);
                }
            });
            card.addEventListener('drop', function (e) {
                e.preventDefault();
                card.classList.remove('drag-over');
            });
        });
    }

    /* ----------------------------------------------------------------
       Save
    ---------------------------------------------------------------- */
    function saveQuestions() {
        var btn    = document.getElementById('cw-save-questions');
        var status = document.getElementById('cw-save-status');
        if (!btn) return;

        btn.disabled    = true;
        btn.textContent = 'Saving\u2026';

        fetch(API_BASE + 'questions', {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-WP-Nonce':    NONCE,
            },
            body: JSON.stringify(questions),
        })
        .then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(function () {
            setStatus(status, '\u2705 Saved successfully!', 'success');
        })
        .catch(function () {
            setStatus(status, '\u274C Save failed. Please try again.', 'error');
        })
        .finally(function () {
            btn.disabled    = false;
            btn.textContent = '\uD83D\uDCBE Save All Changes';
        });
    }

    /* ----------------------------------------------------------------
       Utilities
    ---------------------------------------------------------------- */
    function uid(prefix) {
        return prefix + Date.now() + Math.floor(Math.random() * 1000);
    }

    function toSlug(str) {
        return (str || '').toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/[\s_]+/g, '-')
            .replace(/-+/g, '-')
            .substring(0, 60);
    }

    function escAttr(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function setStatus(el, msg, cls) {
        if (!el) return;
        el.textContent = msg;
        el.className   = 'cw-save-status ' + cls;
        setTimeout(function () { el.textContent = ''; el.className = 'cw-save-status'; }, 3500);
    }

}());
