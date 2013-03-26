#tiDoctrineOAuthServerPlugin (for Symfony 1.4)#

The _tiDoctrineOAuthServerPlugin_ provides the basis for making an OAuth server. It provides each requested URL in the OAuth protocol 1.0a (request token, user authorization and access token). It provides the pages to authorize/refuse the access to the connected sfDoctrineGuardUser. If the user is not connected, he is automatically redirected to a login page. The authorized consumer keys (that authorizes applications to request tokens) are defined in the database (they must be added manually for the moment).

##Installation##

* Install the plugin (via a package, install version <= 0.0.4)

        symfony plugin:install tiDoctrineOAuthServerPlugin

* Install the plugin (via a git checkout)

        git clone git://github.com/Tillid/tiDoctrineOAuthServerPlugin.git plugins/tiDoctrineOAuthServerPlugin

* Activate the plugin in the config/ProjectConfiguration.class.php

        [php]
        class ProjectConfiguration extends sfProjectConfiguration
        {
          public function setup()
          {
            $this->enablePlugins(array(
              'sfDoctrinePlugin', 
              'sfDoctrineOAuthServerPlugin',
              '...'
            ));
          }
        }

* Rebuild your model, update you database tables by starting from scratch and load the fixtures (it will delete all the existing tables, then re-create them)

        symfony doctrine:build --all --and-load --no-confirmation

* Enable the tiOAuthServer module in your _settings.yml_

          all:
            .settings:
              enabled_modules:      [default, tiOAuthServer]

* Clear your cache

        symfony cc

##How to use##

The _tiDoctrineOAuthServerPlugin_ provides three routes, for each step of the OAuth connexion protocol :

* __ti_oauth_request_token__: via __/request_token__, used to retrieve the request token

* __ti_oauth_authorize__: via __/authorize__, used to allow the user to authorize (or not) the access to all the user data

* __ti_oauth_access_token__: via __/access_token__, used to retrieve the access token, that must be stored in the client application.

You should override each of these templates to have nice pages.

###Configure your client

First you have to generate an application key and secret to be used by the client. You can do that by simply rename data/fixtures/fixtures.yml to fixtures.yml.

You can use a plugin like _sfDoctrineOAuthPlugin_ to manage the client-side connexion. Here is the PHP code you have to use to connect the client:
    [php]
    $server = "http://localhost/my_project";
    // Application key and secret registered in our server
    $oauth = new sfOAuth1("12345", "67890"));
    // We can set a name to debug
    $oauth->setName("my_client_name");
    // We set all the URLs
    $oauth->setRequestTokenUrl($server.'/request-token-url'));
    $oauth->setRequestAuthUrl($server.'/authorize-url'));
    $oauth->setAccessTokenUrl($server.'/access-token-url'));
    // We set a callback URL
    $oauth->setCallback('http://localhost/my_client/access-token');
    $token = $oauth->getRequestToken();
    $token->save();
    $verifier = $oauth->requestAuth(array('oauth_callback' => $oauth->getCallback()));

You have to configure the callback action (called at the callback URL defined above):
    [php]
    $server = "http://localhost/my_project";
    $verifier = $request->getParameter('oauth_verifier');
    $key = $request->getParameter('oauth_token');
    $request_token = Doctrine_Core::getTable('Token')->createQuery('a')
            ->where('a.token_key = ?', $key)
            ->fetchOne();
    $oauth = new sfOAuth1("12345", "67890"));
    $oauth->setName("my_client_name"));
    $oauth->setToken($request_token);
    $oauth->setAccessTokenUrl($server.'/access-token-url'));
    $access_token = $oauth->getAccessToken($verifier);
    if ($access_token->getTokenKey() && $access_token->getTokenSecret()) {
      $access_token->save();
      // Here I store the token for the current user, to prevent doing the authentication each time 
      $this->getUser()->setAttribute('access_identifier', $access_token->getTokenKey());
    }
    $request_token->delete();

###Create your secured actions

Then, all you have to do is to create actions and protect them like that:

    [php]
    public function executeMyPostTicket(sfWebRequest $request)
    {
      try {
        $req = OAuthRequest::from_request();
        list($consumer, $token) = tiOAuthServer::getInstance()->verify_request($req);
        $access_token = Doctrine_Core::getTable('OAuthServerAccessTokens')->createQuery('a')
                ->where('a.id = ?', $token)
                ->fetchOne();
        if (!$access_token)
          throw new OAuthException();
        // Write your code here
        echo $access_token->getIdUser();
        return sfView::NONE;
      } catch( OAuthException $e ) {
        // The request wasn't valid!
        header('WWW-Authenticate: OAuth realm="http://localhost/"');
        echo 'I\'m afraid I can\'t let you do that Dave.';
        die;
      }
    }

##Changelog

_2013/03/26: **develop**_
 * The README now has a better documentation
 * Add .md extension to the README file 