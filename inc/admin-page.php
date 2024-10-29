<?php

namespace AkariWorker\Inc;

class AdminPage {
	function __construct() {
		add_action("admin_menu", fn() => $this->onAdminMenu());
		add_action("admin_post_akari_worker_settings", fn() => AdminPage::settingsSave());
	}

	public function onAdminMenu() {
		add_menu_page("Akari Worker Page", "Akari Worker", 'manage_options', 'akari-worker',
			fn() => $this->showAdminPage(),
			"/wp-content/plugins/akari-worker/assets/img/akari-icon.png?ver=" . \AkariWorker::getPluginData()["Version"]);
	}
	
	public function showAdminPage() {
		?>
		<style>
			.akari-worker-wrapper label {
				display: block;
				margin: 15px 0;
				font-weight: bold;
			}

			.akari-worker-title {
				font-weight: normal;
				font-size: 1.6em;
				margin-bottom: 20px;
			}
		</style>

		<div class="akari-worker-wrapper">
			<h1 class="akari-worker-title">Akari Worker Settings</h1>

			<form method="post" action="<?= admin_url("admin-post.php?action=akari_worker_settings") ?>">
				<h2>General Settings</h2>

				<label>Google gtag ID</label>
				<input name="gtag-id" type="text" placeholder="Enter Google gtag ID" value="<?= get_option("akari_worker_google_analytics_id", "") ?>">
				
				<label>Meta pixel ID</label>
				<input name="pixel-id" type="text" placeholder="Enter Meta pixel ID" value="<?= get_option("akari_worker_meta_pixel_id", "") ?>">

				<label>Available storage space (mb)</label>
				<input name="avail-storage" type="number" placeholder="Enter available storage" value="<?= get_option("akari_worker_available_storage", 10000) ?>">

				<h2>Advanced Settings</h2>

				<label>Custom script</label>
				<p>The custom script is given priority over any other scripts.</p>

				<?php if (get_option("akari_worker_custom_script")): ?>
					<textarea name="custom-script-content" rows="10" cols="50" placeholder="Enter script content here"><?= htmlspecialchars_decode(get_option("akari_worker_custom_script")) ?></textarea>
				<?php else: ?>
					<textarea name="custom-script-content" rows="10" cols="50" placeholder="Enter script content here"></textarea>
				<?php endif; ?>

				<input type="hidden" name="_wpnonce" value="<?= esc_attr(wp_create_nonce("akari_worker_settings")) ?>">

				<label></label>
				<input type="submit" value="Lagre">
			</form>
		</div>

		<?php
	}
	
	public static function settingsSave() {
		if (current_user_can("manage_options") && wp_verify_nonce($_POST["_wpnonce"], "akari_worker_settings")) {
			$gtag_id = isset($_POST["gtag-id"]) ? sanitize_text_field($_POST["gtag-id"]) : "";
			$pixel_id = isset($_POST["pixel-id"]) ? sanitize_text_field($_POST["pixel-id"]) : "";
			$avail_storage = isset($_POST["avail-storage"]) ? absint($_POST["avail-storage"]) : 10000;
			$custom_script_content = isset($_POST["custom-script-content"]) ? stripslashes($_POST["custom-script-content"]) : "";

			update_option("akari_worker_google_analytics_id", $gtag_id);
			update_option("akari_worker_meta_pixel_id", $pixel_id);
			update_option("akari_worker_available_storage", $avail_storage);
			update_option("akari_worker_custom_script", htmlspecialchars($custom_script_content));
		}

		wp_redirect("/wp-admin/admin.php?page=akari-worker");
	}
}
