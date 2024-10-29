<?php

namespace AkariWorker\Inc;

class PageMeta {
    function __construct() {
        add_action("wp_head", function() {
            $this->googleAnalytics();
            $this->metaPixel();
        });

        // The custom script is given priority over any other scripts.
        add_action("wp_head", function() {
            $this->other();
        }, 0);
    }

    public function googleAnalytics() { 
        if (get_option("akari_worker_google_analytics_id")): ?>
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?= get_option("akari_worker_google_analytics_id") ?>"></script>

            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '<?= get_option("akari_worker_google_analytics_id") ?>');
            </script>
        <?php endif;
    }

    public function metaPixel() {
        if (get_option("akari_worker_meta_pixel_id")) { ?>
            <script>
                !function(f,b,e,v,n,t,s)
                {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            </script>

            <script>
                fbq('init', '<?= get_option("akari_worker_meta_pixel_id") ?>');
                fbq('track', 'PageView');
            </script>
        <?php }
    }

    public function other() { ?>
        <?php if (get_option("akari_worker_custom_script")): ?>
            <?= htmlspecialchars_decode(get_option("akari_worker_custom_script")) ?>
        <?php endif; ?>
    <?php }
}
