<?php

if (class_exists('JComponentRouterBase')) {
	abstract class CastorRouterBase extends JComponentRouterBase
	{
	}
} else {
	class CastorRouterBase
	{
		var $app;
		var $menu;

		public function __construct($app = null, $menu = null)
		{
			$this->app = empty($app) ? JFactory::getApplication('site') : $app;
			$this->menu = empty($menu) ? $this->app->getMenu() : $menu;
		}
	}
}

