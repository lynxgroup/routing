<?php namespace LynxGroup\Component\Routing;

use LynxGroup\Component\Odm\Repository;

class RouteRepository extends Repository
{
	public function match($path, &$args = null)
	{
		foreach( $this->query() as $route ) {
			$args = $route->match($path);

			if (is_array($args)) {
				return $route;
			}
		}
	}
}
