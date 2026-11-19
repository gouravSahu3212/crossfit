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
            <p class="page-hero-label">Ota yhteyttä</p>
            <h1 class="page-hero-title">Yhteystiedot ja sijainti</h1>
            <p class="page-hero-desc">Tule käymään tai ota meihin yhteyttä sähköpostitse. Vastaamme mielellämme kysymyksiisi ja toivotamme sinut tervetulleeksi treenaamaan!</p>
        </div>
    </header>

    <div class="page-width">

        <!-- Contact Split Layout -->
        <section class="page-section">
            <div class="contact-grid">

                <!-- Left Column: Details & Opening Hours -->
                <div>
                    <div class="contact-card">
                        <div class="contact-card-label">Katuosoite</div>
                        <p class="contact-card-val">
                            Utinkatu 47<br>
                            45100 Kouvola
                        </p>
                    </div>

                    <div class="contact-card">
                        <div class="contact-card-label">Sähköposti</div>
                        <p class="contact-card-val">
                            Email: <a href="mailto:info@crossfitkouvola.com">info@kouvolatrainingcenter.fi</a><br>
                            Phone: <a href="tel:+358400000000">+358 40 000 0000</a>
                        </p>
                    </div>

                    <div class="contact-card">
                        <div class="contact-card-label">Aukioloajat</div>
                        <p class="contact-card-val">
                            <strong>Monday &ndash; Friday:</strong> 06:00 &ndash; 20:30<br>
                            <strong>Saturday &ndash; Sunday:</strong> 10:00 &ndash; 13:00<br>
                            <span style="font-size: 13px; color: var(--text-muted); display: block; margin-top: 6px;">Ohjatut tunnit järjestetään WODconnectin päivittäisen tuntikalenterin mukaisesti.</span>
                        </p>
                    </div>

                    <div class="callout-box" style="margin-top: 20px;">
                        <h3>Saapuminen ja pysäköinti</h3>
                        <p>Kuntosalin edustalla on maksuton pysäköintialue. Käytössäsi ovat myös pukuhuoneet, yksityiset suihkutilat sekä säilytyslokerot treenivarusteillesi.</p>
                    </div>
                </div>

                <!-- Right Column: Contact Message Form -->
                <div>
                    <div class="contact-form-box">
                        <h3>Lähetä meille viesti</h3>
                        <p>Haluaisitko tulla tutustumaan, osallistua On-Ramp-alkeiskurssille tai kysyä lisää palveluistamme? Jätä meille viesti alla olevalla lomakkeella!</p>
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
            <h2>Löydät meidät Kouvolasta</h2>
            <p>Utinkatu 47, 45200 Kouvola &mdash; Helposti saavutettavissa autolla, pyörällä tai julkisilla kulkuvälineillä.</p>
            <a href="https://maps.google.com/?q=Utinkatu+47+45200+Kouvola" target="_blank" rel="noreferrer" class="btn-outline">Avaa Google Mapsissa</a>
        </div>

    </div>

</main>

<?php get_footer(); ?>