<?php

require_once(__DIR__ . '/client/GitHubClient.php');

class GithubModule extends HC_Module {

  private $loggedIn;

  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('github', 'GithubWindowCallback');
    $this->registerWindowCallback('github_auth', 'GithubAuthWindowCallback');

    $this->logInUser();
  }

  public function logInUser() {
    /*
    $cu = $this->hc->getUserManager()->getLoggedInUser();

    if ($cu === false) {
      $this->loggedIn = false;
      return;
    }*/

     $username = $this->hc->getDB()->getConfigValue('module.GithubModule.username');
     $password = $this->hc->getDB()->getConfigValue('module.GithubModule.password');

     if ($username === false || $password === false) {
       $this->loggedIn = false;
     } else {
       $this->loggedIn = true;
     }
   }

  public static function setup($hc) {
    $sql = "CREATE TABLE IF NOT EXISTS hc_m_GithubModule_credentials(
      username VARCHAR(64) NOT NULL,
      password VARCHAR(64) NOT NULL,
      PRIMARY KEY (`username`)
    )";
    $db = $stmt = $hc->getDB()->getDBo();
    $stmt = $db->prepare($sql);
    return $stmt->execute();
  }

  public function onCreatingSidebar(&$sidebar) {
    $newEntry = [
      'icon' => 'github',
      'text' => 'Github',
      'id' => 'github',
    ];
    array_unshift($sidebar, $newEntry); // To prepend the entry
  }

  public function GithubWindowCallback() {
    if (!$this->loggedIn) return $this->GithubAuthWindowCallback();

    $html = '<p>Has iniciado sesión correctamente.</p>
             <br>
    ';

    $owner = 'tan-tan-kanarek';
    $repo = 'github-php-client';

    $client = new GitHubClient();
    //$client->setPage();
    //$client->setPageSize(2);
    $commits = $client->repos->commits->listCommitsOnRepository($owner, $repo);

    /*
    echo "Count: " . count($commits) . "\n";
    foreach($commits as $commit)
    {
        /* @var $commit GitHubCommit
        echo get_class($commit) . " - Sha: " . $commit->getSha() . "\n";
    }

    $commits = $client->getNextPage();

    echo "Count: " . count($commits) . "\n";
    foreach($commits as $commit)
    {
        /* @var $commit GitHubCommit
        echo get_class($commit) . " - Sha: " . $commit->getSha() . "\n";
    }*/

    return [
      'html' => $html,
      'title' => '<svg class="icon github windowicon">
        <use xlink:href="#github">
        </use>
      </svg>
      Github',
    ];
  }

  public function GithubAuthWindowCallback() {
     $cu = $this->hc->getUserManager()->getLoggedInUser();
     if ($this->loggedIn) return $this->GithubWindowCallback();

     $html = '
      <form action="modules/GithubModule/GithubAuth.php" method="POST">
         <input type="text" name="username" placeholder="Nombre de usuario" class="githubmodule_credentialsinput">
         <br>
         <input type="password" name="password" placeholder="Contraseña" class="githubmodule_credentialsinput">
         <br>
         <br>
         <input type="submit" value="Iniciar sesión" id="githubmodule_login">
      </form>
       ';

     return [
       'html' => $html,
       'title' => '<svg class="icon github windowicon">
         <use xlink:href="#github">
         </use>
       </svg>
       Github',
     ];
  }

  public function GithubNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Example</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

   public function onCreatingMetacode(&$metacode) {

     $metacode[] = '<link rel="stylesheet" href="modules/GithubModule/style.css">';
   }

}
