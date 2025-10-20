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
 * Plugin strings are defined here.
 *
 * @package     block_annotation
 * @category    string
 * @copyright   2025 Gumilar <gumilarmn@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['annotation:addinstance'] = 'Add a new Annotation block';
$string['annotation:myaddinstance'] = 'Add a new Annotation block to Dashboard';
$string['apiurl'] = 'API URL';
$string['apiurl_help'] = 'The URL of the external API to fetch annotations from. This API should accept POST requests with course_id, module_id, and user_id parameters.';
$string['apitimeout'] = 'API timeout (seconds)';
$string['apitimeout_help'] = 'Timeout in seconds for API requests. Default is 30 seconds.';
$string['blocktitle'] = 'Block title';
$string['blocktitle_help'] = 'The title to display at the top of the block.';
$string['blockstring'] = 'Block content';
$string['cacheduration'] = 'Cache duration (seconds)';
$string['cacheduration_help'] = 'How long to cache API responses in seconds. Default is 300 seconds (5 minutes).';
$string['errorloading'] = 'Failed to fetch annotations from API.';
$string['loading'] = 'Loading annotations...';
$string['noannotations'] = 'No annotations available.';
$string['pluginname'] = 'Annotation Block';
$string['privacy:metadata'] = 'The Annotation block fetches text modules from an external API but does not store user data locally.';
$string['settings'] = 'Annotation Settings';
$string['viewannotation'] = 'View annotation content fetched from external source';
