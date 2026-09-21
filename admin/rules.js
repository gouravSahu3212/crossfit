/* ================================================================
   Codyweb Admin Rules Builder — rules.js
   ================================================================
   Fetches questions, recommendations, and existing rules from the
   REST API. Renders a drag-drop rule builder. Saves via REST POST.
   ================================================================ */

/* global cwChatbotRules */
(function () {
    'use strict';

    var API_BASE = cwChatbotRules.apiBase;
    var NONCE    = cwChatbotRules.nonce;

    var questions       = [];
    var recommendations = [];
    var rules           = [];
    var fallback        = { message: '', url: '' };
    var dragSrcEl       = null;

    /* ----------------------------------------------------------------
       Init
    ---------------------------------------------------------------- */
    document.addEventListener('DOMContentLoaded', function () {
        loadAll().then(function () {
            render();
            bindEvents();
        });
    });

    /* ----------------------------------------------------------------
       Load all data in parallel
    ---------------------------------------------------------------- */
    function loadAll() {
        return Promise.all([
            fetch(API_BASE + 'questions',       { headers: { 'X-WP-Nonce': NONCE } }).then(function (r) { return r.json(); }),
            fetch(API_BASE + 'recommendations', { headers: { 'X-WP-Nonce': NONCE } }).then(function (r) { return r.json(); }),
            fetch(API_BASE + 'rules',           { headers: { 'X-WP-Nonce': NONCE } }).then(function (r) { return r.json(); }),
        ])
        .then(function (results) {
            questions       = Array.isArray(results[0]) ? results[0] : [];
            recommendations = Array.isArray(results[1]) ? results[1] : [];
            var rulesData   = results[2];
            if (rulesData && Array.isArray(rulesData.rules)) {
                rules    = rulesData.rules;
                fallback = rulesData.fallback || { message: '', url: '' };
            } else {
                rules    = [];
                fallback = { message: '', url: '' };
            }
        })
        .catch(function (err) {
            console.error('CW Chatbot: failed to load data', err);
            questions = []; recommendations = []; rules = [];
        });
    }

    /* ----------------------------------------------------------------
       Render — full rebuild
    ---------------------------------------------------------------- */
    function render() {
        var app = document.getElementById('cw-rules-app');
        if (!app) return;

        var notices = '';
        if (!recommendations.length) {
            notices += '<div class="cw-notice cw-notice-warning">&#x26A0;&#xFE0F; No Recommendations found. <a href="' + escAttr(cwChatbotRules.newRecUrl) + '">Add a Recommendation</a> first.</div>';
        }
        if (!questions.length) {
            notices += '<div class="cw-notice cw-notice-warning">&#x26A0;&#xFE0F; No Questions found. <a href="' + escAttr(cwChatbotRules.questionsUrl) + '">Add Questions</a> first.</div>';
        }

        var rulesHtml = rules.length === 0
            ? '<div class="cw-empty-state"><div class="cw-empty-state-icon">&#x1F5FA;</div><h3>No Rules Yet</h3><p>Click &ldquo;+ Add Rule&rdquo; to map answers to a recommendation.</p></div>'
            : '<div id="cw-rules-list" class="cw-rules-list">' + rules.map(renderRule).join('') + '</div>';

        app.innerHTML =
            '<div class="cw-page-header">' +
                '<h2>&#x1F5FA; Logic Rules</h2>' +
                '<p>Map question answers to training recommendations. Rules are evaluated <strong>top-to-bottom</strong> &mdash; the first match wins. Drag to reorder.</p>' +
            '</div>' +
            notices +
            rulesHtml +
            '<div class="cw-rules-toolbar">' +
                '<button type="button" id="cw-add-rule" class="cw-btn-add-rule">+ Add Rule</button>' +
                '<button type="button" id="cw-save-rules" class="button button-primary cw-btn-save">&#x1F4BE; Save All Rules</button>' +
            '</div>' +
            '<div id="cw-save-status" class="cw-save-status"></div>' +
            renderFallbackSection();

        setupDragDrop();
    }

    /* ----------------------------------------------------------------
       Rule template
    ---------------------------------------------------------------- */
    function renderRule(rule, idx) {
        var recOptions = recommendations.map(function (r) {
            var sel = parseInt(rule.recommendation_id, 10) === r.id ? ' selected' : '';
            return '<option value="' + r.id + '"' + sel + '>' + escHtml(r.title) + '</option>';
        }).join('');

        var conditionsHtml = rule.conditions && rule.conditions.length
            ? rule.conditions.map(function (c, ci) { return renderCondition(c, rule.id, ci); }).join('')
            : '<p class="cw-no-conditions">No conditions yet. Add one below.</p>';

        var modeAll = rule.match_mode !== 'any' ? ' active' : '';
        var modeAny = rule.match_mode === 'any'  ? ' active' : '';

        return '<div class="cw-rule-card" data-id="' + escAttr(rule.id) + '" draggable="true">' +
            '<div class="cw-rule-header">' +
                '<span class="cw-drag-handle" title="Drag to reorder">&#x2807;</span>' +
                '<span class="cw-rule-priority">#' + (idx + 1) + '</span>' +
                '<span class="cw-rule-label">IF conditions match &rarr; Recommend:</span>' +
                '<div class="cw-match-mode-toggle">' +
                    '<button type="button" class="cw-match-btn' + modeAll + '" data-rule="' + escAttr(rule.id) + '" data-mode="all">ALL</button>' +
                    '<button type="button" class="cw-match-btn' + modeAny + '" data-rule="' + escAttr(rule.id) + '" data-mode="any">ANY</button>' +
                '</div>' +
                '<button type="button" class="cw-btn-delete-r" data-id="' + escAttr(rule.id) + '" title="Delete rule">&#x1F5D1;</button>' +
            '</div>' +
            '<div class="cw-rule-body">' +
                '<div class="cw-rec-selector">' +
                    '<label>Recommend:</label>' +
                    '<select class="cw-rec-select" data-rule="' + escAttr(rule.id) + '">' +
                        '<option value="">&#x2014; Select a recommendation &#x2014;</option>' +
                        recOptions +
                    '</select>' +
                '</div>' +
                '<div class="cw-conditions-header">Conditions</div>' +
                '<div class="cw-conditions-list" data-rule="' + escAttr(rule.id) + '">' + conditionsHtml + '</div>' +
                '<button type="button" class="cw-btn-add-condition" data-rule="' + escAttr(rule.id) + '">+ Add Condition</button>' +
            '</div>' +
        '</div>';
    }

    function renderCondition(condition, ruleId, idx) {
        var qOptions = questions.map(function (q) {
            var sel = condition.question_id === q.id ? ' selected' : '';
            return '<option value="' + escAttr(q.id) + '"' + sel + '>' + escHtml(q.text) + '</option>';
        }).join('');

        var selectedQ = questions.find(function (q) { return q.id === condition.question_id; });
        var aOptions  = selectedQ
            ? selectedQ.answers.map(function (a) {
                var sel = condition.answer_slug === a.slug ? ' selected' : '';
                return '<option value="' + escAttr(a.slug) + '"' + sel + '>' + escHtml(a.text) + '</option>';
              }).join('')
            : '';

        return '<div class="cw-condition-row" data-rule="' + escAttr(ruleId) + '" data-idx="' + idx + '">' +
            '<select class="cw-q-select" data-rule="' + escAttr(ruleId) + '" data-idx="' + idx + '">' +
                '<option value="">&#x2014; Select question &#x2014;</option>' + qOptions +
            '</select>' +
            '<span class="cw-condition-is">is</span>' +
            '<select class="cw-a-select" data-rule="' + escAttr(ruleId) + '" data-idx="' + idx + '">' +
                '<option value="">&#x2014; Select answer &#x2014;</option>' + aOptions +
            '</select>' +
            '<button type="button" class="cw-btn-delete-c" data-rule="' + escAttr(ruleId) + '" data-idx="' + idx + '" title="Delete condition">&#x1F5D1;</button>' +
        '</div>';
    }

    function renderFallbackSection() {
        return '<div class="cw-fallback-section">' +
            '<h3>&#x26A0;&#xFE0F; Fallback &mdash; No Match Found</h3>' +
            '<p>Shown in the chatbot when no rule conditions are met.</p>' +
            '<div class="cw-fallback-fields">' +
                '<div>' +
                    '<label for="cw-fallback-message">Message</label>' +
                    '<textarea id="cw-fallback-message" rows="2" placeholder="Get in touch with us to find the best training option for you!">' + escHtml(fallback.message || '') + '</textarea>' +
                '</div>' +
                '<div>' +
                    '<label for="cw-fallback-url">Link URL (optional)</label>' +
                    '<input type="url" id="cw-fallback-url" value="' + escAttr(fallback.url || '') + '" placeholder="https://example.com/contact" />' +
                '</div>' +
            '</div>' +
        '</div>';
    }

    /* ----------------------------------------------------------------
       Events — delegation (called once)
    ---------------------------------------------------------------- */
    function bindEvents() {
        var app = document.getElementById('cw-rules-app');
        if (!app) return;

        app.addEventListener('click', function (e) {
            var t = e.target;
            if (t.id === 'cw-add-rule')                      { addRule(); return; }
            if (t.id === 'cw-save-rules')                    { saveRules(); return; }
            if (t.classList.contains('cw-btn-delete-r'))     { deleteRule(t.dataset.id); return; }
            if (t.classList.contains('cw-btn-add-condition')){ addCondition(t.dataset.rule); return; }
            if (t.classList.contains('cw-btn-delete-c'))     { deleteCondition(t.dataset.rule, parseInt(t.dataset.idx, 10)); return; }
            if (t.classList.contains('cw-match-btn'))        { setMatchMode(t.dataset.rule, t.dataset.mode); return; }
        });

        app.addEventListener('change', function (e) {
            var t = e.target;
            if (t.classList.contains('cw-rec-select')) {
                var rule = findRule(t.dataset.rule);
                if (rule) rule.recommendation_id = parseInt(t.value, 10) || null;
            }
            if (t.classList.contains('cw-q-select')) {
                var idx = parseInt(t.dataset.idx, 10);
                var rl  = findRule(t.dataset.rule);
                if (rl && rl.conditions[idx] !== undefined) {
                    rl.conditions[idx].question_id = t.value;
                    rl.conditions[idx].answer_slug  = '';
                    updateAnswerDropdown(t.dataset.rule, idx, t.value);
                }
            }
            if (t.classList.contains('cw-a-select')) {
                var rl2 = findRule(t.dataset.rule);
                var i2  = parseInt(t.dataset.idx, 10);
                if (rl2 && rl2.conditions[i2] !== undefined) {
                    rl2.conditions[i2].answer_slug = t.value;
                }
            }
        });
    }

    /* ----------------------------------------------------------------
       Targeted answer dropdown update (avoids full re-render on Q change)
    ---------------------------------------------------------------- */
    function updateAnswerDropdown(ruleId, idx, questionId) {
        var condList = document.querySelector('.cw-conditions-list[data-rule="' + ruleId + '"]');
        if (!condList) return;
        var rows = condList.querySelectorAll('.cw-condition-row');
        if (!rows[idx]) return;
        var aSelect = rows[idx].querySelector('.cw-a-select');
        if (!aSelect) return;
        var selectedQ = questions.find(function (q) { return q.id === questionId; });
        var opts = '<option value="">&#x2014; Select answer &#x2014;</option>';
        if (selectedQ) {
            opts += selectedQ.answers.map(function (a) {
                return '<option value="' + escAttr(a.slug) + '">' + escHtml(a.text) + '</option>';
            }).join('');
        }
        aSelect.innerHTML = opts;
    }

    /* ----------------------------------------------------------------
       CRUD
    ---------------------------------------------------------------- */
    function addRule() {
        var firstRec = recommendations[0] ? recommendations[0].id : null;
        rules.push({ id: uid('r'), recommendation_id: firstRec, match_mode: 'all', conditions: [] });
        render();
        bindEvents();
    }

    function deleteRule(id) {
        if (!confirm('Delete this rule?')) return;
        rules = rules.filter(function (r) { return r.id !== id; });
        render();
        bindEvents();
    }

    function addCondition(ruleId) {
        var rule = findRule(ruleId);
        if (!rule) return;
        rule.conditions.push({ question_id: '', answer_slug: '' });
        render();
        bindEvents();
    }

    function deleteCondition(ruleId, idx) {
        var rule = findRule(ruleId);
        if (!rule) return;
        rule.conditions.splice(idx, 1);
        render();
        bindEvents();
    }

    function setMatchMode(ruleId, mode) {
        var rule = findRule(ruleId);
        if (!rule) return;
        rule.match_mode = mode;
        var card = document.querySelector('.cw-rule-card[data-id="' + ruleId + '"]');
        if (card) {
            card.querySelectorAll('.cw-match-btn').forEach(function (btn) {
                btn.classList.toggle('active', btn.dataset.mode === mode);
            });
        }
    }

    /* ----------------------------------------------------------------
       Drag & Drop
    ---------------------------------------------------------------- */
    function setupDragDrop() {
        var list = document.getElementById('cw-rules-list');
        if (!list) return;

        list.querySelectorAll('.cw-rule-card').forEach(function (card) {
            card.addEventListener('dragstart', function (e) {
                dragSrcEl = card;
                e.dataTransfer.effectAllowed = 'move';
                setTimeout(function () { card.classList.add('is-dragging'); }, 0);
            });
            card.addEventListener('dragend', function () {
                card.classList.remove('is-dragging');
                list.querySelectorAll('.cw-rule-card').forEach(function (c) { c.classList.remove('drag-over'); });
                var ids = Array.from(list.querySelectorAll('.cw-rule-card')).map(function (c) { return c.dataset.id; });
                rules = ids.map(function (id) { return findRule(id); }).filter(Boolean);
                list.querySelectorAll('.cw-rule-priority').forEach(function (el, i) { el.textContent = '#' + (i + 1); });
            });
            card.addEventListener('dragover', function (e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                if (card === dragSrcEl) return;
                list.querySelectorAll('.cw-rule-card').forEach(function (c) { c.classList.remove('drag-over'); });
                card.classList.add('drag-over');
                var rect = card.getBoundingClientRect();
                if (e.clientY < rect.top + rect.height / 2) {
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
    function saveRules() {
        var btn    = document.getElementById('cw-save-rules');
        var status = document.getElementById('cw-save-status');
        if (!btn) return;

        var fb = {
            message: (document.getElementById('cw-fallback-message') || {}).value || '',
            url:     (document.getElementById('cw-fallback-url')     || {}).value || '',
        };

        btn.disabled    = true;
        btn.textContent = 'Saving\u2026';

        fetch(API_BASE + 'rules', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': NONCE },
            body: JSON.stringify({ rules: rules, fallback: fb }),
        })
        .then(function (res) { if (!res.ok) throw new Error('HTTP ' + res.status); return res.json(); })
        .then(function () { setStatus(status, '\u2705 Rules saved successfully!', 'success'); })
        .catch(function () { setStatus(status, '\u274C Save failed. Please try again.', 'error'); })
        .finally(function () { btn.disabled = false; btn.textContent = '\uD83D\uDCBE Save All Rules'; });
    }

    /* ----------------------------------------------------------------
       Utilities
    ---------------------------------------------------------------- */
    function findRule(id) { return rules.find(function (r) { return r.id === id; }) || null; }

    function uid(p) { return p + Date.now() + Math.floor(Math.random() * 1000); }

    function escAttr(s) {
        return String(s || '').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
    function escHtml(s) {
        return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function setStatus(el, msg, cls) {
        if (!el) return;
        el.textContent = msg;
        el.className   = 'cw-save-status ' + cls;
        setTimeout(function () { el.textContent = ''; el.className = 'cw-save-status'; }, 3500);
    }

}());
