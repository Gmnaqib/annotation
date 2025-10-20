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
        global $OUTPUT, $COURSE, $USER, $PAGE;

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

        // Get annotations from API
        $annotations = $this->get_annotations_from_api();

        // Render annotations using template
        $templatedata = [
            'annotations' => $annotations,
        ];

        $this->content->text = $OUTPUT->render_from_template('block_annotation/annotation_list', $templatedata);

        return $this->content;
    }

    /**
     * Fetch annotations from external API
     *
     * @return array Array of annotations
     */
    private function get_annotations_from_api()
    {
        global $COURSE, $USER, $PAGE;

        // Get configuration values
        $api_url = !empty($this->config->api_url) ? $this->config->api_url : 'https://example.com/api/annotations';
        $api_timeout = !empty($this->config->api_timeout) ? (int)$this->config->api_timeout : 30;
        $cache_duration = !empty($this->config->cache_duration) ? (int)$this->config->cache_duration : 300;

        // Prepare data to send to API
        $course_id = $COURSE->id;
        $user_id = $USER->id;

        // Get module ID if available
        $module_id = 0;
        if (isset($PAGE->cm) && $PAGE->cm) {
            $module_id = $PAGE->cm->id;
        }

        $postdata = [
            'course_id' => $course_id,
            'module_id' => $module_id,
            'user_id' => $user_id,
        ];

        // Create cache key
        $cache_key = 'block_annotation_' . md5($api_url . serialize($postdata));

        // Try to get from cache first
        $cache = cache::make('block_annotation', 'apidata');
        $cached_data = $cache->get($cache_key);

        if ($cached_data !== false) {
            return $cached_data;
        }

        // Initialize curl
        $curl = new curl();
        $curl->setopt([
            'CURLOPT_TIMEOUT' => $api_timeout,
            'CURLOPT_CONNECTTIMEOUT' => 10,
            'CURLOPT_HTTPHEADER' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        try {
            // Make API call
            $response = $curl->post($api_url, json_encode($postdata));

            if ($curl->get_errno()) {
                // Log error and return empty array
                debugging('Annotation API curl error: ' . $curl->error);
                return $this->get_fallback_data();
            }

            // Decode JSON response
            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                debugging('Annotation API JSON decode error: ' . json_last_error_msg());
                return $this->get_fallback_data();
            }

            // Validate and sanitize data
            $validated_data = $this->validate_annotation_data($data);

            // Cache the result
            $cache->set($cache_key, $validated_data);

            return $validated_data;
        } catch (Exception $e) {
            debugging('Annotation API exception: ' . $e->getMessage());
            return $this->get_fallback_data();
        }
    }

    /**
     * Get fallback data when API fails
     *
     * @return array Fallback annotation data
     */
    private function get_fallback_data()
    {
        return [
            [
                'title' => 'Sample Annotation',
                'description' => 'This is a sample annotation. Configure the API URL in block settings to fetch real data.',
                'type' => 'example',
                'image_url' => '',
                'content' => 'API connection failed. Please check your API URL configuration.',
            ]
        ];
    }

    /**
     * Validate and sanitize annotation data from API
     *
     * @param array $data Raw data from API
     * @return array Validated annotation data
     */
    private function validate_annotation_data($data)
    {
        if (!is_array($data)) {
            return [];
        }

        $validated = [];
        foreach ($data as $item) {
            if (!is_array($item)) {
                continue;
            }

            $annotation = [
                'title' => isset($item['title']) ? clean_text($item['title']) : '',
                'description' => isset($item['description']) ? clean_text($item['description']) : '',
                'type' => isset($item['type']) ? clean_text($item['type']) : '',
                'image_url' => isset($item['image_url']) ? clean_param($item['image_url'], PARAM_URL) : '',
                'content' => isset($item['content']) ? clean_text($item['content']) : '',
            ];

            // Only add if title exists
            if (!empty($annotation['title'])) {
                $validated[] = $annotation;
            }
        }

        return $validated;
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

    /**
     * Mobile view for annotation block
     *
     * @param array $args Arguments from mobile app
     * @return array HTML and other data for mobile
     */
    public static function mobile_view($args)
    {
        global $OUTPUT, $COURSE, $USER, $PAGE, $DB;

        $args = (object) $args;

        // Get block instance
        if (isset($args->instanceid)) {
            $instance = $DB->get_record('block_instances', ['id' => $args->instanceid]);
            if ($instance) {
                $configdata = !empty($instance->configdata) ? unserialize(base64_decode($instance->configdata)) : new \stdClass();
            }
        }

        // Create temporary block instance for API call
        $tempblock = new block_annotation();
        if (isset($configdata)) {
            $tempblock->config = $configdata;
        }

        // Get annotations from API
        $annotations = $tempblock->get_annotations_from_api();

        $templatedata = [
            'annotations' => $annotations,
        ];

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('block_annotation/annotation_list', $templatedata),
                ]
            ],
            'javascript' => '',
            'otherdata' => '',
        ];
    }
}
