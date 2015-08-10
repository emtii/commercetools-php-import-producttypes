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
 * @package     Import
 * @author      Marcel Thiesies <thiesies@bestit-online.de>
 * @copyright   2015 best it
 * @license     MIT
 * @version     0.1
 * @link        http://www.bestit-online.de/
 * @since       10.08.15 - 08:18
 * @deprecated  Not deprecated
 */

// require helper functions
require __DIR__ . '/functions.php';


/**
 * @param $files
 * @return null
 */
function _run ($files) {

    importLog('START Import');

    foreach ($files as $file) {
        $file = __DIR__ . '/data/types_nested/' . $file;
        importLog($file);

        if(file_exists($file)){
            postProductTypes($file);
        }
    }

    importLog('DONE Import');

    return null;
}

// import product types
_run(getProductTypes());