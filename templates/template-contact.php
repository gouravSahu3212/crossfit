<?php
/**
 * Template Name: Contact
 * Description: Custom template for the Contact & Location page
 *
 * @package Codyweb_Child
 */

get_header();
?>

<main id="primary" class="site-main inner-page page-contact">

    <!-- Page Banner / Hero -->
    <header class="page-hero">
        <div class="page-width">
            <p class="page-hero-label">Get In Touch</p>
            <h1 class="page-hero-title">Contact &amp; Location</h1>
            <p class="page-hero-desc">Drop in, call or send us a message. We are here to answer your questions and welcome you to the gym.</p>
        </div>
    </header>

    <div class="page-width">

        <!-- Contact Split Layout -->
        <section class="page-section">
            <div class="contact-grid">

                <!-- Left Column: Details & Opening Hours -->
                <div>
                    <div class="contact-card">
                        <div class="contact-card-label">Street Address</div>
                        <p class="contact-card-val">
                            Salpaussel&auml;nkatu 42<br>
                            45100 Kouvola, Finland
                        </p>
                    </div>

                    <div class="contact-card">
                        <div class="contact-card-label">Email &amp; Phone</div>
                        <p class="contact-card-val">
                            Email: <a href="mailto:info@crossfitkouvola.com">info@crossfitkouvola.com</a><br>
                            Phone: <a href="tel:+358400000000">+358 40 000 0000</a>
                        </p>
                    </div>

                    <div class="contact-card">
                        <div class="contact-card-label">Opening &amp; Training Hours</div>
                        <p class="contact-card-val">
                            <strong>Monday &ndash; Friday:</strong> 06:00 &ndash; 20:30<br>
                            <strong>Saturday &ndash; Sunday:</strong> 10:00 &ndash; 13:00<br>
                            <span style="font-size: 13px; color: var(--text-muted); display: block; margin-top: 6px;">Coached classes run per the daily WODconnect schedule.</span>
                        </p>
                    </div>

                    <div class="callout-box" style="margin-top: 20px;">
                        <h3>Arrival &amp; Parking</h3>
                        <p>Free parking is available directly outside the gym building. Locker rooms, private shower stalls, and cubbies for workout gear are ready for your visit.</p>
                    </div>
                </div>

                <!-- Right Column: Contact Message Form -->
                <div>
                    <div class="contact-form-box">
                        <h3>Send Us A Message</h3>
                        <p>Interested in trying a free class, joining an On-Ramp course, or have a question? Leave a note below.</p>
                            <?php echo do_shortcode('[contact-form-7 id="45f9435" title="Contact form 1"]'); ?>                    
                        </div>
                </div>

            </div>
        </section>

        <!-- About / Community Section -->
        <?php echo cw_render_page_about(); ?>

        <?php echo cw_render_page_faq(); ?>

        <!-- Map / Directions Banner -->
        <div class="page-cta-banner">
            <h2>Find Us In Kouvola</h2>
            <p>Salpaussel&auml;nkatu 42, 45100 Kouvola &mdash; easily accessible by car, bike or public transit.</p>
            <a href="https://maps.google.com/?q=Salpausselankatu+42+45100+Kouvola" target="_blank" rel="noreferrer" class="btn-outline">Open In Google Maps</a>
        </div>

    </div>

</main>

<?php get_footer(); ?>
