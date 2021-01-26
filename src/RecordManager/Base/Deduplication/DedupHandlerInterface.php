<?php
/**
 * Interface for Deduplication Handlers
 *
 * PHP version 7
 *
 * Copyright (C) The National Library of Finland 2020.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category DataManagement
 * @package  RecordManager
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/KDK-Alli/RecordManager
 */
namespace RecordManager\Base\Deduplication;

use RecordManager\Base\Database\DatabaseInterface as Database;
use RecordManager\Base\Record\Factory as RecordFactory;
use RecordManager\Base\Utils\Logger;

/**
 * Interface for Deduplication Handlers
 *
 * This class provides the rules and functions for deduplication of records.
 *
 * @category DataManagement
 * @package  RecordManager
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/KDK-Alli/RecordManager
 */
interface DedupHandlerInterface
{
    /**
     * Constructor
     *
     * @param Database      $db            Database
     * @param Logger        $log           Logger object
     * @param boolean       $verbose       Whether verbose output is enabled
     * @param string        $basePath      Base path
     * @param array         $mainConfig    Main configuration
     * @param array         $settings      Data source settings
     * @param RecordFactory $recordFactory Record factory
     */
    public function __construct(Database $db, Logger $log, $verbose, $basePath,
        $mainConfig, $settings, $recordFactory
    );

    /**
     * Verify dedup record consistency
     *
     * @param array $dedupRecord Dedup record
     *
     * @return array An array with a line per fixed record
     */
    public function checkDedupRecord($dedupRecord);

    /**
     * Verify record links
     *
     * @param array $record Record
     *
     * @return string Fix message or empty string for no problems
     */
    public function checkRecordLinks($record);

    /**
     * Update dedup candidate keys for the given record
     *
     * @param array  $record         Database record
     * @param object $metadataRecord Metadata record for the used format
     *
     * @return boolean Whether anything was changed
     */
    public function updateDedupCandidateKeys(&$record, $metadataRecord);

    /**
     * Find a single duplicate for the given record and set a common dedup key to
     * both records
     *
     * @param array $record Database record
     *
     * @return boolean Whether a duplicate was found
     */
    public function dedupRecord($record);

    /**
     * Remove a record from a dedup record
     *
     * @param string|object $dedupId ObjectID of the dedup record
     * @param string        $id      Record ID to remove
     *
     * @return void
     */
    public function removeFromDedupRecord($dedupId, $id);
}