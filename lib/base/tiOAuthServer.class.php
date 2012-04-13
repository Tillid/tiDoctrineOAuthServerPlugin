<?php

/**
 * tiDoctrineOAuthServer
 * 
 * @package    tiDoctrineOAuthServerPlugin
 * @author     Emeric Kasbarian <emeric@tillid.fr>
 */

abstract class tiOAuthServer
{
  public static function getInstance()
  {
    static $oauth_server = null;
    if (!$oauth_server)
    {
      $oauth_server = new OAuthServer(new tiOAuthDataStore());
      $hmac_method = new OAuthSignatureMethod_HMAC_SHA1();
      $oauth_server->add_signature_method($hmac_method);
      $plaintext_method = new OAuthSignatureMethod_PLAINTEXT();
      $oauth_server->add_signature_method($plaintext_method);
    }
    return $oauth_server;
  }
}