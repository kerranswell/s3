<?php

$dsp->usersadmin_admin->deleteItem((int)$_REQUEST['id']);

Redirect('/admin/?op=usersadmin');