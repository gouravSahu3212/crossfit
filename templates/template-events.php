<?php
/**
 * Template Name: Events
 * Description: Custom template for the Events and Courses page
 *
 * @package Codyweb_Child
 */

get_header();
?>

<main id="primary" class="site-main inner-page page-events">

    <!-- Page Banner / Hero -->
    <header class="page-hero">
        <div class="page-width">
            <p class="page-hero-label">Community &amp; Courses</p>
            <h1 class="page-hero-title">Events &amp; Courses</h1>
            <p class="page-hero-desc">Courses, competitions and community days at the box. Something is always happening at CrossFit Kouvola, open to members and non-members alike.</p>
            <div class="page-hero-actions">
                <a href="#upcoming" class="btn-gold">View Upcoming Events</a>
                <a href="/yhteystiedot" class="btn-outline">Inquire About An Event</a>
            </div>
        </div>
    </header>

    <div class="page-width">

        <!-- Intro Note -->
        <section class="page-section">
            <div class="callout-box">
                <h3>Open Community Atmosphere</h3>
                <p>Most of our community events, beginner courses, and friendly competitions are open to everyone, whether you train with us daily or are visiting Kouvola for the weekend.</p>
            </div>
        </section>

        <!-- Upcoming Events Grid -->
        <section id="upcoming" class="page-section">
            <div class="page-section-header">
                <p class="page-section-label">What's Next</p>
                <h2 class="page-section-title">Upcoming Courses &amp; Days</h2>
            </div>

            <?php echo do_shortcode( '[upcoming_events]' ); ?>
        </section>

        <!-- About / Community Section -->
        <?php echo cw_render_page_about(); ?>

        <!-- Events FAQ -->
        <?php echo cw_render_page_faq(); ?>


        <!-- CTA Strip -->
        <div class="page-cta-banner">
            <h2>Want to host an event with us?</h2>
            <p>We organize private training events, bachelor parties, and corporate recreation days.</p>
            <a href="/yhteystiedot" class="btn-gold">Contact Us</a>
        </div>

    </div>

</main>

<?php get_footer(); ?>
