<?php

$dsp->news_admin->deleteItem((int)$_REQUEST['id']);

Redirect('/admin/?op=news&pid='.$dsp->news_admin->pid);