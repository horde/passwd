/**
 * Passwd Responsive Form Validation
 * Mobile-first password change form validation with real-time feedback
 *
 * Copyright 2026 Horde LLC (http://www.horde.org/)
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('passwd-form');
        if (!form) {
            return;
        }

        // Validation messages are embedded in the page by the controller
        var messages = window.passwdValidationMessages || {
            current_pass: 'Please provide your current password',
            new_pass: 'Please provide a new password',
            verify_pass: 'Please verify your new password',
            no_match: 'Your passwords do not match'
        };

        var newpass0 = document.getElementById('passwd-newpassword0');
        var newpass1 = document.getElementById('passwd-newpassword1');

        // Create feedback element for password match
        if (newpass1) {
            var feedback = document.createElement('div');
            feedback.id = 'passwd-match-feedback';
            feedback.style.marginTop = '0.5rem';
            feedback.style.fontSize = '0.875rem';
            feedback.style.display = 'none';
            newpass1.parentNode.appendChild(feedback);

            // Real-time validation as user types
            function checkPasswordMatch() {
                if (!newpass0 || !newpass1) {
                    return;
                }

                var val0 = newpass0.value;
                var val1 = newpass1.value;

                // Only show feedback if confirm field has content
                if (val1.length === 0) {
                    feedback.style.display = 'none';
                    newpass1.style.borderColor = '';
                    return;
                }

                if (val0 === val1) {
                    // Passwords match
                    feedback.textContent = '✓ Passwords match';
                    feedback.style.color = '#28a745';
                    feedback.style.display = 'block';
                    newpass1.style.borderColor = '#28a745';
                } else {
                    // Passwords don't match
                    feedback.textContent = '✗ ' + messages.no_match;
                    feedback.style.color = '#dc3545';
                    feedback.style.display = 'block';
                    newpass1.style.borderColor = '#dc3545';
                }
            }

            // Check on input events
            newpass0.addEventListener('input', checkPasswordMatch);
            newpass1.addEventListener('input', checkPasswordMatch);
        }

        // Form submit validation
        form.addEventListener('submit', function(e) {
            var oldpass = document.getElementById('passwd-oldpassword');

            // Check old password
            if (!oldpass || !oldpass.value.trim()) {
                e.preventDefault();
                alert(messages.current_pass);
                if (oldpass) {
                    oldpass.focus();
                }
                return false;
            }

            // Check new password
            if (!newpass0 || !newpass0.value.trim()) {
                e.preventDefault();
                alert(messages.new_pass);
                if (newpass0) {
                    newpass0.focus();
                }
                return false;
            }

            // Check confirm password
            if (!newpass1 || !newpass1.value.trim()) {
                e.preventDefault();
                alert(messages.verify_pass);
                if (newpass1) {
                    newpass1.focus();
                }
                return false;
            }

            // Check passwords match
            if (newpass0.value !== newpass1.value) {
                e.preventDefault();
                alert(messages.no_match);
                newpass1.focus();
                return false;
            }

            return true;
        });
    });
})();
