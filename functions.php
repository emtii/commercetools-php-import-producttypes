<?php
/**
 * Import Producttypes into your project.
 *
 * This little helper imports given producttypes as json files into
 * your commercetools project.
 *
 * PHP Version 5
 *
 * @category    commercetools-php-import-producttypes
 * @package     Helper
 * @author      Marcel Thiesies <thiesies@bestit-online.de>
 * @copyright   2015 best it
 * @license     MIT
 * @version     0.1
 * @link        http://www.bestit-online.de/
 * @since       10.08.15 - 08:25
 * @deprecated  Not deprecated
 */


/**
 * @param $msg
 * @return null
 */
function importLog($msg) {
    error_log("productType Import => " . $msg);

    return null;
}

/**
 * @return array
 * @info The handling of folders is not very stable this way, nested types have to get imported first,
 *       after that, we can import main types with proper given IDs of nested types.
 * @todo We need to handle given response of every imported product type and save name => ID of
 *       nested types somehow.
 */
function getProductTypes() {

    // lookup in /data/
    $dirToScan = __DIR__ . '/data/';

    // define folders to scan inside of /data/
    $foldersToScan = array(
        $dirToScan . 'types_main',
        $dirToScan . 'types_nested'
    );

    $scanned = array();

    foreach ($foldersToScan as $folder) {
        if(file_exists($folder)) {
            importLog('Get json files of folder: ' . $folder);

            // scan dir, remove unix . & .. folder
            $scanned = array_merge(array_diff(scandir($folder), array('..', '.')), $scanned);
        }
        else {
            importLog('Folder not found');
        }
    }

    return $scanned;
}

/**
 * @param $data
 * @return array
 * @todo create auth token automatically or change completely to client_id / client_secreted auth
 *       and put this completely into an external config file.
 */
function postProductTypes ($data) {
    $api = 'https://api.sphere.io/';
    $project = '';
    $endpoint = '/product-types';
    $bearer = '';

    $ch = curl_init($api . $project . $endpoint);
    importLog('Initiated cURL Session');

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
            'Authorization: Bearer ' . $bearer
        )
    );
    importLog('Set Options for cURL Session');

    $result = curl_exec($ch);
    importLog('Executed cURL Session, saved Result');

    curl_close($ch);
    importLog('Closed cURL Session');

    // returns result, but is not handled so far
    // @todo Should handle the result in another helper method somehow, e.g. for saving created IDs.
    return $result;
}