<?php

class osapiVzProvider extends osapiProvider {
    const STUDIVZ    = 'studivz';
    const MEINVZ     = 'meinvz';
    const SCHUELERVZ = 'schuelervz';
    const SANDBOX    = 'sandbox';

  public function __construct($network = osapiVzProvider::STUDIVZ, osapiHttpProvider $httpProvider = null) {
    $platform = 'www.' . $network . '.net';
    $shindig  = $network . '.gadgets.apivz.net';
    parent::__construct("http://" . $platform . "/OAuth/RequestToken/",
        "http://" . $platform . "/OAuth/Authorize/",
        "http://" . $platform . "/OAuth/AccessToken/",
        "http://" . $shindig . "/social/rest/",
        "http://" . $shindig . "/social/rpc/", "Vz", true, $httpProvider);
  }
}
