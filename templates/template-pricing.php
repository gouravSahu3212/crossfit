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
            <?php echo do_shortcode( '[pricing_table]' ); ?>
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

        <!-- About / Community Section -->
        <?php echo cw_render_page_about(); ?>

        <!-- Pricing FAQ -->
        <?php echo cw_render_page_faq(); ?>


        <!-- CTA Strip -->
        <div class="page-cta-banner">
            <h2>Start training today</h2>
            <p>Ready to experience coached functional fitness in Kouvola? Get started with a free trial workout.</p>
            <a href="/yhteystiedot" class="btn-gold">Book Free Trial</a>
        </div>

    </div>

</main>

<?php get_footer(); ?>
