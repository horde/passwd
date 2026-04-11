/**
 * Provides javascript support for the main passwd page.
 *
 * See the enclosed file LICENSE for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 */

document.addEventListener('DOMContentLoaded', function() {
    var submit = document.getElementById('passwd-submit');
    if (submit) {
        submit.addEventListener('click', function(e) {
            var oldPw = document.getElementById('passwd-oldpassword'),
                newPw0 = document.getElementById('passwd-newpassword0'),
                newPw1 = document.getElementById('passwd-newpassword1');

            if (!oldPw.value) {
                alert(Passwd.current_pass);
                oldPw.focus();
                e.preventDefault();
                return;
            }
            if (!newPw0.value) {
                alert(Passwd.new_pass);
                newPw0.focus();
                e.preventDefault();
                return;
            }
            if (!newPw1.value) {
                alert(Passwd.verify_pass);
                newPw1.focus();
                e.preventDefault();
                return;
            }
            if (newPw0.value != newPw1.value) {
                alert(Passwd.no_match);
                newPw0.focus();
                e.preventDefault();
                return;
            }
        });
    }

    var form = document.getElementById('passwd');
    if (form) {
        var first = form.querySelector('input:not([type="hidden"]), select, textarea');
        if (first) {
            first.focus();
        }
    }
});
