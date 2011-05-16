<?php
/**
 * The horde driver attempts to change a user's password without
 * caring about the actual implementation. It relies on the current horde
 * authentication mechanism's ability to update the user
 *
 * $Horde: passwd/lib/Driver/horde.php
 *
 * Copyright 2011 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.php.
 *
 * @author  Ralf Lang <lang@b1-systems.de>
 * @package Passwd
 */
class Passwd_Driver_horde extends Passwd_Driver {

    /**
     * Change the user's password.
     *
     * @param string $username      The user for which to change the password.
     * @param string $old_password  The old (current) user password.
     * @param string $new_password  The new user password to set.
     *
     * @return boolean  True or false based on success of the change.
     */
    public function changePassword($username,  $old_password, $new_password)
    {
    
        $registry = $GLOBALS['injector']->getInstance('Horde_Registry');
        $auth = $GLOBALS['injector']->getInstance('Horde_Core_Factory_Auth')->create();

        if (!$auth->hasCapability('update')) {
            return PEAR::raiseERROR(_('The current horde configuration does not allow changing passwords'));
        }

        /* Check the provided old password. */

        if ($auth->authenticate($username, array('password' => $old_password, false))) {
            /* actually modify the password */
            return $auth->updateUser($username, $username, array('password' => $new_password) );
        } else {
            return PEAR::raiseERROR(_('The provided old password is not right'));        
        }

    }

}
