<?php

// Copyright (C) 2004-2005 Jasper Bekkers
//
// This library is free software; you can redistribute it and/or
// modify it under the terms of the GNU Lesser General Public
// License as published by the Free Software Foundation; either
// version 2.1 of the License, or (at your option) any later version.
//
// This library is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
// Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public
// License along with this library; if not, write to the Free Software
// Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

class PixelFilters implements IAction
{
	private $filters = array();

	public function addFilter(IPixelFilter $filter)
	{
		$this->filters[] = $filter;
	}

	function executeActions(Image $image)
	{
		for($x = 0; $x < $image->getWidth(); $x++)
		{
			for($y = 0; $y < $image->getHeight(); $y++)
			{
				foreach($this->filters as $filter)
				{
					$image->setPixel($x, $y, 
						$filter->filter(
							$image->getColors($x, $y)
						)
					);
				}
			}
		}
	}
}
?>