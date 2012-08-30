<form id="suggestForm" method="post" action="<?php echo URL_ROOT . $this->_request->lang . "/suggest/submit" ?>">
	<input type="text" name="yourname" class="float-left"
		placeholder="<?php echo _("Your Name"); ?>"
		value="<?php echo $this->_getData('yourname') ?>" />
		<?php if ($this->_getError('yourname')) { ?>
			<div class="error"><?php echo $this->_getError('yourname'); ?></div>
		<?php }?>
	<input type="text" name="email" class="float-right"
		placeholder="<?php echo _("Your e-mail"); ?>" value="<?php echo $this->_getData('email') ?>" />
		<?php if ($this->_getError('email')) { ?>
			<div class="error"><?php echo $this->_getError('email'); ?></div>
		<?php }?>
	<textarea type="text" name="suggestion" rows="6"
		placeholder="<?php echo _("What the hell do you want to suggest!"); ?>"><?php echo $this->_getData('suggestion') ?></textarea>
		<?php if ($this->_getError('suggestion')) { ?>
			<div class="error"><?php echo $this->_getError('suggestion'); ?></div>
		<?php }?>
	<button id="submitButton" type="submit" title="<?php echo _("Send"); ?>" />
</form>
