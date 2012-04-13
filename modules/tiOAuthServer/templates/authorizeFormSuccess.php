<?php

/**
 * authorizeFormSuccess
 * 
 * @package    tiDoctrineOAuthServerPlugin
 * @author     Emeric Kasbarian <emeric@tillid.fr>
 */

?>
<?php use_helper('I18N'); ?>
<div>
    <p><?php echo __('By clicking on the "I Accept" button, I authorize this application to access all my data, without storing my password.', array(), 'ti_oauth_server'); ?></p>
    <p><?php echo __('A click on the "I Refuse" button will destroy the request token, and refuse the authorization.', array(), 'ti_oauth_server'); ?></p>
  <form method="POST" action="<?php echo url_for('ti_oauth_authorize', array('oauth_token' => $token, 'oauth_callback' => $callback, "accept" => 1)); ?>">
    <input type="submit" value="<?php echo __('I Accept', array(), 'ti_oauth_server'); ?>" />
  </form>
  <form method="POST" action="<?php echo url_for('ti_oauth_authorize', array('oauth_token' => $token, 'oauth_callback' => $callback, 'refuse' => 1)); ?>">
    <input type="submit" value="<?php echo __('I Refuse', array(), 'ti_oauth_server'); ?>" />
  </form>
</div>