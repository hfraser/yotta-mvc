<form id="suggestForm" method="post" enctype="multipart/form-data"
	action="<?php echo $this->getData('basepath') . $this->getData('lang') . "/suggest/submit" ?>">
	<fieldset>
		<legend><?php echo _("Your comments and information"); ?></legend>
		<div class="elgroup">
		<label for="yourname"><?php echo _("Name") ?></label>
		<input type="text" name="yourname" class="float-left"
			placeholder="<?php echo _("Your Name"); ?>"
			value="<?php echo $this->getData('yourname') ?>" />
			<?php if ($this->getHelper('form')->getError('yourname')) { ?>
				<div class="error"><?php echo $this->getHelper('form')->getError('yourname'); ?></div>
			<?php }?>
		</div>
		<div class="elgroup">
		<label for="email"><?php echo _("e-mail") ?></label>
		<input type="text" name="email" class="float-right"
			placeholder="<?php echo _("Your e-mail"); ?>" value="<?php echo $this->getData('email') ?>" />
			<?php if ($this->getHelper('form')->getError('email')) { ?>
				<div class="error"><?php echo $this->getHelper('form')->getError('email'); ?></div>
			<?php }?>
		</div>
		<div class="elgroup">
		<label for="suggestion"><?php echo _("Suggestion") ?></label>
		<textarea type="text" name="suggestion" rows="6"
			placeholder="<?php echo _("What the hell do you want to suggest!"); ?>"><?php echo $this->getData('suggestion') ?></textarea>
			<?php if ($this->getHelper('form')->getError('suggestion')) { ?>
				<div class="error"><?php echo $this->getHelper('form')->getError('suggestion'); ?></div>
			<?php }?>
		</div>
	</fieldset>
	<button id="submitButton" type="submit"><?php echo _("Send"); ?></button>
</form>