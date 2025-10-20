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
 * Block annotation is defined here.
 *
 * @package     block_annotation
 * @copyright   2025 Gumilar <gumilarmn@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_annotation extends block_base
{

    /**
     * Initializes class member variables.
     */
    public function init()
    {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_annotation');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content()
    {
        global $OUTPUT, $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = [];
        $this->content->icons = [];
        $this->content->footer = '';

        // Use dummy data for now
        $annotations = [
            [
                'title' => 'Web Annotation 1',
                'description' => 'This is the first dummy annotation for web view.',
                'type' => 'article',
                'image_url' => $CFG->wwwroot . '/pix/i/edit.png',
                'content' => 'This is sample content for the first annotation.',
            ],
            [
                'title' => 'Web Annotation 2',
                'description' => 'This is the second dummy annotation with different content.',
                'type' => 'tutorial',
                'image_url' => $CFG->wwwroot . '/pix/i/info.png',
                'content' => 'This is sample content for the second annotation.',
            ],
        ];

        // Render annotations using template
        $templatedata = [
            'annotations' => $annotations,
            'wwwroot' => $CFG->wwwroot,
        ];

        $this->content->text = $OUTPUT->render_from_template('block_annotation/annotation_list', $templatedata);

        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediately after init().
     */
    public function specialization()
    {
        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_annotation');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Allow multiple instances in a single course?
     *
     * @return bool True if multiple instances are allowed, false otherwise.
     */
    public function instance_allow_multiple()
    {
        return true;
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    public function has_config()
    {
        return true;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats()
    {
        return [
            'course-view' => true,
            'mod' => true,
            'my' => true,
            'site-index' => true,
        ];
    }

    /**
     * Return the plugin config settings for external functions.
     *
     * @return stdClass the configs for both the block instance and plugin
     * @since Moodle 3.8
     */
    public function get_config_for_external()
    {
        // Return the configuration for the mobile app.
        $configs = !empty($this->config) ? $this->config : new stdClass();
        return (object) [
            'instance' => $configs,
            'plugin' => new stdClass(),
        ];
    }

    /**
     * Serialize and store config data
     */
    public function instance_config_save($data, $nolongerused = false)
    {
        parent::instance_config_save($data, $nolongerused);
    }

    /**
     * Allow the block to have a configuration page
     *
     * @return boolean
     */
    public function instance_allow_config()
    {
        return true;
    }
}
