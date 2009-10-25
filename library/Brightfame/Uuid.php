<?php

/**
 * Brightfame Uuid
 *
 * @category    Brightfame
 * @package     Brightfame_Uuid
 * @license     New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version     $Id: $
 */

class Brightfame_Uuid
{
    /**
     * Creates a UUID.
     *  
     * @return string
     */
    public static function createUUID()
    {
	    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
	        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
	        mt_rand( 0, 0x0fff ) | 0x4000,
	        mt_rand( 0, 0x3fff ) | 0x8000,
	        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
    }
}