<?php

$dsp->lists_admin->deleteItem((int)$_REQUEST['id']);

Redirect('/admin/?op=lists&pid='.$dsp->lists_admin->pid);