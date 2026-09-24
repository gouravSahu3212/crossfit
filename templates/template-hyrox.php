<?php
/**
 * Template Name: Hyrox
 * Description: Custom template for the HYROX page
 *
 * @package Codyweb_Child
 */

get_header();
?>

<main id="primary" class="site-main inner-page page-hyrox">

    <!-- Page Banner / Hero -->
    <header class="page-hero">
        <div class="page-width">
            <p class="page-hero-label">Training</p>
            <h1 class="page-hero-title">HYROX</h1>
            <p class="page-hero-desc">Running plus eight functional stations. Train the engine, master the pacing. Coached HYROX classes for beginners and racers alike.</p>
            <div class="page-hero-actions">
                <a href="#stations" class="btn-gold">Explore The Stations</a>
                <a href="/hinnasto" class="btn-outline">View Pricing</a>
            </div>
        </div>
    </header>

    <div class="page-width">

        <!-- About HYROX -->
        <section class="page-section">
            <div class="split-grid">
                <div>
                    <div class="page-section-header">
                        <p class="page-section-label">Overview</p>
                        <h2 class="page-section-title">HYROX Training in Kouvola</h2>
                    </div>
                    <p style="font-size: 16px; line-height: 1.7; color: var(--text-muted); margin-bottom: 20px;">
                        HYROX is a global fitness race designed for everyone: eight one-kilometre runs, each followed by a functional workout station. It rewards consistency, pacing and honest work &mdash; not technical gymnastics or high-skill barbell movements.
                    </p>
                    <p style="font-size: 16px; line-height: 1.7; color: var(--text-muted);">
                        At CrossFit Kouvola, our classes focus on developing your aerobic base, station endurance, and tactical pacing so you can conquer both everyday training sessions and official HYROX race events.
                    </p>
                </div>
                <div class="callout-box" style="margin-top: 20px;">
                    <h3>Who is it for?</h3>
                    <p style="margin-bottom: 16px;">HYROX is accessible to all fitness levels. Whether you are running your first kilometer or preparing for a World Championship, every workout can be calibrated to your current capacity.</p>
                    <ul style="list-style: none; padding: 0; margin: 0; color: var(--text-muted); font-size: 14px; line-height: 1.8;">
                        <li><strong style="color: var(--text);">No Complex Gymnastics:</strong> Accessible movements anyone can learn.</li>
                        <li><strong style="color: var(--text);">Engine &amp; Stamina:</strong> Build unmatched cardiovascular endurance.</li>
                        <li><strong style="color: var(--text);">Race Preparation:</strong> Pacing strategies and simulated race conditions.</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- The 8 Stations -->
        <section id="stations" class="page-section">
            <div class="page-section-header">
                <p class="page-section-label">The Challenge</p>
                <h2 class="page-section-title">The 8 Functional Stations</h2>
                <p class="page-section-sub">Each station tests strength, stamina, and mental grit between 1 km running intervals.</p>
            </div>

            <div class="station-grid">
                <div class="station-item">
                    <div class="station-index">STATION 01</div>
                    <h3 class="station-name">1000m SkiErg</h3>
                    <p class="station-desc">Full body conditioning targeting the upper body, core, and posterior chain.</p>
                </div>
                <div class="station-item">
                    <div class="station-index">STATION 02</div>
                    <h3 class="station-name">50m Sled Push</h3>
                    <p class="station-desc">Heavy leg drive pushing the sled across turf with sustained power.</p>
                </div>
                <div class="station-item">
                    <div class="station-index">STATION 03</div>
                    <h3 class="station-name">50m Sled Pull</h3>
                    <p class="station-desc">Posterior chain test dragging the loaded sled backward with a rope.</p>
                </div>
                <div class="station-item">
                    <div class="station-index">STATION 04</div>
                    <h3 class="station-name">80m Burpee Broad Jumps</h3>
                    <p class="station-desc">Explosive bodyweight endurance moving forward repetition after repetition.</p>
                </div>
                <div class="station-item">
                    <div class="station-index">STATION 05</div>
                    <h3 class="station-name">1000m Rowing</h3>
                    <p class="station-desc">Aerobic stamina and stroke efficiency on the Concept2 indoor rower.</p>
                </div>
                <div class="station-item">
                    <div class="station-index">STATION 06</div>
                    <h3 class="station-name">200m Farmers Carry</h3>
                    <p class="station-desc">Grip strength, shoulder stability and core control under heavy dumbbells.</p>
                </div>
                <div class="station-item">
                    <div class="station-index">STATION 07</div>
                    <h3 class="station-name">100m Sandbag Lunges</h3>
                    <p class="station-desc">Quad and glute burn carrying a weighted sandbag across the floor.</p>
                </div>
                <div class="station-item">
                    <div class="station-index">STATION 08</div>
                    <h3 class="station-name">100 / 75 Wall Balls</h3>
                    <p class="station-desc">The ultimate finisher: deep squats into an explosive overhead target throw.</p>
                </div>
            </div>
        </section>

        <!-- Course Option -->
        <section class="page-section">
            <div class="cards-grid-2">
                <div class="feature-card">
                    <span class="event-card-tag">Specialized Program</span>
                    <h3 class="feature-card-title">HYROX Prep Course</h3>
                    <p class="feature-card-desc">Eight weeks of focused race pacing, transition drills, station endurance, and running intervals designed to get you competition ready.</p>
                    <div class="event-card-footer">
                        <span class="event-card-price">99,00 &euro;</span>
                        <a href="/yhteystiedot" class="btn-gold">Sign Up</a>
                    </div>
                </div>

                <div class="feature-card">
                    <span class="event-card-tag">Weekly Schedule</span>
                    <h3 class="feature-card-title">Weekly HYROX Classes</h3>
                    <p class="feature-card-desc">Included with your CrossFit Kouvola membership. Classes run Mondays, Wednesdays, and Fridays with coached progression.</p>
                    <div class="event-card-footer">
                        <span class="event-card-price">Included</span>
                        <a href="/hinnasto" class="btn-outline">View Passes</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- About / Community Section -->
        <?php echo cw_render_page_about(); ?>

        <!-- HYROX FAQ -->
        <?php echo cw_render_page_faq(); ?>


        <!-- CTA Strip -->
        <div class="page-cta-banner">
            <h2>Ready to build your engine?</h2>
            <p>Join a coached HYROX session and see why this format is taking over the fitness world.</p>
            <a href="/yhteystiedot" class="btn-gold">Book A Free Trial Class</a>
        </div>

    </div>

</main>

<?php get_footer(); ?>
