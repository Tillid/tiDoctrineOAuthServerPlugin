<?php

/**
 * authorizeInvalidSuccess
 * 
 * @package    tiDoctrineOAuthServerPlugin
 * @author     Emeric Kasbarian <emeric@tillid.fr>
 */

?>
<?php use_helper('I18N'); ?>
<div>
  <p><?php echo __("The request token you try to validate is invalid. Please do all the steps again.", array(), 'ti_oauth_server'); ?></p>
  <p><?php echo __("If the problem persists, please contact the webmaster.", array(), 'ti_oauth_server'); ?></p>
</div>