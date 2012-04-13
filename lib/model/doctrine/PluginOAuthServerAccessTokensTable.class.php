<?php

/**
 * PluginOAuthServerAccessTokensTable
 * 
 * @package    tiDoctrineOAuthServerPlugin
 * @author     Emeric Kasbarian <emeric@tillid.fr>
 */

class PluginOAuthServerAccessTokensTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginOAuthServerAccessTokensTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginOAuthServerAccessTokens');
    }
}