<?php

/**
 * tiOAuthServerActions
 * 
 * @package    tiDoctrineOAuthServerPlugin
 * @author     Emeric Kasbarian <emeric@tillid.fr>
 */

class tiOAuthServerActions extends sfActions
{
  public function executeRequestToken(sfWebRequest $request)
  {
    try
    {
      $request = OAuthRequest::from_request();
      $token = tiOAuthServer::getInstance()->fetch_request_token($request);
      printf('oauth_token=%s&oauth_token_secret=%s',
              OAuthUtil::urlencode_rfc3986($token->key),
              OAuthUtil::urlencode_rfc3986($token->secret));
    }
    catch(OAuthException $e)
    {
        header("HTTP/401 Unauthorized");
        echo $e->getMessage();
    }
    die;
  }

  public function executeAuthorize(sfWebRequest $request)
  {
    $req = OAuthRequest::from_request();
    $this->token = $req->get_parameter('oauth_token');
    $this->callback = $req->get_parameter('oauth_callback');
    $token = Doctrine_Core::getTable('OAuthServerRequestTokens')->createQuery('a')
            ->where('a.key = ?', $req->get_parameter('oauth_token'))
            ->fetchOne();
    if (!$token) {
      $this->setTemplate('authorizeInvalid');
      return ;
    }
    $this->accept = $request->getParameter('accept', null);
    $this->refuse = $request->getParameter('refuse', null);
    if (!$this->accept && !$this->refuse) {
      $this->setTemplate('authorizeForm');
      return ;
    }
    if ($this->refuse) {
      $token->delete();
      $this->setTemplate('authorizeRefused');
      return ;
    }
    $token->setAuthorized(1);
    $token->setIdUser($this->getUser()->getGuardUser()->getId());
    $token->save();
    $verifier = substr(md5(rand()), 0, 10);
    if ($this->callback)
    {
      $this->redirect($this->callback."?oauth_token=".$this->token."&oauth_verifier=".$verifier);
    }
  }

  public function executeAccessToken(sfWebRequest $request)
  {
    try {
      $req = OAuthRequest::from_request();
      $token = tiOAuthServer::getInstance()->fetch_access_token($req);
      printf('oauth_token=%s&oauth_token_secret=%s',
              OAuthUtil::urlencode_rfc3986($token->key),
              OAuthUtil::urlencode_rfc3986($token->secret));
    } catch(OAuthException $e) {
      header("HTTP/401 Unauthorized");
      echo $e->getMessage();
    }
    die;
  }
}
