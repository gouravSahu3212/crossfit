<?php
/*
Template Name: Home Page
*/

get_header();
?>

<main id="primary" class="site-main home-page">

    <!-- ==================== 1. SPLIT HERO ==================== -->
    <section class="hp-hero">
        <div class="hp-hero-panel hp-hero-panel--hyrox">
            <h2 class="hp-hero-heading">Start HYROX</h2>
            <a href="/hyrox" class="btn-gold">Read more</a>
        </div>
        <div class="hp-hero-panel hp-hero-panel--crossfit">
            <h2 class="hp-hero-heading">Start CrossFit</h2>
            <a href="/crossfit" class="btn-gold">Read more</a>
        </div>
    </section>

    <!-- ==================== 2. MOTIVATIONAL QUOTE ==================== -->
    <section class="hp-quote">
        <div class="page-width">
            <h2 class="hp-quote-text">You don't need to be fit to start. You only need to decide to walk through the door.</h2>
            <p class="hp-quote-body">We are a coached functional training gym in Kouvola. CrossFit, HYROX and Easy WOD classes run every day of the week and every workout is scaled to the person doing it &mdash; first-timers and competitors train side by side in the same hour.</p>
        </div>
    </section>

    <!-- ==================== 3. CURRENT PASSES & COURSES ==================== -->
    <section class="hp-passes">
        <div class="page-width">
            <div class="hp-passes-header">
                <p class="hp-section-label">What's on</p>
                <h2 class="hp-section-title">Current: passes and courses</h2>
            </div>
            <div class="hp-cards-grid">

                <div class="hp-card">
                    <h3 class="hp-card-title">Autumn On-Ramp course</h3>
                    <p class="hp-card-desc">Four weeks of coached basics, three sessions a week. No experience needed.</p>
                    <p class="hp-card-price">132,00 &euro;</p>
                    <a href="/crossfit" class="btn-gold">Read more</a>
                </div>

                <div class="hp-card">
                    <h3 class="hp-card-title">HYROX Prep course</h3>
                    <p class="hp-card-desc">Eight weeks of race pacing, station work and running intervals.</p>
                    <p class="hp-card-price">99,00 &euro;</p>
                    <a href="/hyrox" class="btn-gold">Read more</a>
                </div>

                <div class="hp-card">
                    <h3 class="hp-card-title">Super 10-session pass</h3>
                    <p class="hp-card-desc">Ten visits to CrossFit, HYROX or Easy WOD classes. Valid for three months.</p>
                    <p class="hp-card-price">139,00 &euro;</p>
                    <a href="/hinnasto" class="btn-gold">Read more</a>
                </div>

            </div>
        </div>
    </section>

    <!-- ==================== 4. WEEKLY SCHEDULE ==================== -->
    <section class="hp-schedule">
        <div class="page-width">
            <div class="hp-schedule-header">
                <p class="hp-section-label">Timetable</p>
                <h2 class="hp-section-title">Weekly Schedule</h2>
                <p class="hp-section-sub">All classes for the week. Book your spot in WODconnect.</p>
            </div>
            <div class="hp-schedule-grid">

                <div class="hp-day">
                    <div class="hp-day-name">Monday</div>
                    <div class="hp-day-slots">
                        <div class="hp-slot"><span class="hp-slot-time">06:30</span><span class="hp-slot-class">CrossFit WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">09:30</span><span class="hp-slot-class">Easy WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">16:30</span><span class="hp-slot-class">CrossFit WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">17:30</span><span class="hp-slot-class">HYROX</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">18:30</span><span class="hp-slot-class">CrossFit WOD</span></div>
                    </div>
                </div>

                <div class="hp-day">
                    <div class="hp-day-name">Tuesday</div>
                    <div class="hp-day-slots">
                        <div class="hp-slot"><span class="hp-slot-time">06:30</span><span class="hp-slot-class">CrossFit WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">16:30</span><span class="hp-slot-class">Weightlifting</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">17:30</span><span class="hp-slot-class">CrossFit WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">18:30</span><span class="hp-slot-class">On-Ramp</span></div>
                    </div>
                </div>

                <div class="hp-day">
                    <div class="hp-day-name">Wednesday</div>
                    <div class="hp-day-slots">
                        <div class="hp-slot"><span class="hp-slot-time">06:30</span><span class="hp-slot-class">CrossFit WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">09:30</span><span class="hp-slot-class">Easy WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">16:30</span><span class="hp-slot-class">HYROX</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">17:30</span><span class="hp-slot-class">CrossFit WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">18:30</span><span class="hp-slot-class">CrossFit WOD</span></div>
                    </div>
                </div>

                <div class="hp-day">
                    <div class="hp-day-name">Thursday</div>
                    <div class="hp-day-slots">
                        <div class="hp-slot"><span class="hp-slot-time">06:30</span><span class="hp-slot-class">CrossFit WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">16:30</span><span class="hp-slot-class">Gymnastics</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">17:30</span><span class="hp-slot-class">CrossFit WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">18:30</span><span class="hp-slot-class">On-Ramp</span></div>
                    </div>
                </div>

                <div class="hp-day">
                    <div class="hp-day-name">Friday</div>
                    <div class="hp-day-slots">
                        <div class="hp-slot"><span class="hp-slot-time">06:30</span><span class="hp-slot-class">CrossFit WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">16:30</span><span class="hp-slot-class">CrossFit WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">17:30</span><span class="hp-slot-class">HYROX</span></div>
                    </div>
                </div>

                <div class="hp-day">
                    <div class="hp-day-name">Saturday</div>
                    <div class="hp-day-slots">
                        <div class="hp-slot"><span class="hp-slot-time">10:00</span><span class="hp-slot-class">Team WOD</span></div>
                        <div class="hp-slot"><span class="hp-slot-time">11:00</span><span class="hp-slot-class">Open Gym</span></div>
                    </div>
                </div>

                <div class="hp-day">
                    <div class="hp-day-name">Sunday</div>
                    <div class="hp-day-slots">
                        <div class="hp-slot"><span class="hp-slot-time">11:00</span><span class="hp-slot-class">Open Gym</span></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ==================== 5. PRICING ==================== -->
    <section class="hp-pricing">
        <div class="page-width">
            <div class="hp-pricing-header">
                <p class="hp-section-label">Plans</p>
                <h2 class="hp-section-title">Pricing</h2>
            </div>
            <div class="hp-pricing-grid">

                <div class="hp-price-card hp-price-card--featured">
                    <h3 class="hp-price-name">Full membership</h3>
                    <p class="hp-price-amount">89 &euro; <span>/ kk</span></p>
                    <ul class="hp-price-features">
                        <li>Unlimited classes</li>
                        <li>CrossFit, HYROX &amp; Easy WOD</li>
                        <li>Open gym access</li>
                    </ul>
                </div>

                <div class="hp-price-card">
                    <h3 class="hp-price-name">10-session pass</h3>
                    <p class="hp-price-amount">139 &euro;</p>
                    <ul class="hp-price-features">
                        <li>10 class visits</li>
                        <li>Valid for 3 months</li>
                        <li>All class types</li>
                    </ul>
                </div>

                <div class="hp-price-card">
                    <h3 class="hp-price-name">Student / senior</h3>
                    <p class="hp-price-amount">69 &euro; <span>/ kk</span></p>
                    <ul class="hp-price-features">
                        <li>Unlimited classes</li>
                        <li>Valid ID required</li>
                        <li>Open gym access</li>
                    </ul>
                </div>

                <div class="hp-price-card">
                    <h3 class="hp-price-name">Drop-in</h3>
                    <p class="hp-price-amount">20 &euro;</p>
                    <ul class="hp-price-features">
                        <li>One single class</li>
                        <li>Visiting athletes welcome</li>
                        <li>Book in advance</li>
                    </ul>
                </div>

            </div>
            <div class="hp-pricing-link">
                <a href="/hinnasto" class="btn-outline">View all pricing</a>
            </div>
        </div>
    </section>

    <!-- ==================== 6. ABOUT / COMMUNITY ==================== -->
    <section class="hp-about">
        <div class="page-width">
            <div class="hp-about-inner">
                <p class="hp-section-label">Our story</p>
                <h2 class="hp-section-title">Training at CrossFit Kouvola</h2>
                <p class="hp-about-body">Our gym has been part of the Kouvola community since 2013. Everything we do is built around coached group classes, honest work and a room where people know your name.</p>
            </div>
        </div>
    </section>

    <!-- ==================== 7. FAQ ==================== -->
    <section class="hp-faq">
        <div class="page-width">
            <div class="hp-faq-header">
                <p class="hp-section-label">Questions</p>
                <h2 class="hp-section-title">FAQ</h2>
            </div>
            <div class="hp-faq-list">

                <div class="hp-faq-item">
                    <button class="hp-faq-q" type="button">
                        Do I need to be in shape before starting?
                        <svg class="hp-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </button>
                    <div class="hp-faq-a">
                        <p>No. Every workout is scaled to your level and our coaches adjust the movements and loads for you from day one.</p>
                    </div>
                </div>

                <div class="hp-faq-item">
                    <button class="hp-faq-q" type="button">
                        What is the difference between CrossFit and HYROX?
                        <svg class="hp-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </button>
                    <div class="hp-faq-a">
                        <p>CrossFit focuses on varied functional movements at high intensity. HYROX is a specific race format combining running with functional workout stations. Both are coached and suitable for all levels.</p>
                    </div>
                </div>

                <div class="hp-faq-item">
                    <button class="hp-faq-q" type="button">
                        Can I try a class before committing?
                        <svg class="hp-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </button>
                    <div class="hp-faq-a">
                        <p>Absolutely! We offer a free trial class so you can experience a session before signing up.</p>
                    </div>
                </div>

                <div class="hp-faq-item">
                    <button class="hp-faq-q" type="button">
                        What should I bring to my first class?
                        <svg class="hp-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </button>
                    <div class="hp-faq-a">
                        <p>Comfortable workout clothes, indoor training shoes and a water bottle. We have all the equipment you need at the gym.</p>
                    </div>
                </div>

                <div class="hp-faq-item">
                    <button class="hp-faq-q" type="button">
                        How do I book classes?
                        <svg class="hp-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </button>
                    <div class="hp-faq-a">
                        <p>All bookings are made through WODconnect. You'll receive access when you sign up for a membership or pass.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<?php get_footer();