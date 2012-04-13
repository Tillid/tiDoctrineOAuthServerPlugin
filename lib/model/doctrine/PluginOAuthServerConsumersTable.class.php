<?php

/**
 * PluginOAuthServerConsumersTable
 * 
 * @package    tiDoctrineOAuthServerPlugin
 * @author     Emeric Kasbarian <emeric@tillid.fr>
 */

class PluginOAuthServerConsumersTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginOAuthServerConsumersTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginOAuthServerConsumers');
    }
}