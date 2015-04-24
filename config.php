<div class="caldera-config-group">
	<label><?php echo __('From Name', 'cf-validate-email'); ?> </label>
	<div class="caldera-config-field">
		<input type="text" class="block-input field-config required" name="{{_name}}[from_name]" value="{{from_name}}">
		<p class="description"><?php _e('The name the email will come from. i.e the site or you. (Not be the senders name).', 'cf-validate-email'); ?></p>
	</div>
</div>

<div class="caldera-config-group">
	<label><?php echo __('From Email', 'cf-validate-email'); ?> </label>
	<div class="caldera-config-field">
		<input type="text" class="block-input field-config required" name="{{_name}}[from_email]" value="{{from_email}}">
		<p class="description"><?php _e('The email address the email will come from i.e no-reply@example.com (Not be the senders email).', 'cf-validate-email'); ?></p>
	</div>
</div>
<div class="caldera-config-group">
	<label><?php echo __('Subject', 'cf-validate-email'); ?> </label>
	<div class="caldera-config-field">
		<input type="text" class="block-input field-config required magic-tag-enabled" name="{{_name}}[subject]" value="{{#if subject}}{{subject}}{{else}}Please verify to continue.{{/if}}">
		<p class="description"><?php _e('The subject of the verification email.', 'cf-validate-email'); ?></p>
	</div>
</div>

<div class="caldera-config-group">
	<label><?php echo __('Validation Email', 'cf-validate-email'); ?> </label>
	<div class="caldera-config-field">
		{{{_field slug="email" type="email" exclude="system" required="true"}}}
		<p class="description"><?php _e('The email address the email will be sent to. The email field to be verified.', 'cf-validate-email'); ?></p>
	</div>
</div>
<div class="caldera-config-group">
	<label><?php echo __('Verify Notice', 'cf-validate-email'); ?> </label>
	<div class="caldera-config-field">
		<input type="text" class="block-input field-config required magic-tag-enabled" name="{{_name}}[notice]" value="{{#if notice}}{{notice}}{{else}}Please check your email to complete submission.{{/if}}">
		<p class="description"><?php _e('The notice shown to the sender informing them to check their email.', 'cf-validate-email'); ?></p>
	</div>
</div>
<div class="caldera-config-group">
	<label><?php echo __('Link Expiry', 'cf-validate-email'); ?> </label>
	<div class="caldera-config-field">
		<input type="number" class="field-config required" name="{{_name}}[expire]" value="{{#if expire}}{{expire}}{{else}}5{{/if}}" style="width: 45px;"> <?php _e('Minutes'); ?>
		<p class="description"><?php _e('Set in Minutes the life span of a validate link.', 'cf-validate-email'); ?></p>
	</div>
</div>
<br>
<label><?php echo __('Message', 'cf-validate-email'); ?> </label>
<textarea class="block-input field-config required magic-tag-enabled" name="{{_name}}[message]">{{#if message}}{{message}}{{else}}Hi %name%,
Please click the link below to verify your email address:

{validate_link}

- Many Thanks

{{/if}}
</textarea>
<p class="description"><?php echo __('Use {validate_link} in message to indicate link placement.', 'cf-validate-email'); ?></p>