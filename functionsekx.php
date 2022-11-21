<?php
// mouchajiankaixindaima


if ( !defined('ABSPATH') ) {exit;}

add_action('admin_menu', 'mobantu_erphp_menu');
function mobantu_erphp_menu() {
	global $wpdb;
	$tx_count = $wpdb->get_var("SELECT count(ice_id) FROM $wpdb->iceget where ice_success != 1");
		if (function_exists('add_menu_page')) {
			if(current_user_can('administrator')){
				add_menu_page('erphpdown', 'ErphpDown'.($tx_count?'<span class="awaiting-mod">'.$tx_count.'</span>':''), 'activate_plugins', 'erphpdown/admin/erphp-settings.php', '','dashicons-shield');
			}else{
				add_menu_page('erphpdown2', 'ErphpDown', 'read', 'erphpdown/admin/erphp-add-money-online.php', '','dashicons-shield');
			}
		}
		if (function_exists('add_submenu_page')) {
			if(current_user_can('administrator')){
				add_submenu_page('erphpdown/admin/erphp-settings.php', '基础设置','基础设置', 'activate_plugins', 'erphpdown/admin/erphp-settings.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '支付设置', '支付设置', 'activate_plugins', 'erphpdown/admin/erphp-payment.php');
				if(plugin_check_stripe()){
					add_submenu_page('erphpdown/admin/erphp-settings.php', 'Stripe设置', 'Stripe设置', 'activate_plugins', 'erphpdown-addon-stripe/setting.php');
				}
				add_submenu_page('erphpdown/admin/erphp-settings.php', '显示设置', '显示设置', 'activate_plugins', 'erphpdown/admin/erphp-front.php');
				if(plugin_check_video()){
					add_submenu_page('erphpdown/admin/erphp-settings.php', 'VOD设置', 'VOD设置', 'activate_plugins', 'erphpdown-addon-video/settings.php');
				}
				add_submenu_page('erphpdown/admin/erphp-settings.php', 'VIP设置','VIP设置','activate_plugins', 'erphpdown/admin/erphp-vip-setting.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', 'VIP订单','VIP订单','activate_plugins', 'erphpdown/admin/erphp-vip-items.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', 'VIP用户','VIP用户','activate_plugins', 'erphpdown/admin/erphp-vip-users.php');
				if(function_exists('MBT_erphp_vip_cat')){
					add_submenu_page('erphpdown/admin/erphp-settings.php', '分类VIP订单','分类VIP订单','activate_plugins', 'erphpdown/admin/erphp-vip-cat-items.php');
					add_submenu_page('erphpdown/admin/erphp-settings.php', '分类VIP用户','分类VIP用户','activate_plugins', 'erphpdown/admin/erphp-vip-cat-users.php');
				}
				add_submenu_page('erphpdown/admin/erphp-settings.php', '赠送VIP', '赠送VIP', 'activate_plugins', 'erphpdown/admin/erphp-add-vip.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '赠送购买', '赠送购买', 'activate_plugins', 'erphpdown/admin/erphp-add-buy.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '充值扣钱', '充值扣钱', 'activate_plugins', 'erphpdown/admin/erphp-add-money.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '余额明细', '余额明细', 'activate_plugins', 'erphpdown/admin/erphp-moneylog-list.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '充值统计', '<font color=#dba617>充值统计</font>', 'activate_plugins', 'erphpdown/admin/erphp-chong-list.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '购买统计', '<font color=#dba617>购买统计</font>', 'activate_plugins', 'erphpdown/admin/erphp-orders-list.php');
				//add_submenu_page('erphpdown/admin/erphp-settings.php', '附加购买统计', '附加购买统计', 'activate_plugins', 'erphpdown/admin/erphp-indexs-list.php');
				//add_submenu_page('erphpdown/admin/erphp-settings.php', '免登录统计(旧)', '免登录统计(旧)', 'activate_plugins', 'erphpdown/admin/erphp-wppays-list.php');
				if(plugin_check_tuan()){
					add_submenu_page('erphpdown/admin/erphp-settings.php', '拼团统计', '拼团统计', 'activate_plugins', 'erphpdown-addon-tuan/admin/list.php');
				}
				add_submenu_page('erphpdown/admin/erphp-settings.php', 'VIP免费下载查看统计', '下载统计(VIP免费)', 'activate_plugins', 'erphpdown/admin/erphp-vipdown-list.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '普通免费下载查看统计', '下载统计(全站免费)', 'activate_plugins', 'erphpdown/admin/erphp-freedown-list.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '提现统计', '提现统计'.($tx_count?'<span class="awaiting-mod">'.$tx_count.'</span>':''), 'activate_plugins', 'erphpdown/admin/erphp-tixian-list.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '推广统计', '推广统计', 'activate_plugins', 'erphpdown/admin/erphp-reference-all.php');
		        if(plugin_check_card()){
					add_submenu_page('erphpdown/admin/erphp-settings.php', '充值卡','充值卡', 'activate_plugins', 'erphpdown/addon/card/list.php');
					add_submenu_page('erphpdown/admin/erphp-settings.php', '添加充值卡','添加充值卡', 'activate_plugins', 'erphpdown/addon/card/add.php');
				}
				if(plugin_check_vipcard()){
					add_submenu_page('erphpdown/admin/erphp-settings.php', 'VIP充值卡','VIP充值卡', 'activate_plugins', 'erphpdown/addon/vipcard/list.php');
					add_submenu_page('erphpdown/admin/erphp-settings.php', '添加VIP充值卡','添加VIP充值卡', 'activate_plugins', 'erphpdown/addon/vipcard/add.php');
				}
				if(plugin_check_activation()){
					add_submenu_page('erphpdown/admin/erphp-settings.php', '激活码','激活码', 'activate_plugins', 'erphpdown/addon/activation/list.php');
					add_submenu_page('erphpdown/admin/erphp-settings.php', '添加激活码','添加激活码', 'activate_plugins', 'erphpdown/addon/activation/add.php');
				}
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '签到统计', '签到统计', 'activate_plugins', 'erphpdown/admin/erphp-checkin-list.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '销售排行', '销售排行', 'activate_plugins', 'erphpdown/admin/erphp-items-list.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '查询用户', '查询用户', 'activate_plugins', 'erphpdown/admin/erphp-check-users.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '批量处理', '批量处理', 'activate_plugins', 'erphpdown/admin/erphp-shop-list.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '清理数据', '清理数据', 'activate_plugins', 'erphpdown/admin/erphp-clear.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '检查更新', '检查更新', 'activate_plugins', 'erphpdown/admin/update.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '个人资产充值', '个人资产充值', 'read', 'erphpdown/admin/erphp-add-money-online.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '个人申请提现', '个人申请提现', 'read', 'erphpdown/admin/erphp-money.php');
			}else{
				add_submenu_page('erphpdown/admin/erphp-add-money-online.php', '资产充值', '资产充值', 'read', 'erphpdown/admin/erphp-add-money-online.php');
				if(plugin_check_cred()){
					add_submenu_page('erphpdown/admin/erphp-add-money-online.php', '积分兑换','积分兑换', 'read', 'erphpdown/addon/mycred/erphp-to-mycred.php');
				}
				add_submenu_page('erphpdown/admin/erphp-add-money-online.php', '充值记录', '充值记录', 'read', 'erphpdown/admin/erphp-add-money-list.php');
				add_submenu_page('erphpdown/admin/erphp-add-money-online.php', '升级VIP', '升级VIP', 'read', 'erphpdown/admin/erphp-update-vip.php');
				add_submenu_page('erphpdown/admin/erphp-add-money-online.php', 'VIP记录', 'VIP记录', 'read', 'erphpdown/admin/erphp-update-vip-list.php');
				add_submenu_page('erphpdown/admin/erphp-add-money-online.php', '消费清单', '消费清单', 'read', 'erphpdown/admin/erphp-get-items.php');
				add_submenu_page('erphpdown/admin/erphp-add-money-online.php', '销售订单', '销售订单', 'edit_posts', 'erphpdown/admin/erphp-items.php');
				add_submenu_page('erphpdown/admin/erphp-add-money-online.php', '提现列表', '提现列表', 'read', 'erphpdown/admin/erphp-money-list.php');
				add_submenu_page('erphpdown/admin/erphp-add-money-online.php', '申请提现', '申请提现', 'read', 'erphpdown/admin/erphp-money.php');
				add_submenu_page('erphpdown/admin/erphp-add-money-online.php', '推广注册', '推广注册', 'read', 'erphpdown/admin/erphp-reference.php');
				add_submenu_page('erphpdown/admin/erphp-add-money-online.php', '推广下载', '推广下载', 'read', 'erphpdown/admin/erphp-reference-list.php');
				add_submenu_page('erphpdown/admin/erphp-add-money-online.php', '推广VIP', '推广VIP', 'read', 'erphpdown/admin/erphp-reference-vip-list.php');
				add_submenu_page('erphpdown/admin/erphp-add-money-online.php', '免费下载记录', '免费下载记录', 'read', 'erphpdown/admin/erphp-vipdown-list-my.php');
			}
	}
    
}

?>
