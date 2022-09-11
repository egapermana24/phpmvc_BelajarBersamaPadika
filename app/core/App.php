<?php

class App
{
  protected $controller = 'home';
  protected $method = 'index';
  protected $params = [];

  public function __construct()
  {
    $url = $this->parseURL();

    // Cek apakah controller ada atau tidak
    if ($url == NULL) {
      $url = [$this->controller];
    }
    if (file_exists('../app/controllers/' . $url[0] . '.php')) {
      $this->controller = $url[0];
      unset($url[0]);
    }
    // Require controller
    require_once '../app/controllers/' . $this->controller . '.php';
    $this->controller = new $this->controller;

    // Cek apakah method ada atau tidak
    if (isset($url[1])) {
      if (method_exists($this->controller, $url[1])) {
        $this->method = $url[1];
        unset($url[1]);
      }
    }

    // cek apakah ada parameter
    if (!empty($url)) {
      $this->params = array_values($url);
    }


    // jalankan controller dan method, dan kirimkan parameter jika ada
    call_user_func_array([$this->controller, $this->method], $this->params);
  }

  public function parseURL()
  {
    if (isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/'); // menghilangkan '/' di akhir url
      $url = filter_var($url, FILTER_SANITIZE_URL); // menghilangkan karakter yang tidak diperbolehkan
      $url = explode('/', $url); // memecah url menjadi array
      return $url;
    }
  }
}
