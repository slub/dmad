<?php
namespace Slub\DmNorm\Common;

/***
 *
 * This file is part of the "Publisher Database" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Matthias Richter <matthias.richter@slub-dresden.de>, SLUB Dresden
 *
 ***/
/**
 * GndLib
 */

class GndLib 
{
    const DATASERVER = 'https://data.slub-dresden.de/';
    const DATAPATH = 'source/gnd_marc21/';

    public static function flattenDataSet($dataSet) {
        $output = [];
        foreach ($dataSet as $key => $dataField) {
            $buffer = [];
            if (is_array($dataField)) {
                foreach ($dataField as $subField) {
                    if (is_array($subField)) {
                        // eliminate '1_', '__', '7_' etc level
                        $subField = array_values($subField)[0];
                        $subField = self::flattenInner($subField);
                        $buffer [] = $subField;
                    }
                }
                $output[$key] = $buffer;
            }
        }
        return $output;
    }

    private static function flattenInner($subField) {
        $output = [];
        foreach ($subField as $key => $cell) {
            // if array merge, else append
            if (is_array($cell)) {
                $buffer = [];
                foreach ($cell as $subkey => $subcell) {
                    // if array merge, else append
                    if (is_array($subcell))
                        $buffer = array_merge($buffer, $subcell);
                    else
                        $buffer[$subkey] = $subcell;
                }
                $output = array_merge($output, $buffer);
            }
            else
                $output[$key] = $cell;
        }
        return $output;
    }

    public static function getSource($id) {
        $url = 'https://data.slub-dresden.de/source/gnd_marc21/' . $id;

        $headers = @get_headers($url);
        if (!$headers || $headers[0] == 'HTTP/1.0 404 Not Found' || $headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }

        return json_decode(file_get_contents($url), true);
    }
}
