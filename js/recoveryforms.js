/* 
 * Copyright (C) 2013 peredur.net
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function regformhash(form, email) {
    // Check each field has a value
    if (email.value == '') {
        alert('You must provide a valid email address. Please try again.');
        return false;
    }

    // submit the form. 
    form.submit();
    return true;
}

function formsub(form, email, password) {
    if (email.value == '') {
        alert('You must provide a valid email address. Please try again.');
        return false;
    }
    else if (password.value < 8) {
        alert('Sorry. The code is invalid.');
        return false;
    }
    else if (password.value == '') {
        alert('Please enter the recovery code.');
        return false;
    }
    else {
        form.submit();
        return true;
    }
}