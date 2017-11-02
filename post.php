<?php require_once 'class/DB.php'; ?>
<?php require_once 'class/Post.php'; ?>
<?php
$p = new Post();
return $p->show(2);
