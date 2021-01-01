<?php
/*
+--------------------------------------------------------------------------
|   WeCenter [#RELEASE_VERSION#]
|   ========================================
|   by WeCenter Software
|   © 2011 - 2014 WeCenter. All Rights Reserved
|   http://www.wecenter.com
|   ========================================
|   Support: WeCenter@qq.com
|
+---------------------------------------------------------------------------
*/

if (!defined('IN_ANWSION'))
{
	die;
}

class main extends AWS_CONTROLLER
{
	public function get_access_rule()
	{
		$rule_action['rule_type'] = 'black';

		return $rule_action;
	}

	public function setup()
	{
		HTTP::no_cache_header();
	}

	public function index_action()
	{
		if ($this->user_id)
		{
			HTTP::redirect('/');
		}

		$this->crumb(AWS_APP::lang()->_t('登录'));

		TPL::import_css('css/register.css');

		if ($_GET['url'])
		{
			$return_url = '/'; //htmlspecialchars(base64_decode($_GET['url']));
		}
		else
		{
			$return_url = '/'; //htmlspecialchars($_SERVER['HTTP_REFERER']);
		}

		TPL::assign('captcha_required', $this->model('login')->is_captcha_required());
		TPL::assign('return_url', $return_url);

		TPL::output("account/login");
	}

}