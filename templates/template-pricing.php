<?php
/**
 * Template Name: Pricing
 * Description: Custom template for the Pricing and Memberships page
 *
 * @package Codyweb_Child
 */

get_header();
?>

<main id="primary" class="site-main inner-page page-pricing">

    <!-- Page Banner / Hero -->
    <header class="page-hero">
        <div class="page-width">
            <p class="page-hero-label">Memberships &amp; Passes</p>
            <h1 class="page-hero-title">Pricing</h1>
            <p class="page-hero-desc">No joining fee. Every membership includes all coached classes (CrossFit, HYROX &amp; Easy WOD) plus open gym access.</p>
            <div class="page-hero-actions">
                <a href="#plans" class="btn-gold">Choose A Plan</a>
                <a href="/yhteystiedot" class="btn-outline">Book A Free Trial</a>
            </div>
        </div>
    </header>

    <div class="page-width">

        <!-- Pricing Cards Grid -->
        <section id="plans" class="page-section">
            <div class="pricing-grid">

                <!-- Full Membership -->
                <div class="pricing-card pricing-card--popular">
                    <span class="pricing-badge">Most Popular</span>
                    <h3 class="pricing-card-title">Full Membership</h3>
                    <div class="pricing-card-price">89 &euro; <span>/ month</span></div>
                    <ul class="pricing-card-features">
                        <li>Unlimited coached classes</li>
                        <li>CrossFit, HYROX &amp; Easy WOD</li>
                        <li>Open Gym access during open hours</li>
                        <li>WODconnect workout tracking</li>
                        <li>Continuous monthly billing</li>
                    </ul>
                    <a href="https://www.wodconnect.com/crossfit-kouvola" target="_blank" rel="noreferrer" class="btn-gold">Choose Plan</a>
                </div>

                <!-- 10-Session Pass -->
                <div class="pricing-card">
                    <h3 class="pricing-card-title">10-Session Pass</h3>
                    <div class="pricing-card-price">139 &euro; <span>/ 10 visits</span></div>
                    <ul class="pricing-card-features">
                        <li>10 class visits of your choice</li>
                        <li>Valid for 3 full months</li>
                        <li>Access to all class formats</li>
                        <li>Great for flexible training schedules</li>
                        <li>No ongoing commitment</li>
                    </ul>
                    <a href="https://www.wodconnect.com/crossfit-kouvola" target="_blank" rel="noreferrer" class="btn-outline">Get 10-Pass</a>
                </div>

                <!-- Student / Senior -->
                <div class="pricing-card">
                    <h3 class="pricing-card-title">Student / Senior</h3>
                    <div class="pricing-card-price">69 &euro; <span>/ month</span></div>
                    <ul class="pricing-card-features">
                        <li>Unlimited coached classes</li>
                        <li>CrossFit, HYROX &amp; Easy WOD</li>
                        <li>Open Gym access</li>
                        <li>Valid student / senior ID required</li>
                        <li>Continuous monthly billing</li>
                    </ul>
                    <a href="https://www.wodconnect.com/crossfit-kouvola" target="_blank" rel="noreferrer" class="btn-outline">Choose Plan</a>
                </div>

                <!-- Drop-In -->
                <div class="pricing-card">
                    <h3 class="pricing-card-title">Drop-In</h3>
                    <div class="pricing-card-price">20 &euro; <span>/ class</span></div>
                    <ul class="pricing-card-features">
                        <li>One single class visit</li>
                        <li>Visiting athletes welcome</li>
                        <li>Book easily before arriving</li>
                        <li>Shower &amp; locker facilities</li>
                        <li>Includes workout coaching</li>
                    </ul>
                    <a href="/yhteystiedot" class="btn-outline">Book Drop-In</a>
                </div>

            </div>
        </section>

        <!-- Billing Info & Sports Benefits -->
        <section class="page-section">
            <div class="split-grid">
                <div class="callout-box" style="margin: 0;">
                    <h3>How Billing Works</h3>
                    <p style="margin-bottom: 12px;">Monthly memberships are continuous recurring subscriptions. There is no long lock-in period; you can cancel or pause with one calendar month's written notice.</p>
                    <p>Passes and single drop-ins are one-time payments that never renew automatically. All bookings and invoices are seamlessly managed in WODconnect.</p>
                </div>

                <div class="callout-box" style="margin: 0;">
                    <h3>Tax-Free Sports Benefits</h3>
                    <p style="margin-bottom: 12px;">We proudly accept employer-provided sports and wellness benefits for all memberships, passes, and On-Ramp courses:</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px;">
                        <span style="background: rgba(255,255,255,0.06); padding: 6px 14px; border-radius: 4px; font-weight: 700; color: var(--gold);">Smartum</span>
                        <span style="background: rgba(255,255,255,0.06); padding: 6px 14px; border-radius: 4px; font-weight: 700; color: var(--gold);">Edenred</span>
                        <span style="background: rgba(255,255,255,0.06); padding: 6px 14px; border-radius: 4px; font-weight: 700; color: var(--gold);">ePassi</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing FAQ -->
        <section class="page-section page-faq">
            <div class="page-section-header">
                <p class="page-section-label">Questions</p>
                <h2 class="page-section-title">Pricing FAQ</h2>
            </div>
            <div class="page-faq-list">
                <div class="page-faq-item">
                    <button class="page-faq-q" type="button">
                        Is there a registration or joining fee?
                        <svg class="page-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </button>
                    <div class="page-faq-a">
                        <p>No joining fee whatsoever. You only pay for your active membership or pass, and you can begin training immediately.</p>
                    </div>
                </div>

                <div class="page-faq-item">
                    <button class="page-faq-q" type="button">
                        How can I freeze my membership if I get injured or travel?
                        <svg class="page-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </button>
                    <div class="page-faq-a">
                        <p>Memberships can be frozen for documented medical reasons (doctor's certificate) or prolonged travel upon request by emailing us at info@crossfitkouvola.com.</p>
                    </div>
                </div>

                <div class="page-faq-item">
                    <button class="page-faq-q" type="button">
                        Can I test a class before buying a membership?
                        <svg class="page-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </button>
                    <div class="page-faq-a">
                        <p>Yes! We offer a completely free trial session so you can experience our coaching, equipment, and community before deciding on a plan.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Strip -->
        <div class="page-cta-banner">
            <h2>Start training today</h2>
            <p>Ready to experience coached functional fitness in Kouvola? Get started with a free trial workout.</p>
            <a href="/yhteystiedot" class="btn-gold">Book Free Trial</a>
        </div>

    </div>

</main>

<?php get_footer(); ?>
