<?php
	namespace BigTree;
	
	/**
	 * @global array $bigtree
	 * @global bool $reply_all
	 */
	
	// Make sure the user has the right to see this message
	$parent = new Message(end($bigtree["path"]));

	// If the original message doesn't exist or you don't have access to it.
	if (!$parent->ID) {
		Auth::stop("This message either does not exist or you do not have permission to view it.",
					 Router::getIncludePath("admin/layouts/_error.php"));
	}

	$users = User::all("name ASC", true);
		
	if (isset($_SESSION["saved_message"])) {
		$send_to = $_SESSION["saved_message"]["send_to"];
		$subject = htmlspecialchars($_SESSION["saved_message"]["subject"]);
		$message = htmlspecialchars($_SESSION["saved_message"]["message"]);
		$error = true;
		unset($_SESSION["saved_message"]);
	} else {
		$subject = "RE: ".$parent->Subject;
		$message = "";
		$error = false;
		
		// Generate the recipient names from the parent if we're replying to all, otherwise, just use the sender.
		$send_to = array();
		
		if (!empty($reply_all) || $parent->Sender == Auth::user()->ID) {
			foreach ($parent->Recipients as $recipient) {
				if ($recipient != Auth::user()->ID) {
					$send_to[] = $recipient;
				}
			}
			
		}
		
		$send_to[] = $parent->Sender;
	}
?>
<div class="container">
	<form method="post" action="<?=ADMIN_ROOT?>dashboard/messages/create-reply/" id="message_form">
		<input type="hidden" name="response_to" value="<?=htmlspecialchars(end($bigtree["path"]))?>" />
		<section>
			<p<?php if (!$error) { ?> style="display: none;"<?php } ?> class="error_message"><?=Text::translate("Errors found! Please fix the highlighted fields before submitting.")?></p>
			<fieldset id="send_to"<?php if ($error && !count($send_to)) { ?> class="form_error"<?php } ?>>
				<label class="required"><?=Text::translate("Send To")?><?php if ($error && !count($send_to)) { ?><span class="form_error_reason"><?=Text::translate("Required")?></span><?php } ?></label>
				<div class="multi_widget many_to_many">
					<section style="display: none;">
						<p><?=Text::translate('No users selected. Click "Add User" to add a user to the list.')?></p>
					</section>
					<ul>
						<?php
							$x = 0;
							if (is_array($send_to)) {
								foreach ($send_to as $id) {
						?>
						<li>
							<input type="hidden" name="send_to[<?=$x?>]" value="<?=htmlspecialchars($id)?>" />
							<p><?=htmlspecialchars($users[$id]["name"])?></p>
							<a href="#" class="icon_delete"></a>
						</li>
						<?php
									$x++;
								}
							}
						?>
					</ul>
					<footer>
						<select>
							<?php
								foreach ($users as $id => $user) {
									if ($id != Auth::user()->ID) {
							?>
							<option value="<?=$id?>"><?=htmlspecialchars($user["name"])?></option>
							<?php
									}
								}
							?>
						</select>
						<a href="#" class="add button"><span class="icon_small icon_small_add"></span><?=Text::translate("Add User")?></a>
					</footer>
				</div>
			</fieldset>
			<fieldset<?php if ($error && !$subject) { ?> class="form_error"<?php } ?>>
				<label for="message_field_subject" class="required"><?=Text::translate("Subject")?><?php if ($error && !$subject) { ?><span class="form_error_reason"><?=Text::translate("Required")?></span><?php } ?></label>
				<input id="message_field_subject" type="text" name="subject"  class="required" value="<?=$subject?>" />
			</fieldset>
			<fieldset<?php if ($error && !$message) { ?> class="form_error"<?php } ?>>
				<label for="message_field_message" class="required"><?=Text::translate("Message")?><?php if ($error && !$message) { ?><span class="form_error_reason"><?=Text::translate("Required")?></span><?php } ?></label>
				<textarea id="message_field_message" name="message" id="message" class="required"><?=$message?></textarea>
			</fieldset>
		</section>
		<footer>
			<a href="../" class="button"><?=Text::translate("Discard")?></a>
			<input type="submit" class="button blue" value="<?=Text::translate("Send Message", true)?>" />
		</footer>
	</form>
</div>
<?php
	$bigtree["html_fields"] = array("message");
	include Router::getIncludePath("admin/layouts/_html-field-loader.php");
?>
<script>
	BigTreeManyToMany({
		id: "send_to",
		count: <?=$x?>,
		key: "send_to",
		sortable: false
	});
	
	BigTreeFormValidator("#message_form");
</script>