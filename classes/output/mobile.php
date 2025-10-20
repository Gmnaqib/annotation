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
        global $OUTPUT, $USER, $CFG;

        // Use dummy data for now
        $annotations = [
            [
                'title' => 'Annotation Dummy 1',
                'description' => 'This is the first dummy annotation for testing mobile view.',
                'type' => 'article',
                'image_url' => $CFG->wwwroot . '/pix/i/edit.png',
                'content' => 'This is sample content for the first annotation.',
            ],
            [
                'title' => 'Annotation Dummy 2',
                'description' => 'This is the second dummy annotation with different content.',
                'type' => 'tutorial',
                'image_url' => $CFG->wwwroot . '/pix/i/info.png',
                'content' => 'This is sample content for the second annotation.',
            ],
            [
                'title' => 'Annotation Dummy 3',
                'description' => 'This is the third dummy annotation to show multiple items.',
                'type' => 'example',
                'image_url' => '',
                'content' => 'This annotation has no image but has content.',
            ],
        ];

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
}
