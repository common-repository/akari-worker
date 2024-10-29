<?php

namespace AkariWorker\Inc;

class AdminNotice {
    private int $available_storage_mb;
    private $space_available;

    function __construct(int $available_storage_mb, int $space_used) {
        $this->available_storage_mb = $available_storage_mb;
        $this->space_available = $available_storage_mb - $space_used;

		if ($this->space_available < 0) {
			$this->space_available = 0;
		}
    }

    function render() {
		?>
			<div class="akari-worker-notice notice notice-info">
				<div class="admin-notice-text">
					<h1>Viktig informasjon</h1>

					<p>Endringer som utføres på nettsiden kan forårsake feil eller uønskede resultater.<br>
						Dette gjøres på eget ansvar og det vil påløpe kostnader hvis Akari skal korrigere eller gjenopprette feil på nettsiden.</p>

					<p>For spørsmål eller assistanse, kontakt <a href="mailto:support@akari.no">support@akari.no</a><br>
						Ved kritiske feil, kontakt oss på telefon <b>32 76 66 00</b> (tastevalg 1) under åpningstid (08:00 - 16:00).</p>

					<p><b>Tilgjengelig lagringsplass: </b><?= sprintf("%d / %d MB", $this->space_available, $this->available_storage_mb) ?></p>

					<?php if ($this->space_available == 0): ?>
						<p style="color: #e8362a;">Du har brukt opp tilgjengelig lagringsplass.
							<a href="https://akari.no/oppgrader-lagring" target="_blank" style="color: #e8362a;">Kjøp mer lagring.</a></p>
					<?php endif; ?>
				</div>

				<a class="akari-worker-website-link" href="https://akari.no">
					<img width="200" alt="Akari logo" src="/wp-content/plugins/akari-worker/assets/img/akari-wordmark.svg">
				</a>
			</div>
		<?php
    }
}
