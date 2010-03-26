<?php

/**
 * 
 * @author Jérémy Faivre
 *
 */
class dmConfigOverriderPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $this->dispatcher->connect('dm.context.loaded', array($this, 'listenToContextLoadedEvent'));
  }
  
  /**
   * Copy dmConfig(app_*) to sfConfig(app_*) and dmConfig(app::{current_app}_*) to sfConfig(app_*).
   * 
   * @param sfEvent $e
   */
  public function listenToContextLoadedEvent(sfEvent $e)
  {
  	$sf_app_prefix = sfConfig::get('sf_app').'_';
  	$sf_app_len = strlen($sf_app_prefix);
  	
    foreach ( dmConfig::getAll() as $key => $val )
    {
        if ( substr($key, 0, 4) == 'app_' )
        {
           sfConfig::set($key, $val);
        }
        else if ( substr($key, 0, 5) == 'app::' )
        {
        	if ( substr($key, 5, $sf_app_len) == $sf_app_prefix )
        	   sfConfig::set('app_'.substr($key, 5 + $sf_app_len), $val);
        }
    }
  }
}