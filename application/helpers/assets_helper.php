<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
function css_url($nom){
  return base_url().'assets/css/'.$nom;
}

function js_url($nom){
  return base_url().'assets/javascript/'.$nom;
}

function img_url($nom){
  return base_url().'assets/images/'.$nom;
}
