<?php

/**
 * PluginOAuthServerNoncesTable
 * 
 * @package    tiDoctrineOAuthServerPlugin
 * @author     Emeric Kasbarian <emeric@tillid.fr>
 */

class PluginOAuthServerNoncesTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginOAuthServerNoncesTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginOAuthServerNonces');
    }
}