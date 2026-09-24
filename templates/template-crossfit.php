<?php
/**
 * Template Name: CrossFit
 * Description: Custom template for the CrossFit page
 *
 * @package Codyweb_Child
 */

get_header();
?>

<main id="primary" class="site-main inner-page page-crossfit">

    <!-- Page Banner / Hero -->
    <header class="page-hero">
        <div class="page-width">
            <p class="page-hero-label">Training</p>
            <h1 class="page-hero-title">CrossFit</h1>
            <p class="page-hero-desc">Strength, gymnastics and conditioning in a coached group &mdash; every day different. Start with On-Ramp, then join the daily WOD.</p>
            <div class="page-hero-actions">
                <a href="#onramp" class="btn-gold">Beginner On-Ramp Course</a>
                <a href="#classes" class="btn-outline">Class Formats</a>
            </div>
        </div>
    </header>

    <div class="page-width">

        <!-- Intro / Philosophy -->
        <section class="page-section">
            <div class="split-grid">
                <div>
                    <div class="page-section-header">
                        <p class="page-section-label">Our Philosophy</p>
                        <h2 class="page-section-title">CrossFit Training in Kouvola</h2>
                    </div>
                    <p style="font-size: 16px; line-height: 1.7; color: var(--text-muted); margin-bottom: 20px;">
                        CrossFit is constantly varied, functional movement performed at high intensity. In practice, this means you develop genuine strength, cardiovascular capacity, mobility, and gymnastic control across every workout.
                    </p>
                    <p style="font-size: 16px; line-height: 1.7; color: var(--text-muted);">
                        Every class is led by certified coaches who explain the movements, guide your warm-up, refine your barbell technique, and tailor the weights and repetitions so you train safely and effectively.
                    </p>
                </div>
                <div id="onramp" class="callout-box" style="margin-top: 10px;">
                    <h3>The On-Ramp Beginner Course</h3>
                    <p style="margin-bottom: 16px;">Training always starts with our On-Ramp course. In four weeks, three sessions a week, you learn the core barbell, gymnastics, and kettlebell movements calmly and safely before joining regular daily WODs.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 14px; border-top: 1px solid var(--border);">
                        <span style="font-family: var(--font-head); font-size: 24px; font-weight: 800; color: var(--gold);">132,00 &euro;</span>
                        <a href="/yhteystiedot" class="btn-gold">Register Now</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Class Types Grid -->
        <section id="classes" class="page-section">
            <div class="page-section-header">
                <p class="page-section-label">Schedule &amp; Styles</p>
                <h2 class="page-section-title">Class Formats</h2>
                <p class="page-section-sub">A balanced variety of coached classes designed to build well-rounded athletic capacity.</p>
            </div>

            <div class="cards-grid-3">
                <div class="feature-card">
                    <div class="feature-card-num">01</div>
                    <h3 class="feature-card-title">CrossFit WOD</h3>
                    <p class="feature-card-desc">The cornerstone of our programming. Combines weightlifting, gymnastics, and high-intensity conditioning. Scaled to every participant.</p>
                    <span style="font-size: 13px; color: var(--gold); font-weight: 600;">60 minutes &bull; Coached</span>
                </div>

                <div class="feature-card">
                    <div class="feature-card-num">02</div>
                    <h3 class="feature-card-title">Easy WOD</h3>
                    <p class="feature-card-desc">A lighter-intensity, low-impact conditioning session focusing on aerobic capacity, sweating, and recovery without heavy barbell loads.</p>
                    <span style="font-size: 13px; color: var(--gold); font-weight: 600;">60 minutes &bull; Accessible</span>
                </div>

                <div class="feature-card">
                    <div class="feature-card-num">03</div>
                    <h3 class="feature-card-title">Weightlifting</h3>
                    <p class="feature-card-desc">Dedicated technique and strength focus on the Olympic lifts (Snatch and Clean &amp; Jerk) alongside squatting and pulling power.</p>
                    <span style="font-size: 13px; color: var(--gold); font-weight: 600;">60 minutes &bull; Technical</span>
                </div>

                <div class="feature-card">
                    <div class="feature-card-num">04</div>
                    <h3 class="feature-card-title">Gymnastics</h3>
                    <p class="feature-card-desc">Progressions for pull-ups, toes-to-bar, handstands, ring muscle-ups, and core body control regardless of your current strength.</p>
                    <span style="font-size: 13px; color: var(--gold); font-weight: 600;">60 minutes &bull; Skill Focus</span>
                </div>

                <div class="feature-card">
                    <div class="feature-card-num">05</div>
                    <h3 class="feature-card-title">Team WOD</h3>
                    <p class="feature-card-desc">Saturday morning partner and team workouts. High energy, friendly atmosphere, and coffee with the community afterwards.</p>
                    <span style="font-size: 13px; color: var(--gold); font-weight: 600;">Saturdays &bull; Community</span>
                </div>

                <div class="feature-card">
                    <div class="feature-card-num">06</div>
                    <h3 class="feature-card-title">Open Gym</h3>
                    <p class="feature-card-desc">Free gym time to work on mobility, technique weaknesses, individual programming, or make up a missed workout.</p>
                    <span style="font-size: 13px; color: var(--gold); font-weight: 600;">Daily hours &bull; Independent</span>
                </div>
            </div>
        </section>

        <!-- What an Hour Looks Like -->
        <section class="page-section">
            <div class="page-section-header">
                <p class="page-section-label">The Experience</p>
                <h2 class="page-section-title">Structure of a 60-Minute Class</h2>
            </div>
            <div class="cards-grid-4">
                <div class="feature-card">
                    <div class="feature-card-num">10 min</div>
                    <h3 class="feature-card-title">General Warm-Up</h3>
                    <p class="feature-card-desc">Heart rate activation, joint mobilization, and movement preparation.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card-num">15 min</div>
                    <h3 class="feature-card-title">Strength / Skill</h3>
                    <p class="feature-card-desc">Coached progression on barbells, gymnastics, or unilateral strength.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card-num">25 min</div>
                    <h3 class="feature-card-title">The Workout (WOD)</h3>
                    <p class="feature-card-desc">The high-energy workout of the day with scaled weights and reps for all.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card-num">10 min</div>
                    <h3 class="feature-card-title">Cool-Down</h3>
                    <p class="feature-card-desc">Heart rate recovery, targeted stretching, and high fives.</p>
                </div>
            </div>
        </section>

        <!-- CrossFit FAQ -->
        <?php echo cw_render_page_faq(); ?>


        <!-- CTA Strip -->
        <div class="page-cta-banner">
            <h2>Ready to start your journey?</h2>
            <p>Sign up for the upcoming On-Ramp beginner course or book a free trial workout.</p>
            <a href="/yhteystiedot" class="btn-gold">Book Free Trial</a>
        </div>

    </div>

</main>

<?php get_footer(); ?>
