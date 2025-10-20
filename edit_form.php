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
 * Form for editing annotation block instances.
 *
 * @package     block_annotation
 * @copyright   2025 Gumilar <gumilarmn@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Form for editing annotation block instances.
 *
 * @copyright   2025 Gumilar <gumilarmn@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_annotation_edit_form extends block_edit_form
{

    /**
     * Extends the configuration form for annotation block.
     *
     * @param MoodleQuickForm $mform the form being built.
     */
    protected function specific_definition($mform)
    {

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Block title configuration
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_annotation'));
        $mform->setDefault('config_title', 'Annotation Block');
        $mform->setType('config_title', PARAM_TEXT);
        $mform->addHelpButton('config_title', 'blocktitle', 'block_annotation');

        // API URL configuration
        $mform->addElement('text', 'config_api_url', get_string('apiurl', 'block_annotation'));
        $mform->setDefault('config_api_url', 'https://example.com/api/annotations');
        $mform->setType('config_api_url', PARAM_URL);
        $mform->addHelpButton('config_api_url', 'apiurl', 'block_annotation');

        // API timeout configuration
        $mform->addElement('text', 'config_api_timeout', get_string('apitimeout', 'block_annotation'));
        $mform->setDefault('config_api_timeout', '30');
        $mform->setType('config_api_timeout', PARAM_INT);
        $mform->addHelpButton('config_api_timeout', 'apitimeout', 'block_annotation');

        // Cache duration configuration
        $mform->addElement('text', 'config_cache_duration', get_string('cacheduration', 'block_annotation'));
        $mform->setDefault('config_cache_duration', '300');
        $mform->setType('config_cache_duration', PARAM_INT);
        $mform->addHelpButton('config_cache_duration', 'cacheduration', 'block_annotation');
    }
}
