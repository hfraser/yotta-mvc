<!DOCTYPE html>
<html lang="<?php echo($this->_request->lang);?>">
<head>
<meta charset="utf-8" />
<title><?php _($this->_request->currentAction . ':title'); ?></title>
<?php $this->helper->getAllCSS(); ?>
</head>
<body class="<?php echo($this->_request->currentAction . ' ' . $this->_request->lang); ?>">