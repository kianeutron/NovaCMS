<?php
namespace NovaCMS\Core;

class BaseController
{
    protected function view($view, $data = [])
    {
        extract($data);
       include __DIR__ . '/../Views/layout.php';
    }
     protected function redirect ($url) {
     header ("location: $url");
     exit ;
     }
}