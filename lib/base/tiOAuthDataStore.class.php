<?php

/**
 * tiDoctrineOAuthServer
 * 
 * @package    tiDoctrineOAuthServerPlugin
 * @author     Emeric Kasbarian <emeric@tillid.fr>
 */

class tiOAuthDataStore extends OAuthDataStore
{
  function lookup_consumer($consumer_key)
  {
    return Doctrine_Core::getTable('OAuthServerConsumers')->createQuery('a')
            ->where('a.key = ?', $consumer_key)
            ->fetchOne();
  }

  function lookup_token($consumer, $token_type, $token)
  {
    $oToken = null;
    if ($token_type == 'request')
    {
      $oToken = Doctrine_Core::getTable('OAuthServerRequestTokens')->createQuery('a')
              ->where('a.key = ?', $token)
              ->fetchOne();
    }
    else if ($token_type == 'access')
    {
      $oToken = Doctrine_Core::getTable('OAuthServerAccessTokens')->createQuery('a')
              ->where('a.key = ?', $token)
              ->fetchOne();
    }
    if ($oToken && $oToken->getIdConsumer() == $consumer->getId())
      return $oToken;
    return null;
  }

  function lookup_nonce($consumer, $token, $nonce, $timestamp)
  {
    if (!$token)
      return false;
    $existing = Doctrine_Core::getTable('OAuthServerNonces')->createQuery('a')
            ->where('a.id_consumer = ?', $consumer->getId())
            ->andWhere('a.token = ?', $token->getKey())
            ->andWhere('a.nonce = ?', $nonce)
            ->andWhere('a.timestamp = ?', (int)$timestamp)
            ->fetchOne();
    if (!$existing)
    {
      $oNonce = new OAuthServerNonces();
      $oNonce->setIdConsumer($consumer->getId());
      $oNonce->setToken($token->getKey());
      $oNonce->setNonce($nonce);
      $oNonce->setTimestamp((int)$timestamp);
      $oNonce->save();
      return false;
    }
    return true;
  }

  function new_request_token($consumer, $callback = null)
  {
    $key    = substr(md5(rand(1, 1000000) * rand(1,1000000)), 0, 8);
    $secret = substr(md5(rand(1, 1000000) * rand(1,1000000)), 0, 8);
    $token = new OAuthServerRequestTokens();
    $token->setIdConsumer($consumer->getId());
    $token->setKey($key);
    $token->setSecret($secret);
    $token->setAuthorized(0);
    $token->save();
    return $token;
  }

  function new_access_token($request_token, $consumer, $verifier = null)
  {
    if ($request_token->authorized)
    {
      $key    = substr(md5(rand(1, 1000000) * rand(1, 1000000)), 0, 8);
      $secret = substr(md5(rand(1, 1000000) * rand(1, 1000000)), 0, 8);
      $access_token = new OAuthServerAccessTokens();
      $access_token->setIdConsumer($consumer->getId());
      $access_token->setKey($key);
      $access_token->setSecret($secret);
      $access_token->setIdUser($request_token->getIdUser());
      $access_token->save();
      $request_token->delete();
      return $access_token;
    }
    else
      throw new OAuthException('Unauthorized Access Token!');
  }
}