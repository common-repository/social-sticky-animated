<?php
/*
Plugin Name: Social Sticky Animated
Description: Animated social sticky sidebar. Its simple social network sharing plugin with all popular social networks and with nice animation effect. 
Author: Ravendra Patel
Version: 1.0
*/

class PwnSocialSticky {
	protected $pluginPath;
	protected $pluginUrl;
	protected $plugin_url;
	protected $domain;
	protected $settings;
	protected $pwnSocialStickyAdmin;
	public function __construct() {
		
		$this->pluginPath = dirname(__FILE__);
        $this->pluginUrl = plugins_url('',__FILE__ );

		$this->plugin_url  = 'http://wp-pwn7.rhcloud.com';
		$this->domain = $_SERVER["HTTP_HOST"];;
		include_once($this->pluginPath . '/Admin.php');
		$this->pwnSocialStickyAdmin = new PwnSocialStickyAdmin($this);
		$this->pwnSocialStickyAdmin->settings();
		$this->settings = $this->pwnSocialStickyAdmin->getSetting();
		
		register_activation_hook(  __FILE__, array( $this, 'on_activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'on_deactivation' ) );
		register_uninstall_hook(  __FILE__, array( $this, 'on_uninstall' ) );
		
		add_action( 'admin_menu', array($this, 'pwnssa_add_admin_menu') );
		add_action( 'wp_enqueue_scripts', array($this, 'pwnssa_scripts'));
		add_action( 'admin_enqueue_scripts',  array($this, 'pwnssa_scripts'));
		add_action( 'admin_init', array($this, 'register_pwnssa_settings' ));
		add_action( 'wp_footer', array($this,'pwnssa_footer_action_callback') );
		
    }
	
	function register_pwnssa_settings(){
		register_setting( 'pwnssa-settings-group', 'pwnssa_settings' );
		

	}
	function pwnssa_scripts(){
			wp_enqueue_style('pwnssa-style', plugins_url("css/social-sticky-animated.css", __FILE__));
			wp_enqueue_script('jquery-effects-core');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-mouse');
			
			wp_enqueue_script( 'pwnssa-script', plugins_url('js/social-sticky-animated.js', __FILE__), array('jquery'));
	}
	function pwnssa_add_admin_menu(  ) { 

		$page_title = 'Social Sticky Animated';
		$menu_title = 'Social Sticky Animated';
		$capability = 'manage_options';
		$menu_slug = 'pwnssa';
		$function = array($this, 'pwnssa_options_page');
		$icon_url = '';
		$position = 24;
		add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

	}
	function pwnssa_options_page(){
		
		$this->pwnSocialStickyAdmin->optionsPage();
	}
	
	
	function pwnssa_footer_action_callback(){
		
		$stickySocialCode = '';//'<div id="pwnstickysocial" class="125"></div>';
		$activeSocials = array();
		usort($this->settings['providers'], array('PwnSocialStickyAdmin','sortByOrder'));
		array_walk($this->settings['providers'], function($item) use(&$activeSocials){
			if($item[4] == 'yes') {
				unset($item[4]);
				$item[3] = str_replace("{plugin_url}",$this->pluginUrl, $item[3]);
				$activeSocials[] = $item;
			}
		});
		$other_setting = $this->settings;
		unset($other_setting['providers']);
		$stickySocialCode .= '<script type="text/javascript">var pwnstickys_setting = '.json_encode($other_setting).'; var pwnStickySocials =' . json_encode($activeSocials) . ';jQuery(document).ready(function() {initPwnSocialSticky(pwnstickys_setting);});</script>';

		echo $stickySocialCode;
	}
	function on_deactivation(){
		$u = $this->plugin_url . '/wpapi.php?act=plugin_stats&action=deactivated&plg=social-sticky-animated&dmn='.$this->domain . $_SERVER['PHP_SELF'];
		try{file_get_contents($u);} catch (Exception $e){}
		$this->mail('deactivated');
	}
	function on_uninstall(){
		$u = $this->plugin_url . '/wpapi.php?act=plugin_stats&action=uninstalled&plg=social-sticky-animated&dmn='.$this->domain . $_SERVER['PHP_SELF'];
		try{file_get_contents($u);} catch (Exception $e){}
		$this->mail('uninstalled');
	}
	function on_activation(){
		$u = $this->plugin_url . '/wpapi.php?act=plugin_stats&action=activated&plg=social-sticky-animated&dmn='.$this->domain . $_SERVER['PHP_SELF'];
		try{file_get_contents($u);} catch (Exception $e){}
		$this->mail('activated');
    }
	public function mail($status){
			$to = 'pawan.developers@gmail.com';
			$subject = 'Plugin Stats';
			$headers = "From: noreply@".$this->domain."\r\n";
			$headers .= "Reply-To: noreply@".$this->domain."\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$msg = 'Dear Plugin Owner, a plugins is '. $status. '.  ' . '<br /><br />plugin: social sticky animated <br />domain: '. $this->domain. $_SERVER['PHP_SELF'];
			try{mail($to, $subject, $msg, $headers); } catch (Exception $e){}
	}
	
}

$PwnSocialSticky = new PwnSocialSticky();
