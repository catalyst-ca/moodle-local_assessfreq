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

/**
 * Upgrade file.
 *
 * @package   local_assessfreq
 * @author    Simon Thornett <simon.thornett@catalyst-eu.net>
 * @copyright Catalyst IT, 2024
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Function to upgrade local_assessfreq.
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_local_assessfreq_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2024021413) {

        $table = new xmldb_table('local_assessfreq_trend');
        $field = new xmldb_field('module', XMLDB_TYPE_CHAR, '20', true, true);
        $index = new xmldb_index('module', XMLDB_INDEX_NOTUNIQUE, ['module']);

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        /*
         * Previously we only used this table for quiz, so all existing modules will be quiz modules.
         */
        $DB->execute("UPDATE {local_assessfreq_trend} SET module = 'quiz'");

        upgrade_plugin_savepoint(true, 2024021413, 'local', 'assessfreq');
    }

    return true;
}