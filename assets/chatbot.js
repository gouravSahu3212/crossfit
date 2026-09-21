/* ================================================================
   Codyweb Training Recommendation Chatbot — Frontend Widget
   ================================================================
   Design: Dark athletic theme with gold accent.
   Stateless quiz. Fetches /wp-json/cw-chatbot/v1/config on first
   bubble open. Runs the recommendation engine client-side.
   ================================================================ */

(function () {
    'use strict';

    var API_BASE = (window.cwChatbot && window.cwChatbot.apiBase) || '/wp-json/cw-chatbot/v1/';

    /* State */
    var state = {
        isOpen:   false,
        isLoaded: false,
        config:   null,
        answers:  {},
        qIndex:   -1,
    };

    /* DOM refs */
    var $bubble, $panel, $messages, $closeBtn, $scrollBtn, $ctaBtn, $restartBtn;

    /* ----------------------------------------------------------------
       Bootstrap
    ---------------------------------------------------------------- */
    document.addEventListener('DOMContentLoaded', function () {
        $bubble     = document.getElementById('cw-chatbot-bubble');
        $panel      = document.getElementById('cw-chatbot-panel');
        $messages   = document.getElementById('cw-chatbot-messages');
        $closeBtn   = document.getElementById('cw-chatbot-close');
        $scrollBtn  = document.getElementById('cw-scroll-bottom');
        $ctaBtn     = document.getElementById('cw-cta-btn');
        $restartBtn = document.getElementById('cw-restart-btn');

        if (!$bubble || !$panel) return;

        $bubble.addEventListener('click', togglePanel);
        $closeBtn.addEventListener('click', closePanel);
        $restartBtn.addEventListener('click', restartQuiz);
        $scrollBtn.addEventListener('click', function () { scrollBottom(); });

        $messages.addEventListener('scroll', checkScrollBtn);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && state.isOpen) closePanel();
        });
    });

    /* ----------------------------------------------------------------
       Panel open / close
    ---------------------------------------------------------------- */
    function togglePanel() {
        if (state.isOpen) {
            closePanel();
            return;
        }
        state.isOpen = true;
        $panel.classList.add('is-open');
        $panel.setAttribute('aria-hidden', 'false');
        $bubble.setAttribute('aria-expanded', 'true');
        if (!state.isLoaded) {
            loadConfig();
        }
    }

    function closePanel() {
        state.isOpen = false;
        $panel.classList.remove('is-open');
        $panel.setAttribute('aria-hidden', 'true');
        $bubble.setAttribute('aria-expanded', 'false');
    }

    /* ----------------------------------------------------------------
       Load config from REST API
    ---------------------------------------------------------------- */
    function loadConfig() {
        showLoading();
        fetch(API_BASE + 'config')
            .then(function (res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(function (data) {
                state.config   = data;
                state.isLoaded = true;
                hideLoading();
                startQuiz();
            })
            .catch(function () {
                hideLoading();
                addIntro('Sorry, something went wrong loading the chatbot. Please try refreshing the page.');
            });
    }

    /* ----------------------------------------------------------------
       Quiz flow
    ---------------------------------------------------------------- */
    function startQuiz() {
        state.answers = {};
        state.qIndex  = -1;
        clearMessages();
        resetCta();

        var questions = (state.config && state.config.questions) || [];
        var count = questions.length;
        var introText = count
            ? 'Answer ' + count + ' quick questions and I\u2019ll point you to the most suitable training option.'
            : 'No questions have been configured yet.';

        addIntro(introText);

        if (count) {
            delay(500, function () { advanceToNext(); });
        }
    }

    function restartQuiz() {
        if (!state.isLoaded) return;
        startQuiz();
    }

    function advanceToNext() {
        state.qIndex++;
        var questions = (state.config && state.config.questions) || [];

        if (state.qIndex >= questions.length) {
            evaluateRules();
            return;
        }

        var q = questions[state.qIndex];
        delay(200, function () {
            addQuestion(q.text);
            delay(280, function () { addOptions(q); });
        });
    }

    function onAnswer(questionId, answerSlug, answerText, btn) {
        var wrap = btn.closest('.cw-opts-wrap');
        if (!wrap) return;

        state.answers[questionId] = answerSlug;

        /* Remove the options wrap and replace with a selected-answer bubble */
        wrap.remove();

        var sel = document.createElement('div');
        sel.className = 'cw-selected-ans';
        sel.textContent = answerText;
        $messages.appendChild(sel);
        requestAnimationFrame(function () { sel.classList.add('visible'); });
        scrollBottom();

        delay(340, function () { advanceToNext(); });
    }

    /* ----------------------------------------------------------------
       Recommendation engine
    ---------------------------------------------------------------- */
    function evaluateRules() {
        var cfg   = state.config || {};
        var rules = cfg.rules || [];
        var recs  = cfg.recommendations || [];

        var matched = null;
        for (var i = 0; i < rules.length; i++) {
            if (ruleMatches(rules[i])) {
                var recId = parseInt(rules[i].recommendation_id, 10);
                matched = recs.find(function (r) { return r.id === recId; }) || null;
                if (matched) break;
            }
        }

        delay(380, function () {
            if (matched) {
                showResult(matched);
            } else {
                showFallback(cfg.fallback || {});
            }
        });
    }

    function ruleMatches(rule) {
        var conditions = rule.conditions || [];
        if (!conditions.length) return false;

        var mode = rule.match_mode || 'all';
        if (mode === 'any') {
            return conditions.some(function (c) {
                return state.answers[c.question_id] === c.answer_slug;
            });
        }
        return conditions.every(function (c) {
            return state.answers[c.question_id] === c.answer_slug;
        });
    }

    /* ----------------------------------------------------------------
       Result rendering
    ---------------------------------------------------------------- */
    function showResult(rec) {
        var div = document.createElement('div');
        div.className = 'cw-result';
        div.innerHTML =
            '<div class="cw-result-eyebrow">WE RECOMMEND</div>' +
            '<div class="cw-result-name">' + escHtml(rec.title) + '</div>' +
            (rec.description ? '<div class="cw-result-desc">' + escHtml(rec.description) + '</div>' : '');
        $messages.appendChild(div);
        requestAnimationFrame(function () { div.classList.add('visible'); });
        scrollBottom();

        /* Update footer CTA */
        var label = 'SEE ' + rec.title.toUpperCase();
        $ctaBtn.textContent = label;
        if (rec.url) {
            $ctaBtn.href = rec.url;
            $ctaBtn.setAttribute('target', '_blank');
            $ctaBtn.setAttribute('rel', 'noopener noreferrer');
            $ctaBtn.classList.remove('is-disabled');
            $ctaBtn.removeAttribute('aria-disabled');
        } else {
            $ctaBtn.classList.remove('is-disabled');
            $ctaBtn.removeAttribute('aria-disabled');
        }
    }

    function showFallback(fb) {
        var message = fb.message || 'Get in touch with us to find the best training option for you!';
        var div = document.createElement('div');
        div.className = 'cw-result';
        div.innerHTML =
            '<div class="cw-result-eyebrow">LET\u2019S TALK</div>' +
            '<div class="cw-result-name">Not sure yet?</div>' +
            '<div class="cw-result-desc">' + escHtml(message) + '</div>';
        $messages.appendChild(div);
        requestAnimationFrame(function () { div.classList.add('visible'); });
        scrollBottom();

        if (fb.url) {
            $ctaBtn.textContent = 'CONTACT US';
            $ctaBtn.href = fb.url;
            $ctaBtn.setAttribute('target', '_blank');
            $ctaBtn.setAttribute('rel', 'noopener noreferrer');
            $ctaBtn.classList.remove('is-disabled');
            $ctaBtn.removeAttribute('aria-disabled');
        }
    }

    /* ----------------------------------------------------------------
       DOM helpers
    ---------------------------------------------------------------- */
    function addIntro(text) {
        var div = document.createElement('div');
        div.className = 'cw-intro-text';
        div.textContent = text;
        $messages.appendChild(div);
        requestAnimationFrame(function () { div.classList.add('visible'); });
        scrollBottom();
    }

    function addQuestion(text) {
        var div = document.createElement('div');
        div.className = 'cw-q-text';
        div.textContent = text;
        $messages.appendChild(div);
        requestAnimationFrame(function () { div.classList.add('visible'); });
        scrollBottom();
    }

    function addOptions(question) {
        var wrap = document.createElement('div');
        wrap.className = 'cw-opts-wrap';

        question.answers.forEach(function (answer) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'cw-opt';
            btn.textContent = answer.text;
            btn.addEventListener('click', function () {
                onAnswer(question.id, answer.slug, answer.text, btn);
            });
            wrap.appendChild(btn);
        });

        $messages.appendChild(wrap);
        requestAnimationFrame(function () { wrap.classList.add('visible'); });
        scrollBottom();
    }

    function clearMessages() {
        $messages.innerHTML = '';
    }

    function resetCta() {
        $ctaBtn.textContent = 'SEE TRAINING OPTIONS';
        $ctaBtn.href = '#';
        $ctaBtn.removeAttribute('target');
        $ctaBtn.removeAttribute('rel');
        $ctaBtn.classList.add('is-disabled');
        $ctaBtn.setAttribute('aria-disabled', 'true');
    }

    function scrollBottom() {
        $messages.scrollTop = $messages.scrollHeight;
    }

    function checkScrollBtn() {
        if (!$scrollBtn) return;
        var gap = $messages.scrollHeight - $messages.scrollTop - $messages.clientHeight;
        if (gap > 60) {
            $scrollBtn.classList.add('is-visible');
        } else {
            $scrollBtn.classList.remove('is-visible');
        }
    }

    function showLoading() {
        var div = document.createElement('div');
        div.id = 'cw-loading-msg';
        div.className = 'cw-loading-dots';
        div.innerHTML = '<span></span><span></span><span></span>';
        $messages.appendChild(div);
        requestAnimationFrame(function () { div.classList.add('visible'); });
        scrollBottom();
    }

    function hideLoading() {
        var el = document.getElementById('cw-loading-msg');
        if (el) el.remove();
    }

    /* ----------------------------------------------------------------
       Utilities
    ---------------------------------------------------------------- */
    function delay(ms, fn) { setTimeout(fn, ms); }

    function escHtml(s) {
        return String(s || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

}());