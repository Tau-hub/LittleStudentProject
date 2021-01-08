<?php

if ($idm->getRole() != "admin") {
    redirect($pathFor['root']);
    exit();
}
;
