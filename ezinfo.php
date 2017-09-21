<?php

/**
 * DB-Team Invisible reCAPTCHA extension for eZ Publish 4.x, 5.x (legacy)
 * Written by Radosłąw Zadroga, Copyright (C) DB Team.
 * @link http://db-team.pl
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

class DBTeamInvisiblereCAPTCHAInfo {
	
	
    /**
     * Who when what where why how come
     *
     * @return array
     */
    function info()
    {
        return array(
            'Name' => 'DB-Team Invisible reCAPTCHA (Google Invisible reCAPTCHA API v2)',
            'Version' => '0.2.0 dev',
            'Copyright' => 'Copyright (C) 2017-' . date("Y")
                . ' <a href="http://db-team.pl/" target="_blank">db-team.pl</a>',
            'Author' => 'Radosław Zadroga',
            'License' => 'GNU General Public License v2.0',
            'Required' => "PHP >= 5.3.10, should work with PHP 7.x"
        );
    }
    
    
}

