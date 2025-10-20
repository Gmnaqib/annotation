<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Mobile addons are declared here.
 *
 * @package     block_annotation
 * @copyright   2025 Gumilar <gumilarmn@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$addons = [
    'block_annotation' => [
        'handlers' => [
            'annotationhandler' => [
                'delegate' => 'CoreBlockDelegate',
                'method' => 'mobile_view',
                'displaydata' => [
                    'title' => 'pluginname',
                    'icon' => 'fas fa-sticky-note',
                    'class' => 'block_annotation',
                    'priority' => 500,
                ],
                'offlinefunctions' => [
                    'mobile_view' => [],
                ],
            ],
            'annotationmainmenu' => [
                'delegate' => 'CoreMainMenuDelegate',
                'method' => 'mobile_main_menu_view',
                'displaydata' => [
                    'title' => 'pluginname',
                    'icon' => 'fas fa-sticky-note',
                    'class' => 'block_annotation',
                    'priority' => 500,
                ],
                'offlinefunctions' => [
                    'mobile_main_menu_view' => [],
                ],
            ],
        ],
        'lang' => [
            ['pluginname', 'block_annotation'],
            ['noannotations', 'block_annotation'],
            ['loading', 'block_annotation'],
        ],
    ],
];
