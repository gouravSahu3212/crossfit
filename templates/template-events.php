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
            <p class="page-hero-label">Yhteisöllisyys kaiken perusta</p>
            <h1 class="page-hero-title">Tapahtumat ja kurssit</h1>
            <p class="page-hero-desc">Kursseja, kilpailuja ja yhteisöllisiä tapahtumia treenisalillamme. Kouvola Training Centerillä tapahtuu aina jotain – ja mukaan ovat tervetulleita niin jäsenet kuin muutkin liikunnasta kiinnostuneet!</p>
            <div class="page-hero-actions">
                <a href="#upcoming" class="btn-gold">Katso tulevia tapahtumia</a>
                <a href="/yhteystiedot" class="btn-outline">Tapahtumatiedustelut</a>
            </div>
        </div>
    </header>

    <div class="page-width">

        <!-- Intro Note -->
        <section class="page-section">
            <div class="callout-box">
                <h3>Yhteisöllinen tunnelma</h3>
                <p>Suurin osa yhteisöllisistä tapahtumistamme, alkeiskursseistamme ja rentohenkisistä kilpailuistamme on avoinna kaikille. Olet lämpimästi tervetullut mukaan, olitpa sitten salimme vakituinen treenaaja tai Kouvolassa viikonloppua viettävä vierailija!</p>
            </div>
        </section>

        <!-- Upcoming Events Grid -->
        <?php if ( cw_show_passes_section() ) : ?>
        <section id="upcoming" class="page-section">
            <?php
            $passes_title = get_post_meta( get_the_ID(), '_cw_passes_title', true );
            $passes_sc    = ! empty( $passes_title ) ? '[upcoming_events title="' . esc_attr( $passes_title ) . '"]' : '[upcoming_events]';
            echo do_shortcode( $passes_sc );
            ?>
        </section>

        <?php endif; ?>

        <!-- About / Community Section -->
        <?php echo cw_render_page_about(); ?>

        <!-- Events FAQ -->
        <?php echo cw_render_page_faq(); ?>


        <!-- CTA Strip -->
        <div class="page-cta-banner">
            <h2>Haluatko järjestää tapahtuman kanssamme?</h2>
            <p>Järjestämme mm. yksityisiä treenitapahtumia sekä yritysten virkistys- ja TYHY-päiviä.</p>
            <a href="/yhteystiedot" class="btn-gold">Ota yhteyttä</a>
        </div>

    </div>

</main>

<?php get_footer(); ?>