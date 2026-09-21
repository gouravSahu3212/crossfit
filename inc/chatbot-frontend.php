<?php
/**
 * Frontend: Inject the Training Chatbot floating bubble into wp_footer.
 *
 * @package Codyweb_Child
 */

/**
 * Output the chatbot bubble and panel HTML in the page footer.
 * JavaScript (assets/chatbot.js) handles all widget behaviour.
 */
function cw_chatbot_render_bubble() {
    ?>
    <div id="cw-chatbot-wrap" role="complementary" aria-label="Training Finder Chatbot">

        <button
            id="cw-chatbot-bubble"
            type="button"
            aria-label="Find Your Training"
            aria-expanded="false"
            aria-controls="cw-chatbot-panel"
        >
            <svg class="cw-bubble-icon" aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
            </svg>
            <span class="cw-bubble-text">FIND YOUR TRAINING</span>
        </button>

        <div
            id="cw-chatbot-panel"
            role="dialog"
            aria-modal="false"
            aria-label="Training Finder"
            aria-hidden="true"
        >
            <!-- Header -->
            <div class="cw-panel-header">
                <div class="cw-header-left">
                    <div class="cw-header-badge" aria-hidden="true">CFK</div>
                    <div class="cw-header-text">
                        <div class="cw-panel-title">FIND YOUR TRAINING</div>
                        <div class="cw-panel-subtitle">Quick training guide</div>
                    </div>
                </div>
                <button id="cw-chatbot-close" type="button" aria-label="Close Training Finder">&#x00D7;</button>
            </div>

            <!-- Messages + scroll button -->
            <div class="cw-messages-outer">
                <div id="cw-chatbot-messages" role="log" aria-live="polite" aria-atomic="false"></div>
                <button id="cw-scroll-bottom" class="cw-scroll-btn" type="button" aria-label="Scroll to bottom">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
                </button>
            </div>

            <!-- Fixed footer: CTA + Restart -->
            <div class="cw-panel-footer">
                <a id="cw-cta-btn" href="#" class="cw-cta-link is-disabled" aria-disabled="true">SEE TRAINING OPTIONS</a>
                <button id="cw-restart-btn" type="button" class="cw-restart-btn" title="Restart quiz" aria-label="Restart quiz">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8z"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'cw_chatbot_render_bubble' );