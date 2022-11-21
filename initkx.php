<?php
// mochajiankaixindaima

if(isset($_REQUEST['aff']) && !isset($_COOKIE["erphprefid"])){
	setcookie("erphprefid", $_REQUEST['aff'], time()+2592000, '/');
}

$ice_proportion_alipay = get_option('ice_proportion_alipay');
if(!$ice_proportion_alipay){
	add_action( 'admin_notices', 'erphpdown_admin_notice' );
}

function erphpdown_style() {
	global $erphpdown_version;
	wp_enqueue_style( 'erphpdown', constant("erphpdown")."static/erphpdown.css", array(), $erphpdown_version,'screen' );
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'erphpdown', constant("erphpdown")."static/erphpdown.js", false, $erphpdown_version, true);
	wp_localize_script( 'erphpdown', '_ERPHP', array('ajaxurl'=>admin_url("admin-ajax.php")));
	wp_localize_script( 'erphpdown', 'erphpdown_ajax_url', admin_url("admin-ajax.php"));
}
add_action('wp_enqueue_scripts', 'erphpdown_style',20,1);

add_action( 'wp_head', 'erphpdown_head_style' );
function erphpdown_head_style(){
	$erphp_box_style = get_option('erphp_box_style');
?>
<style id="erphpdown-custom"><?php if($erphp_box_style == '1'){?>.erphpdown-default, .erphpdown-see{padding: 15px 25px !important;border: none !important;background: #f5f5f5;}.erphpdown-default > legend{display: none !important;}.erphpdown-default .erphpdown-child{border: none !important;background: #e8e8e8;padding: 25px 15px 15px !important;margin: 10px 0 !important;}.erphpdown-default .erphpdown-child > legend{position: absolute;top: 0;background: #d4d4d4;color: #444;padding: 0 8px !important}<?php }?><?php echo get_option('erphp_custom_css');?></style>
<?php 
}

add_action( 'wp_footer', 'erphpdown_foot_script' );
function erphpdown_foot_script(){
	$erphp_wppay_payment = get_option('erphp_wppay_payment');
?>
<script>window._ERPHPDOWN = {"uri":"<?php echo ERPHPDOWN_URL;?>", "payment": "<?php if($erphp_wppay_payment == 'f2fpay') echo "1";elseif($erphp_wppay_payment == 'f2fpay_weixin') echo "4";elseif($erphp_wppay_payment == 'f2fpay_hupiv3') echo "4";elseif($erphp_wppay_payment == 'weixin') echo "3";elseif($erphp_wppay_payment == 'paypy' || $erphp_wppay_payment == 'vpay' || $erphp_wppay_payment == 'f2fpay_paypy') echo "6";elseif($erphp_wppay_payment == 'hupiv3') echo "5";elseif($erphp_wppay_payment == 'payjs') echo "5"; else echo "2";?>", "wppay": "<?php echo get_option('erphp_wppay_type');?>", "author": "mobantu"}</script>
<?php
}

add_action('user_register', 'erphp_register_extra_fields');
function erphp_register_extra_fields($user_id, $password="", $meta=array()) {
	global $wpdb;
	$erphp_aff_ip = get_option('erphp_aff_ip');
	if($erphp_aff_ip){
		$ice_ali_money_reg = get_option('ice_ali_money_reg');
		$ice_ali_money_new = get_option('ice_ali_money_new');
		if($ice_ali_money_new){
			addUserMoney($user_id,$ice_ali_money_new, '注册奖励');
		}

		if(isset($_COOKIE["erphprefid"]) && is_numeric($_COOKIE["erphprefid"])){
			$sql = "update $wpdb->users set father_id='".esc_sql($_COOKIE["erphprefid"])."',reg_ip = '".erphpGetIP()."' where ID=".$user_id;
			$wpdb->query($sql);
			if($ice_ali_money_reg){
				addUserMoney($_COOKIE["erphprefid"], $ice_ali_money_reg, '推广奖励');
			}
		}
	}else{
		$hasRe = $wpdb->get_row("select ID,father_id from $wpdb->users where reg_ip = '".erphpGetIP()."'");
		$ice_ali_money_reg = get_option('ice_ali_money_reg');
		if($hasRe){
			$sql = "update $wpdb->users set reg_ip = '".erphpGetIP()."' where ID=".$user_id;
			$wpdb->query($sql);

			if(!$hasRe->father_id){
				if(isset($_COOKIE["erphprefid"]) && is_numeric($_COOKIE["erphprefid"])){
					$sql = "update $wpdb->users set father_id='".esc_sql($_COOKIE["erphprefid"])."',reg_ip = '".erphpGetIP()."' where ID=".$user_id;
					$wpdb->query($sql);
					if($ice_ali_money_reg){
						addUserMoney($_COOKIE["erphprefid"], $ice_ali_money_reg, '推广奖励');
					}
				}
			}
		}else{
			$ice_ali_money_new = get_option('ice_ali_money_new');
			if($ice_ali_money_new){
				addUserMoney($user_id,$ice_ali_money_new, '注册奖励');
			}

			if(isset($_COOKIE["erphprefid"]) && is_numeric($_COOKIE["erphprefid"])){
				$sql = "update $wpdb->users set father_id='".esc_sql($_COOKIE["erphprefid"])."',reg_ip = '".erphpGetIP()."' where ID=".$user_id;
				$wpdb->query($sql);
				if($ice_ali_money_reg){
					addUserMoney($_COOKIE["erphprefid"], $ice_ali_money_reg, '推广奖励');
				}
			}
		}
	}
}

add_action("init","erphpdown_noadmin_redirect");
function erphpdown_noadmin_redirect(){
	global $wpdb;
	if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) && get_option('erphp_url_front_noadmin')=='yes') {
	  	$current_user = wp_get_current_user();
	  	if($current_user->roles[0] == get_option('default_role')) {
			$userpage = get_bloginfo('url');
			if(get_option('erphp_url_front_userpage')){
				$userpage = get_option('erphp_url_front_userpage');
			}
			wp_redirect( $userpage );
	  	}
	}
}


add_filter('user_contactmethods','erphpdown_new_contactmethods',10,1); 
function erphpdown_new_contactmethods( $contactmethods ) { 
	if(current_user_can('administrator')){
		$contactmethods['ice_ali_money_ref'] = '推广提成（百分点，填整数，不填则默认erphpdown的设置）'; 
		$contactmethods['ice_ali_money_ref2'] = '三级推广提成（百分点，填整数，不填则默认erphpdown的设置）'; 
		$contactmethods['ice_ali_money_author'] = '作者分成（百分点，填整数，不填则默认erphpdown的设置）'; 
		$contactmethods['ice_ali_money_site'] = '提现手续费（百分点，填整数，不填则默认erphpdown的设置）'; 
	}
	return $contactmethods; 
} 

function erphpdown_admin_notice() {
		$ll = admin_url().'admin.php?page=erphpdown/admin/erphp-settings.php';
    ?>
