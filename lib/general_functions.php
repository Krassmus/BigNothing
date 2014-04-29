<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

function escapeHtml($text) {
    return htmlentities($text, ENT_COMPAT, "UTF-8");
}

$GLOBALS['URI'] = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$GLOBALS['URI'] .= "://".$_SERVER['SERVER_NAME'];
if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' && $_SERVER['SERVER_PORT'] != 443) ||
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'on' && $_SERVER['SERVER_PORT'] != 80)) {
    $GLOBALS['URI'] .= ':'.$_SERVER['SERVER_PORT'];
}
$GLOBALS['URI'] .= dirname($_SERVER['PHP_SELF'])."/";