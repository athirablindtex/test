<?php
  //Loads configuration from database into global CI config
  function load_config()
  {
   $CI =& get_instance();
   foreach($CI->configmodel->gets_all()->result() as $site_config)
   {
    $CI->config->set_item($site_config->key_config,$site_config->value);
   }
  }
?>