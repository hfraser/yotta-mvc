<?php include("includes/header.inc.php"); ?>

<h1><?php _($this->_request->currentAction . ':title'); ?></h1>
<div id="form-holder">
<?php services\SuggestionBox::getIndex(); ?>
</div>
<?php include("includes/footer.inc.php"); ?>
