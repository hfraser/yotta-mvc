<!DOCTYPE html>
<html lang="<?php echo($this->getData('lang'));?>">
<head>
<meta charset="utf-8" />
<title><?php _($this->getData('action') . ':title'); ?></title>
<?php $this->getHelper('html')->getAllCSS(); ?>
</head>
<body class="<?php echo($this->getData('action') . ' ' . $this->getData('lang')); ?>">
<div id="header"><h1><?php echo _("C-Mantix Ultralight Demo Page"); ?></h1></div>