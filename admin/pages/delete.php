<?php

$dsp->pages_admin->deleteItem((int)$_REQUEST['id']);

Redirect('/admin/?op=pages&pid='.$dsp->pages_admin->pid);