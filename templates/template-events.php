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

        <!-- Events FAQ -->
        <section class="page-section page-faq">
            <div class="page-section-header">
                <p class="page-section-label">Questions</p>
                <h2 class="page-section-title">Events FAQ</h2>
            </div>
            <div class="page-faq-list">
                <div class="page-faq-item">
                    <button class="page-faq-q" type="button">
                        Can non-members participate in events?
                        <svg class="page-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </button>
                    <div class="page-faq-a">
                        <p>Yes! Most of our events, beginner workshops, and Saturday Team WODs are open to non-members unless stated otherwise.</p>
                    </div>
                </div>

                <div class="page-faq-item">
                    <button class="page-faq-q" type="button">
                        How do I reserve a spot for a workshop or course?
                        <svg class="page-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </button>
                    <div class="page-faq-a">
                        <p>You can sign up directly via WODconnect if you already have an account, or send us a message through our Contact page to reserve a spot.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Strip -->
        <div class="page-cta-banner">
            <h2>Want to host an event with us?</h2>
            <p>We organize private training events, bachelor parties, and corporate recreation days.</p>
            <a href="/yhteystiedot" class="btn-gold">Contact Us</a>
        </div>

    </div>

</main>

<?php get_footer(); ?>
