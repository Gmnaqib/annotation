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
 * Mobile output class for block_annotation
 *
 * @package     block_annotation
 * @copyright   2025 Gumilar <gumilarmn@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_annotation\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');

/**
 * Mobile output class for block_annotation
 *
 * @copyright   2025 Gumilar <gumilarmn@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mobile
{

    /**
     * Returns the annotation block view for the mobile app.
     *
     * @param  array $args Arguments from tool_mobile_get_content WS
     * @return array       HTML, javascript and other data
     */
    public static function mobile_view($args)
    {
        global $OUTPUT, $COURSE, $USER, $CFG, $DB;

        // Get API URL from block configuration or use default
        $apiurl = 'https://example.com/api/annotations'; // Default API URL

        // Try to get API URL from block instance configuration
        $instances = $DB->get_records(
            'block_instances',
            ['blockname' => 'annotation'],
            'timemodified DESC',
            '*',
            0,
            1
        );

        if (!empty($instances)) {
            $instance = reset($instances);
            $configdata = !empty($instance->configdata) ? unserialize(base64_decode($instance->configdata)) : new \stdClass();
            if (!empty($configdata->api_url)) {
                $apiurl = $configdata->api_url;
            }
        }

        // Get current course ID, fallback to site course if not available
        $course_id = (!empty($COURSE) && $COURSE->id > 0) ? $COURSE->id : 1;

        // Get module ID if available
        $module_id = optional_param('id', 0, PARAM_INT);

        // Prepare data for API call
        $postdata = [
            'course_id' => $course_id,
            'module_id' => $module_id,
            'user_id'   => $USER->id
        ];

        // Initialize curl and make API call
        $curl = new \curl();
        $curl->setopt([
            'CURLOPT_TIMEOUT' => 30,
            'CURLOPT_CONNECTTIMEOUT' => 10,
            'CURLOPT_HTTPHEADER' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        $annotations = [];

        try {
            // Make API call
            $response = $curl->post($apiurl, json_encode($postdata));

            if (!$curl->get_errno()) {
                $data = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                    $annotations = self::validate_annotation_data($data);
                }
            }
        } catch (Exception $e) {
            // Log error but continue with empty annotations
            debugging('Annotation API error: ' . $e->getMessage());
        }

        // If no annotations from API, provide fallback data
        if (empty($annotations)) {
            $annotations = [
                [
                    'title' => 'Sample Annotation',
                    'description' => 'This is a sample annotation. Configure the API URL in block settings to fetch real data.',
                    'type' => 'example',
                    'image_url' => '',
                    'content' => 'API connection failed or no data available. Please check your API configuration.',
                ]
            ];
        }

        // Prepare template data
        $templatedata = [
            'annotations' => $annotations,
            'wwwroot' => $CFG->wwwroot,
            'ismainmenu' => true,
        ];

        // Render template
        $html = $OUTPUT->render_from_template('block_annotation/annotation_list', $templatedata);

        return [
            'templates' => [
                [
                    'id'   => 'main',
                    'html' => $html
                ]
            ],
            'javascript' => '',
            'otherdata'  => []
        ];
    }

    /**
     * Validate and sanitize annotation data from API
     *
     * @param array $data Raw data from API
     * @return array Validated annotation data
     */
    private static function validate_annotation_data($data)
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
}
