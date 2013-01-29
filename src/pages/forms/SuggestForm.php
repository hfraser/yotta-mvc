<form id="suggestForm" method="post" 
	action="<?php echo $this->_request->basepath . $this->_request->lang . "/suggest/submit" ?>">
	<fieldset>
		<legend><?php echo _("Your comments and information"); ?></legend>
		<div class="elgroup">
		<label for="yourname"><?php echo _("Name") ?></label>
		<input type="text" name="yourname" class="float-left"
			placeholder="<?php echo _("Your Name"); ?>"
			value="<?php echo $this->_getData('yourname') ?>" />
			<?php if ($this->_getError('yourname')) { ?>
				<div class="error"><?php echo $this->_getError('yourname'); ?></div>
			<?php }?>
		</div>
		<div class="elgroup">
		<label for="email"><?php echo _("e-mail") ?></label>
		<input type="text" name="email" class="float-right"
			placeholder="<?php echo _("Your e-mail"); ?>" value="<?php echo $this->_getData('email') ?>" />
			<?php if ($this->_getError('email')) { ?>
				<div class="error"><?php echo $this->_getError('email'); ?></div>
			<?php }?>
		</div>
		<div class="elgroup">
		<label for="suggestion"><?php echo _("Suggestion") ?></label>
		<textarea type="text" name="suggestion" rows="6"
			placeholder="<?php echo _("What the hell do you want to suggest!"); ?>"><?php echo $this->_getData('suggestion') ?></textarea>
			<?php if ($this->_getError('suggestion')) { ?>
				<div class="error"><?php echo $this->_getError('suggestion'); ?></div>
			<?php }?>
		</div>
	</fieldset>
	<button id="submitButton" type="submit"><?php echo _("Send"); ?></button>
</form>
