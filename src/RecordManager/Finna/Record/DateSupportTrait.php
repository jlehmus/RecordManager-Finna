<?php
/**
 * Date handling support trait.
 *
 * PHP version 7
 *
 * Copyright (C) The National Library of Finland 2022.
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
 * @link     https://github.com/NatLibFi/RecordManager
 */
namespace RecordManager\Finna\Record;

/**
 * Date handling support trait.
 *
 * @category DataManagement
 * @package  RecordManager
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/NatLibFi/RecordManager
 */
trait DateSupportTrait
{
    /**
     * Convert a date range to a Solr date range string,
     * e.g. [1970-01-01 TO 1981-01-01]
     *
     * @param array|null $range Start and end date
     *
     * @return string Start and end date in Solr format
     * @throws \Exception
     */
    public function dateRangeToStr($range)
    {
        if (!$range) {
            return '';
        }
        $oldTZ = date_default_timezone_get();
        try {
            date_default_timezone_set('UTC');
            $start = date('Y-m-d', strtotime($range[0]));
            $end = date('Y-m-d', strtotime($range[1]));
        } catch (\Exception $e) {
            date_default_timezone_set($oldTZ);
            throw $e;
        }
        date_default_timezone_set($oldTZ);

        return $start === $end ? $start : "[$start TO $end]";
    }

    /**
     * Get years from a string, matches ISO 8601 format.
     *
     * @param string $dateString String to check for years.
     *
     * @return array [startYear, endYear] or empty array if not found.
     */
    protected function getYearsFromString(string $dateString): array
    {
        if (preg_match_all(
            '/(-?\b\d{4})\b-?\d{0,2}\b-?\b\d{0,2}\b/',
            $dateString,
            $matches
        )
        ) {
            $result = [
                'startYear' => $matches[1][0],
                'endYear' => $matches[1][1] ?? $matches[1][0]
            ];
            // Turn the years into numbers and compare them.
            if ((int)$result['startYear'] > (int)$result['endYear']) {
                $result['endYear'] = $result['startYear'];
            }
            return $result;
        }
        return [];
    }
}
