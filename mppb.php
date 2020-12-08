<?php
	
	// Miniatures Paint Progess Bar (mppb) by Ludorg aka Adinarak [2008/04/05]
	// mppb is free software (beerware accepted)
	
	$mppb_error = false;
	
	// Option border
	// mppb.php?border=no
	// mppb.php?border=0
	$border = $_GET['border'];
	if( "no" == $border || "0" == $border )
	{
		$border = 0;
	}
	else 
	{
		$border = 1;
	} 

	// Option type d'image (TODO)
	$img_name="bar1.png";
	$im_orig = imagecreatefrompng( $img_name );
	$x_tot=imagesx($im_orig);
	$y_tot=imagesy($im_orig);


	// Resize de l'image
	$do_resize = false;

	if( isset( $_GET[ 'width' ] ) ) 
	{
		$w = (int)$_GET[ 'width' ];
		$do_resize = true;
	}
	else
	{
		$w = $x_tot;	
	}

	if( isset( $_GET[ 'height' ] ) ) 
	{
		$h = (int)$_GET[ 'height' ];
		$do_resize = true;
	}
	else
	{
		$h = $y_tot;
	}

	if( true == $do_resize )
	{	
		$im = imagecreatetruecolor($w, $h);
		imagecopyresampled( $im, $im_orig, 0, 0, 0, 0, $w, $h, $x_tot, $y_tot );
		imagedestroy($im_orig);	
	}
	else
	{
		$im = $im_orig;
	}

	$x_tot=imagesx($im);
	$y_tot=imagesy($im);
		
	// 10 steps (les 9 de DragonTigre + l'achat)
	// 1 achat
	// 3 dégrappage 
	// 7 ébavurage + suppression du flash
	// 7 assemblage
	// 3 ensablage socle
	// 4 sous-couche 
	// 60 peinture
	// 5 peinture socle
	// 5 herbe statique
	// 5 vernis

	$steps_duration = array( 1, 3, 7, 7, 3, 4, 60, 5, 5, 5);
	$steps_name = array( "Achat", "Dégrappage", "Ebavurage", "Assemblage", "Ensablage socle", "Sous-couche", "Peinture", "Peinture socle", "Herbage", "Vernis");
	$steps_name_en = array( "Buy the minis", "needs translation", "needs translation", "needs translation", "needs translation", "needs translation", "Paint the minis", "needs translation", "needs translation", "needs translation" );

	// Afichage de la bordure
	$black = imagecolorallocate($im, 0, 0, 0);
	if( 1 == $border )
	{
		imagepolygon($im, array( 0, 0, 
			$x_tot-1, 0,
			$x_tot-1, $y_tot-1,
			0, $y_tot-1),
			4,
			$black);
	}	

	// Affichage des lignes
	$x = 0; // x est compris entre 0 et 100
	for( $i = 0; $i <  sizeof( $steps_duration ); $i++ )
	{
		$x += $steps_duration[ $i ];
		imageline( $im, ( $x * $x_tot ) / 100, 0, ( $x * $x_tot ) / 100, $y_tot, $black );
	}

	// Affichage des barres
	$bg1 = imagecolorallocatealpha( $im, 0, 240, 0, 70 );
	$bg2 = imagecolorallocatealpha( $im, 250, 0, 0, 60 );
	function allocate_color_opt( $opt, $im )
	{
		$bg1_opt = (int)hexdec( $opt );
		$bg1_r = $bg1_opt >> 24 & 0xff;
		$bg1_g = $bg1_opt >> 16 & 0xff;
		$bg1_b = $bg1_opt >> 8 & 0xff;
		$bg1_a = $bg1_opt & 0x7f;	
		return imagecolorallocatealpha( $im, $bg1_r, $bg1_g, $bg1_b, $bg1_a );	
	}

	if( isset($_GET[ 'bg1_color' ]) ) 
	{
		$bg1 = allocate_color_opt( $_GET[ 'bg1_color' ], $im );
	}

	if( isset($_GET[ 'bg2_color' ]) ) 
	{
		$bg2 = allocate_color_opt( $_GET[ 'bg2_color' ], $im );
	}

	// mppb.php?step=1 => la figurine a été achetée
	// mppb.php?step=3
	// mppb.php?step=6.4 => figurine peinte à 40%

	if( isset($_GET[ 'step' ]) )
	{
		$step = (double)$_GET[ 'step' ];
	}
	else
	{
		$mppb_error = true;
	}

	$x=0;
	if( ($step >= 0 ) && ( $step < sizeof( $steps_duration ) + 1 ) )
	{
		for( $i = 0; $i < (int)(floor( $step )); $i++ )
		{
			$x += $steps_duration[ $i ];
		}	
	
		// La partie décimale de step indique l'avancement (entre 0 et 1) pour le step concerné
		$step_advance = $step - floor( $step );
		$x += $step_advance * $steps_duration[ floor( $step ) ];		
	
		$x = $x * $x_tot / 100;

		imagefilledpolygon($im, array( 0, 0, 
			$x-1, 0,
			$x-1, $y_tot-1,
			0, $y_tot-1),
			4,
			$bg1);

		imagefilledpolygon($im, array( $x, 0, 
			$x_tot, 0,
			$x_tot, $y_tot-1,
			$x, $y_tot-1),
			4,
			$bg2);
	}
	else
	{
		$mppb_error = true;	
	}
	
	if( true == $mppb_error )
	{
		$error_str = "error => mppb.php?rtfm";
		$text_color = imagecolorallocate($im, 255, 255, 255);		
		
		$line_width = strlen( $error_str ) * imagefontwidth( 5 );
		$line_height = imagefontheight( 5 );				
		
		imagestring( $im, 5, ($x_tot - $line_width ) / 2, ($y_tot - $line_height ) / 2, $error_str, $text_color );
		

	}
	else
	{
		
	}

	if( !isset($_GET[ 'rtfm' ]) )
	{
		header("Content-type: image/png");
		imagepng($im);

		imagecolordeallocate( $bg1, $im );
		imagecolordeallocate( $bg2, $im );
		imagecolordeallocate( $black, $im );

		imagedestroy($im);
	}		
	else
	{
		echo "<h1>mppb usage (rtfm)</h2>";
		echo "<p>Miniatures Painting Progess Bar (mppb) by Ludorg aka Adinarak [2008/04/05]</br>";
		echo "<i>mppb is free software (beerware accepted)</i><br/></p>";
	
		echo "<h2>What is mppb ?</h2>";
		echo "<p>mppb is a php script inspired by <a href=\"http://poussefigs.canalblog.com/archives/2005/12/10/1093251.html\">Graphiques d'avancement des figurines on 'Journal d'un pousseur de figurines'</a>. </p>";
		echo "This script generates a progress bar (png image) showing the achievement in the process of painting miniatures. This process has been divided in 10 steps of different lengths which are : </p>";
		echo "<p><ul>";
		for( $i = 0; $i < sizeof($steps_duration); $i++ )
		{
			echo "<li> Step #", $i+1, " is ", $steps_name_en[$i], " (", $steps_name[$i], ")", " and is ", $steps_duration[$i], "% in miniature painting process", "</li>";
		}
		echo "</p></ul>";
		//$steps_duration = array( 1, 3, 7, 7, 3, 4, 60, 5, 5, 5);
		//$steps_name = array( "Achat", "Dégrappage", "Ebavurage", "Assemblage", "Ensablage socle", "Sous-couche", "Peinture", "Peinture socle", "Herbage", "Vernis");

		echo "<p>A half-painted miniature or a group of miniatures has not yet reached step 7 and is in step 6.5.</p>";
		
		echo "<h2>Options</h2>";
		echo "<ul><li>step (mandatory)</li>";
		echo "<li>width : int</li>";
		echo "<li>height : int</li>";
		echo "<li>border : hide border (0/no)</li>";
		echo "<li>bg1_color : hex color (rgba) for the left part of bar</li>";
		echo "<li>bg2_color : hex color (rgba) the right part of bar</li>";
		echo "<li>rtfm : this help</li>";
		echo "</ul>";
	
		echo "<h2>Using this script</h2>";
		echo "<p><code>&lt;img src=\"http://ludorg.net/adinarak/mppb.php?step=6.75&width=400&height=20\"&gt;</code></p>";
	
		echo "<h2>Examples</h2>";
		echo "<p><code>http://ludorg.net/adinarak/mppb.php?step=3</code></p>";
		echo "<img src=\"http://ludorg.net/adinarak/mppb.php?step=3\">";
		echo "<p><code>http://ludorg.net/adinarak/mppb.php?step=6.8</code></p>";
		echo "<img src=\"http://ludorg.net/adinarak/mppb.php?step=6.8\">";
		echo "<p><code>http://ludorg.net/adinarak/mppb.php?step=6.1&width=200</code></p>";
		echo "<img src=\"http://ludorg.net/adinarak/mppb.php?step=6.1&width=200\">";
		echo "<p><code>http://ludorg.net/adinarak/mppb.php?step=6.9&height=80</code></p>";
		echo "<img src=\"http://ludorg.net/adinarak/mppb.php?step=6.9&height=80\">";
		echo "<p><code>http://ludorg.net/adinarak/mppb.php?step=6.75&width=400&height=20</code></p>";
		echo "<img src=\"http://ludorg.net/adinarak/mppb.php?step=6.75&width=400&height=20\">";
		echo "<p><code>http://ludorg.net/adinarak/mppb.php?step=6.5&bg1_color=0x7f007f60&bg2_color=0x0f007f60&width=600&height=35</code></p>";
		echo "<img src=\"http://ludorg.net/adinarak/mppb.php?step=6.5&bg1_color=0x7f007f60&bg2_color=0x0f007f60&width=600&height=35\">";
		echo "<p><code>http://ludorg.net/adinarak/mppb.php?step=4.7&border=no</code></p>";
		echo "<img src=\"http://ludorg.net/adinarak/mppb.php?step=4.7&border=no\">";
		echo "<p><code>http://ludorg.net/adinarak/mppb.php</code></p>";
		echo "<img src=\"http://ludorg.net/adinarak/mppb.php\">";
				
	}


?> 

