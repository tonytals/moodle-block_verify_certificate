<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

require_once("../../config.php");
require_once($CFG->dirroot . '/mod/certificate/lib.php');
global $DB;

$id = required_param('certnumber', PARAM_ALPHANUM);   // Certificate code to verify.

$PAGE->set_pagelayout('standard');
$strverify = get_string('verifycertificate', 'block_verify_certificate');
$PAGE->set_url('/blocks/verify_certificate/index.php', array('certnumber' => $id));
$context = context_system::instance();
$PAGE->set_context($context);

// Print the header.

$PAGE->navbar->add($strverify);
$PAGE->set_title($strverify);
$PAGE->set_heading($strverify);
$PAGE->requires->css('/blocks/verify_certificate/printstyle.css');
echo $OUTPUT->header();
$ufields = user_picture::fields('u');
$sql = "SELECT ci.timecreated AS citimecreated,
     ci.code, ci.certificateid, ci.userid, $ufields, c.*
     FROM {certificate_issues} ci
                           INNER JOIN {user} u
                           ON u.id = ci.userid
                           INNER JOIN {certificate} c
                           ON c.id = ci.certificateid
                           WHERE ci.code = ?";
$certificates = $DB->get_records_sql($sql, array($id));

if (! $certificates) {
    echo $OUTPUT->box_start('generalbox boxaligncenter');
    echo get_string('notfound', 'block_verify_certificate');
    echo $OUTPUT->box_end();
} else {
    echo $OUTPUT->box_start('generalbox boxaligncenter');
    echo "<a title=\""; print_string('printerfriendly', 'certificate');
    echo "\" href=\"#\" onclick=\"window.print ()\"><div class=\"printicon\">";
    echo "<img src=\"print.gif\" height=\"16\" width=\"16\" border=\"0\"></img></a></div>";
    // Print Section.
    foreach ($certificates as $certdata) {
        echo '<p>' . get_string('certificate', 'block_verify_certificate') . ' ' . $certdata->code . '</p>';
        echo '<p><b>' . get_string('to', 'block_verify_certificate') . ': </b>' . fullname($certdata) . '<br />';
        $course = $DB->get_record('course', array('id' => $certdata->course));
        if ($course) {
            echo '<p><b>' . get_string('course', 'block_verify_certificate') . ': </b>' . $course->fullname . '<br />';
        }
        // Modify printdate so that date is always printed.
        $certdata->printdate = 1;
        $certrecord = new stdClass();
        $certrecord->timecreated = $certdata->citimecreated;
        $certrecord->code = $certdata->code;
        $certrecord->userid = $certdata->userid;

        $date = certificate_get_date($certdata, $certrecord, $course, $certdata->userid);
        if ($date) {
            echo '<p><b>' . get_string('date', 'block_verify_certificate') . ': </b>' . $date . '<br /></p>';
        }
        if ($course && $certdata->printgrade > 0) {
            echo '<p><b>' . get_string('grade', 'block_verify_certificate') . ': </b>' . certificate_get_grade($certdata, $course, $certdata->userid) . '<br /></p>';
        }
    }
    echo $OUTPUT->box_end();
}
echo $OUTPUT->footer();