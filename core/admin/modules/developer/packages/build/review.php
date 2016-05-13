<?php
	namespace BigTree;

	// Need to get the names for everything we're including
	$module_string = array();
	foreach (array_filter((array) $modules) as $m) {
		$module = $admin->getModule($m);
		if ($module) {
			$module_string[] = $module["name"];
		}
	}
	$template_string = array();
	foreach (array_filter((array) $templates) as $t) {
		$template = $cms->getTemplate($t);
		if ($template) {
			$template_string[] = $template["name"];
		}
	}
	$callout_string = array();
	foreach (array_filter((array) $callouts) as $c) {
		$callout = $admin->getCallout($c);
		if ($callout) {
			$callout_string[] = $callout["name"];
		}
	}
	$setting_string = array();
	foreach (array_filter((array) $settings) as $s) {
		$setting = $admin->getSetting($s);
		if ($setting) {
			$setting_string[] = $setting["name"];
		}
	}
	$feed_string = array();
	foreach (array_filter((array) $feeds) as $f) {
		$feed = $cms->getFeed($f);
		if ($feed) {
			$feed_string[] = $feed["name"];
		}
	}
	$field_string = array();
	foreach (array_filter((array) $field_types) as $f) {
		$field_type = $admin->getFieldType($f);
		if ($field_type) {
			$field_string[] = $field_type["name"];
		}
	}
	$table_string = array();
	foreach (array_filter((array) $tables) as $t) {
		list($table,$data) = explode("#",$t);
		if ($table) {
			$table_string[] = $table;
		}
	}
	$file_string = array();
	foreach (array_filter((array) $files) as $f) {
		$file = Router::replaceServerRoot($f);
		if ($file) {
			$file_string[] = $file;
		}
	}
?>
<div class="container package_review">
	<summary>
		<h2><?=Text::translate("Review Your Package")?></h2>
	</summary>
	<section>
		<fieldset>
			<h3><?=Text::translate("Package Information")?></h3>
			<label>
				<small><?=Text::translate("id")?></small>
				<?=$id?>
			</label>
			<label>
				<small><?=Text::translate("bigtree version compatibility")?></small>
				<?=$compatibility?>
			</label>
			<label>
				<small><?=Text::translate("title")?></small>
				<?=$title?>
			</label>
			<label>
				<small><?=Text::translate("version")?></small>
				<?=$version?>
			</label>
			<label>
				<small><?=Text::translate("description")?></small>
				<?=$description?>
			</label>
			<label>
				<small><?=Text::translate("keywords")?></small>
				<?=$keywords?>
			</label>
		</fieldset>
		<fieldset>
			<h3><?=Text::translate("Author Information")?></h3>
			<label>
				<small><?=Text::translate("name")?></small>
				<?=$author["name"]?>
			</label>
			<label>
				<small><?=Text::translate("email")?></small>
				<?=$author["email"]?>
			</label>
			<label>
				<small><?=Text::translate("website")?></small>
				<?=$author["url"]?>
			</label>
		</fieldset>
		<fieldset>
			<h3><?=Text::translate("Components")?></h3>
			<?php
				if (count($module_string)) {
			?>
			<label>
				<small><?=Text::translate("modules")?></small>
				<?=implode(", ",$module_string)?>
			</label>
			<?php
				}
				if (count($template_string)) {
			?>
			<label>
				<small><?=Text::translate("templates")?></small>
				<?=implode(", ",$template_string)?>
			</label>
			<?php
				}
				if (count($callout_string)) {
			?>
			<label>
				<small><?=Text::translate("callouts")?></small>
				<?=implode(", ",$callout_string)?>
			</label>
			<?php
				}
				if (count($setting_string)) {
			?>
			<label>
				<small><?=Text::translate("settings")?></small>
				<?=implode(", ",$setting_string)?>
			</label>
			<?php
				}
				if (count($feed_string)) {
			?>
			<label>
				<small><?=Text::translate("feeds")?></small>
				<?=implode(", ",$feed_string)?>
			</label>
			<?php
				}
				if (count($field_string)) {
			?>
			<label>
				<small><?=Text::translate("field types")?></small>
				<?=implode(", ",$field_string)?>
			</label>
			<?php
				}
				if (count($table_string)) {
			?>
			<label>
				<small><?=Text::translate("tables")?></small>
				<?=implode(", ",$table_string)?>
			</label>
			<?php
				}
			?>
		</fieldset>
		<?php
			if (count($file_string)) {
		?>
		<h3><?=Text::translate("Files")?></h3>
		<ul>
			<li><?=implode("</li><li>",$file_string)?></li>
		</ul>
		<?php
			}
		?>
	</section>
	<footer>
		<a class="button blue" href="<?=DEVELOPER_ROOT?>packages/build/create/"><?=Text::translate("Create")?></a>
		<a class="button" href="<?=DEVELOPER_ROOT?>packages/build/details/"><?=Text::translate("Edit")?></a>
	</footer>
</div>