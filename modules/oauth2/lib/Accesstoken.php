<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Oauth2;

class Accesstoken extends \ORM {
    static protected function orm_setup($configs = array()) {
        $configs['table'] = "oauth2_accesstokens";
        parent::orm_setup($configs);
    }
}