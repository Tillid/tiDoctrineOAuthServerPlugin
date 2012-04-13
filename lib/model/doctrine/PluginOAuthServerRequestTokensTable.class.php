<?php

/**
 * PluginOAuthServerRequestTokensTable
 * 
 * @package    tiDoctrineOAuthServerPlugin
 * @author     Emeric Kasbarian <emeric@tillid.fr>
 */

class PluginOAuthServerRequestTokensTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginOAuthServerRequestTokensTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginOAuthServerRequestTokens');
    }
}