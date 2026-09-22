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

            <div class="cards-grid-3">
                <div class="event-card">
                    <div>
                        <span class="event-card-tag">Course &bull; 4 Weeks</span>
                        <h3 class="event-card-title">Autumn On-Ramp Course</h3>
                        <p class="event-card-desc">Four weeks of coached fundamentals. Three sessions each week in small groups focusing on safety, technique, and barbell fundamentals. No previous experience needed.</p>
                    </div>
                    <div class="event-card-footer">
                        <span class="event-card-price">132,00 &euro;</span>
                        <a href="/crossfit" class="btn-gold">Read More</a>
                    </div>
                </div>

                <div class="event-card">
                    <div>
                        <span class="event-card-tag">Specialized &bull; 8 Weeks</span>
                        <h3 class="event-card-title">HYROX Prep Course</h3>
                        <p class="event-card-desc">Eight weeks of race-specific pacing, station work, running intervals and team strategy designed to prepare you for official race day.</p>
                    </div>
                    <div class="event-card-footer">
                        <span class="event-card-price">99,00 &euro;</span>
                        <a href="/hyrox" class="btn-gold">Read More</a>
                    </div>
                </div>

                <div class="event-card">
                    <div>
                        <span class="event-card-tag">Community &bull; Every Saturday</span>
                        <h3 class="event-card-title">Team WOD Saturday</h3>
                        <p class="event-card-desc">Open community session. Fun partner and team workouts followed by coffee. Free for trial participants and open to all fitness levels.</p>
                    </div>
                    <div class="event-card-footer">
                        <span class="event-card-price" style="color: #4ade80;">Free</span>
                        <a href="/yhteystiedot" class="btn-gold">Join Class</a>
                    </div>
                </div>

                <div class="event-card">
                    <div>
                        <span class="event-card-tag">Competition &bull; In-House</span>
                        <h3 class="event-card-title">Winter Box Throwdown</h3>
                        <p class="event-card-desc">Our annual friendly in-house competition. Scaled and Rx divisions, great music, and celebration of the year's progress.</p>
                    </div>
                    <div class="event-card-footer">
                        <span class="event-card-price">TBA</span>
                        <a href="/yhteystiedot" class="btn-outline">Get Info</a>
                    </div>
                </div>

                <div class="event-card">
                    <div>
                        <span class="event-card-tag">Workshop &bull; 2 Hours</span>
                        <h3 class="event-card-title">Olympic Lifting Clinic</h3>
                        <p class="event-card-desc">Deep-dive masterclass focusing on the Snatch and Clean &amp; Jerk turnover, bar path, and mobility drills.</p>
                    </div>
                    <div class="event-card-footer">
                        <span class="event-card-price">35,00 &euro;</span>
                        <a href="/yhteystiedot" class="btn-outline">Register</a>
                    </div>
                </div>

                <div class="event-card">
                    <div>
                        <span class="event-card-tag">Corporate &bull; Private</span>
                        <h3 class="event-card-title">Company Wellness Days</h3>
                        <p class="event-card-desc">Custom coached fitness sessions, team building workouts, and wellness lectures tailored for your workplace team.</p>
                    </div>
                    <div class="event-card-footer">
                        <span class="event-card-price">Custom</span>
                        <a href="/yhteystiedot" class="btn-gold">Book Session</a>
                    </div>
                </div>
            </div>
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
