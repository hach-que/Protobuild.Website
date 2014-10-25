<?php

$protobuild_root = dirname(dirname(__FILE__));
require_once $protobuild_root.'/support/ProtobuildStartup.php';

require_once $protobuild_root.'/externals/Google/Config.php';
require_once $protobuild_root.'/externals/Google/Client.php';
require_once $protobuild_root.'/externals/Google/Exception.php';
require_once $protobuild_root.'/externals/Google/Model.php';
require_once $protobuild_root.'/externals/Google/Utils.php';
require_once $protobuild_root.'/externals/Google/Collection.php';
require_once $protobuild_root.'/externals/Google/Service.php';
require_once $protobuild_root.'/externals/Google/Service/Resource.php';
require_once $protobuild_root.'/externals/Google/Service/Oauth2.php';
require_once $protobuild_root.'/externals/Google/Service/Datastore.php';
require_once $protobuild_root.'/externals/Google/Service/Exception.php';
require_once $protobuild_root.'/externals/Google/Service/Storage.php';
require_once $protobuild_root.'/externals/Google/Auth/Abstract.php';
require_once $protobuild_root.'/externals/Google/Auth/AssertionCredentials.php';
require_once $protobuild_root.'/externals/Google/Auth/Exception.php';
require_once $protobuild_root.'/externals/Google/Auth/OAuth2.php';
require_once $protobuild_root.'/externals/Google/Http/Request.php';
require_once $protobuild_root.'/externals/Google/Http/CacheParser.php';
require_once $protobuild_root.'/externals/Google/Http/REST.php';
require_once $protobuild_root.'/externals/Google/Http/MediaFileUpload.php';
require_once $protobuild_root.'/externals/Google/IO/Abstract.php';
require_once $protobuild_root.'/externals/Google/IO/Curl.php';
require_once $protobuild_root.'/externals/Google/IO/Exception.php';
require_once $protobuild_root.'/externals/Google/Utils/URITemplate.php';
require_once $protobuild_root.'/externals/Google/Cache/Abstract.php';
require_once $protobuild_root.'/externals/Google/Cache/File.php';
require_once $protobuild_root.'/externals/Google/Signer/Abstract.php';
require_once $protobuild_root.'/externals/Google/Signer/P12.php';

ProtobuildStartup::didStartup();

$show_unexpected_traces = false;
try {
  ProtobuildStartup::loadCoreLibraries();

  $delegation = new RouteDelegation();
  list($controller_class, $request) = 
    $delegation->getControllerAndDataForUri($_REQUEST['__path__']);
  
  if ($controller_class === null) {
    throw new Protobuild404Exception(CommonErrors::PAGE_NOT_FOUND);
  }
  
  $controller = new $controller_class();
  $controller->beginRequest($request);
  echo $controller->processRequest($request);
} catch (Protobuild404Exception $ex) {
  $controller = new ProtobuildErrorController();
  $controller->setException($ex);
  $controller->setCode(404);
  $controller->beginRequest(array());
  echo $controller->processRequest(array());
} catch (ProtobuildException $ex) {
  $controller = new ProtobuildErrorController();
  $controller->setException($ex);
  $controller->setCode(500);
  $controller->beginRequest(array());
  echo $controller->processRequest(array());
} catch (ProtobuildRedirectException $ex) {
  header('Location: '.$ex->getURI());
  die();
} catch (Exception $ex) {
  ProtobuildStartup::didEncounterFatalException(
    'Core Exception',
    $ex,
    $show_unexpected_traces);
}
