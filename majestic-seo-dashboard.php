<?php
/**
 * Plugin Name: Majestic SEO Dashboard Widget
 * Plugin URI: http://www.tailoredinternetmarketing.com/blog/majestic-seo-dashboard-graphs/
 * Description: Majestic SEO Dashboard Widget
 * Version: 3.0
 * Author: Dan Taylor
 * Author URI: http://www.tailoredinternetmarketing.com/
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
 if (!class_exists("drt_mseo_plugin")) {
    class drt_mseo_plugin {
		
		public function __construct() {
			add_action( 'admin_init', array($this, 'register_majsettings') );
			add_action('admin_menu', array($this, 'add_majestic_seo_submenu'));
			add_action('admin_footer',array($this, 'majestic_plugin_downloads_head'),99);
			add_action('wp_dashboard_setup', array($this, 'majestic_add_dashboard_widgets' ));
		}
		
		public function get_majestic_graph($type, $url1, $url2='', $url3='', $url4='', $url5='') {
			if($type == 0) {
				$content = 'http://www.majesticseo.com/domaincharts.php?d='.$url1.','.$url2.','.$url3.','.$url4.','.$url5.'&w=670&h=250&t=l&ctype=0';
			} elseif($type == 1) {
				$content = 'http://www.majesticseo.com/domaincharts.php?d='.$url1.','.$url2.','.$url3.','.$url4.','.$url5.'&w=670&h=250&t=l&a=1&ctype=0';
			} elseif($type == 2) {
				$content = 'http://www.majesticseo.com/domaincharts.php?d='.$url1.','.$url2.','.$url3.','.$url4.','.$url5.'&w=670&h=250&t=ls&ctype=0';
			}
			$standard = '<a href="http://www.majesticseo.com/comparedomainbacklinkhistory.php?d0='.$url1.'&d1='.$url2.'&d2='.$url3.'&d3='.$url4.'&d4='.$url5.'&type='.$type.'"><img style="max-width: 670px; width: 100%;" src="'.$content.'" /></a>';
			return $standard;
		}
		
		public function maj_dashboard_widget() {
			$url = $_SERVER['SERVER_NAME'];
			$url = str_replace('www.','',$url);
			$comp1 = get_option('maj-comp-1');
			$comp2 = get_option('maj-comp-2');
			$comp3 = get_option('maj-comp-3');
			$comp4 = get_option('maj-comp-4');
			
			echo '
			<div id="tabSystem">
		<ul style="width: 100%;" class="htabs hide">
			<li class="sell" style="float: left; padding: 10px;"><a href="#sell">Cumulative View</a></li>
			<li class="car" style="float: left; padding: 10px;"><a href="#quick">Monthly View</a></li>
			<li class="quick" style="float: left; padding: 10px;"><a href="#buy">Normalized</a></li>
		</ul>
		<div style="clear: both;"></div>
		<div class="tabs">
			<div id="sell" class="tab">
				'.$this->get_majestic_graph(1, $url, $comp1, $comp2, $comp3, $comp4).'
			</div>
			<div id="quick" class="tab">
				'.$this->get_majestic_graph(0, $url, $comp1, $comp2, $comp3, $comp4).'
			</div>
			<div id="buy" class="tab">
				'.$this->get_majestic_graph(2, $url, $comp1, $comp2, $comp3, $comp4).'
		
			</div></div></div>
			<p style="text-align: center;">Click graph for larger view and more options</p>
			<p style="text-align: center;">You must be <a href="https://www.majesticseo.com/login.php" target="_blank">Logged In</a> to Majestic SEO to view competitors</p>
			<table width="100%"><tr><td width="50%" align="left">Reports by <a href="http://www.majesticseo.com/comparedomainbacklinkhistory.php?d0='.$url.'&d1='.$comp1.'&d2='.$comp2.'&d3='.$comp3.'&d4='.$comp4.'">Majestic SEO</a></td><td width="50%" style="text-align:right;">Plugin by <a href="http://www.tailoredinternetmarketing.com">Tailored Internet Marketing LTD</a></td></tr></table>';
		} 

		public function majestic_add_dashboard_widgets() {
			wp_add_dashboard_widget('majestic_seo_dashboard_widget', 'Majestic SEO - Backlink Charts', array($this, 'maj_dashboard_widget'));
		} 
		
		public function majestic_plugin_downloads_head() {
			if($_SERVER['REQUEST_URI'] =='/wp-admin/index.php' || $_SERVER['REQUEST_URI'] =='/wp-admin/') {
			echo '<script type="text/javascript" charset="utf-8">
			function cctabs() {
				jQuery(".tab:not(:first)").hide();
				jQuery(".tab:first").show();
				jQuery(".htabs a:first").addClass("gt_active");
				jQuery(".htabs a").click(function(){
					jQuery(\'.htabs a\').removeClass(\'gt_active\');
					jQuery(this).addClass("gt_active");
					stringref=jQuery(this).attr("href").split(\'#\')[1];
					jQuery(\'.tab:not(#\'+stringref+\')\').hide();
					if(jQuery.browser.msie&&jQuery.browser.version.substr(0,3)=="6.0"){
						jQuery(\'.tab#\'+stringref).show();
					}
				else
					jQuery(\'.tab#\'+stringref).show();
							return false;
					
				});
			}
			jQuery(document).ready(function() {
				cctabs();
			});
			</script>';}
		}
		
		public function register_majsettings() {
			register_setting( 'majestic-group', 'maj-comp-1' );
			register_setting( 'majestic-group', 'maj-comp-2' );
			register_setting( 'majestic-group', 'maj-comp-3' );
			register_setting( 'majestic-group', 'maj-comp-4' );
		}
		
		public function majestic_seo_options() {
			echo '
				<div class="wrap">
					<h2>Majestic SEO Dashboard Graph Options</h2>';
			echo'
					<form method="post" action="options.php">';
			settings_fields( 'majestic-group' ); 
			$comp1 = get_option('maj-comp-1');
			$comp2 = get_option('maj-comp-2');
			$comp3 = get_option('maj-comp-3');
			$comp4 = get_option('maj-comp-4');
			
			echo '<table><tr><th colspan="2"><h4>All addresses below should be in the format \'google.com\' NOT \'www.google.com\' OR \'http://www.google.com\'</h4></th></tr>
				<tr>
				<th align="left" scope="row" width="200">Competitor 1</th>
				<td><input type="text" name="maj-comp-1" value="'. $comp1  .'" /></td>
			  </tr>
			  <tr>
				<th align="left" scope="row">Competitor 2</th>
				<td><input type="text" name="maj-comp-2" value="'. $comp2  .'" /></td>
			  </tr>
			  <tr>
				<th align="left" scope="row">Competitor 3</th>
				<td><input type="text" name="maj-comp-3" value="'. $comp3  .'" /></td>
			  </tr>
			  <tr>
				<th align="left" scope="row">Competitor 4</th>
				<td><input type="text" name="maj-comp-4" value="'. $comp4  .'" /></td>
			  </tr>
				<tr><th></th><td><p>You must be <a href="https://www.majesticseo.com/login.php" target="_blank">Logged In</a> to Majestic SEO to view competitors on dashboard</p>
				</td></tr>
				</table>
				
					'; 
				submit_button();
				echo '</form>';
			}
		public function add_majestic_seo_submenu() {
			add_submenu_page('options-general.php', 'Majestic SEO Dashboard', 'Majestic SEO Dashboard', 10, __FILE__, array($this, 'majestic_seo_options')); 		
		}
	}
 }
 if (class_exists("drt_mseo_plugin")) {
    $drt_mseo_plugin = new drt_mseo_plugin();
}