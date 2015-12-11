<?php namespace LynxGroup\Component\Routing;

use LynxGroup\Component\Odm\Document;

class Route extends Document
{
	public function setName($name)
	{
		$this->data['name'] = $name;

		return $this->setDirty();
	}

	public function getName()
	{
		return isset($this->data['name']) ? $this->data['name'] : null;
	}

	public function setPattern($pattern = null)
	{
		$this->data['pattern'] = $pattern;

		return $this->setDirty();
	}

	public function getPattern()
	{
		return isset($this->data['pattern']) ? $this->data['pattern'] : null;
	}

	public function setController(array $controller)
	{
		$this->data['controller'] = $controller;

		return $this->setDirty();
	}

	public function getController()
	{
		return isset($this->data['controller']) ? $this->data['controller'] : ['Controller\\Controller', 'index'];
	}

	public function setPublished($published)
	{
		$this->data['published'] = $published;

		return $this->setDirty();
	}

	public function getPublished()
	{
		return isset($this->data['published']) ? $this->data['published'] : false;
	}

	public function setPermission($permission, array $roles)
	{
		$this->data['permission'][$permission] = $roles;

		return $this->setDirty();
	}

	public function getPermission($permission)
	{
		return isset($this->data['permission'][$permission]) ? $this->data['permission'][$permission] : ['USER', 'VISITOR'];
	}

	public function match($path)
	{
		$slugs = [];

		$args = [];

		foreach( array_filter(explode('/', $this->getPattern())) as $slug )
		{
			if( preg_match('#^{(\w+)}$#', $slug, $matches) )
			{
				array_shift($matches);

				$args[current($matches)] = null;

				$slugs[] = '(\w+)';
			}
			else
			{
				$slugs[] = $slug;
			}
		}

		$pattern = '/'.implode('/', $slugs);

		if( preg_match('#^'.$pattern.'$#', $path, $matches) )
		{
			array_shift($matches);

			foreach( $args as $name => $value )
			{
				$args[$name] = array_shift($matches);
			}

			return $args;
		}
	}

	public function makeUrl($args)
	{
		$slugs = [];

		foreach( array_filter(explode('/', $this->getPattern())) as $slug )
		{
			if( preg_match('#^{(\w+)}$#', $slug, $matches) )
			{
				array_shift($matches);

				if( isset($args[current($matches)]) )
				{
					$slugs[] = $args[current($matches)];
				}
			}
			else
			{
				$slugs[] = $slug;
			}
		}

		return '/'.implode('/', $slugs);;
	}
}

