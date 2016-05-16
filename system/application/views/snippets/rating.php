<?php /* Displays the rating in star images */ 

if(!function_exists('_fractionToImage'))
{
	function _fractionToImage($fraction)
	{
		$closestFraction = (round(($fraction*100)/25)*25)/100;
		if($closestFraction > .99)
			return "star_full.png";
		else if($closestFraction > .74)
			return "star_three_quarters.png";
		else if($closestFraction > .49)
			return "star_half.png";
		else if($closestFraction > .24)
			return "star_quarter.png";
		else
			return "star_empty.png";			
	}
}

if(!function_exists('_printRating'))
{
	function _printRating($rating, $count = null)
	{
		if($rating <= 0 || $rating == null)
			return;
		
		echo "<div class='Rating'>";
		
		echo "<div class='RatingStars'>";
		for($i=0; $i<5; $i++)
		{
			$left = $rating - $i;
			$left = $left > 1 ? 1 : $left;
			$left = $left < 0 ? 0 : $left;
			$image = _fractionToImage($left);
			echo "<img src='/images/$image' tag='$image'/>";
		}
		echo "</div>";
		
		if(isset($count))
			echo "<div class='RatingCount'>($count)</div>";
			
		echo "</div>";
	}
}

isset($count) ? _printRating($rating, $count) : _printRating($rating);

?>