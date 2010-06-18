<ul class="archlist-left">

<?php function get_monthly_archives()
	{	$monthly_archives_l= '';
		$monthly_archives_r= '';

		$now= date( 'Y-m-d H:i:s' );

 $yr_mths = $wpdb->get_col("SELECT YEAR(post_date) AS year, MONTH(post_date) AS month, COUNT(post_id) AS cnt FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC"); 
		$size=count($yr_mths);
		$count=1;
		$monthly_archives_l.="<ul class=\"archlist-left\">\n";
		foreach ( $yr_mths as $yr_mth ) {
			if ($count&1){
				$month= substr('0' . $yr_mth->month, -2, 2);
				$monthly_archives_l.= '<li><a href="'. get_month_link($yr_mth->year, $month->month). '" title="View entries in ' . "{$yr_mth->month_name()}, {$yr_mth->year}". '">' . "{$yr_mth->year}, {$yr_mth->month_name()}</a></li>\n";
			}
			$count++;
		}	
		if ($count==1){
			$monthly_archives_r.="<li></li>\n</ul>\n";		
		}
		else{
			$monthly_archives_r.="</ul>\n";
		}

		reset ($yr_mths);
		$count=1;
		$monthly_archives_r.="<ul class=\"archlist-right\">\n";
		foreach ( $yr_mths as $yr_mth ) {
			if (!($count&1)){
				$month= substr('0' . $yr_mth->month, -2, 2);
				$monthly_archives_r.= '<li><a href="' . URL::get( 'display_entries_by_date', array( 'year' => $yr_mth->year, 'month' => $month ) ) . '" title="View entries in ' . "{$yr_mth->month_name}, {$yr_mth->year}". '">' . "{$yr_mth->month_name} :: {$yr_mth->year}</a></li>\n";
			}
			$count++;
		}
		if ($count==2){
			$monthly_archives_r.="<li></li>\n</ul>\n";		
		}
		else{
			$monthly_archives_r.="</ul>\n";
		}
		return "<div id=\"archives\">" . $monthly_archives_l."\n". $monthly_archives_r . "<br class=\"clear\" />\n</div>";
	}
//}
?>

