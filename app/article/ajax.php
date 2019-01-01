<?php
/*
+--------------------------------------------------------------------------
|   WeCenter [#RELEASE_VERSION#]
|   ========================================
|   by Tatfook Network Team
|   © 2011 - 2014 WeCenter. All Rights Reserved
|   http://www.wecenter.com
|   ========================================
|   Support: WeCenter@qq.com
|
+---------------------------------------------------------------------------
*/

define('IN_AJAX', TRUE);

if (!defined('IN_ANWSION'))
{
	die;
}

class ajax extends AWS_CONTROLLER
{
	public function get_access_rule()
	{
		$rule_action['rule_type'] = 'white';

		$rule_action['actions'] = array(
			'list'
		);

		return $rule_action;
	}

	public function setup()
	{
		HTTP::no_cache_header();
	}


	public function remove_article_action()
	{
		if (!$this->user_info['permission']['is_administrator'])
		{
			H::ajax_json_output(AWS_APP::RSM(null, '-1', AWS_APP::lang()->_t('对不起, 你没有删除文章的权限')));
		}

		if ($article_info = $this->model('article')->get_article_info_by_id($_POST['article_id']))
		{
			if ($this->user_id != $article_info['uid'])
			{
				$this->model('account')->send_delete_message($article_info['uid'], $article_info['title'], $article_info['message']);
			}

			$this->model('article')->remove_article($article_info['id']);
		}

		H::ajax_json_output(AWS_APP::RSM(array(
			'url' => get_js_url('/')
		), 1, null));
	}

	public function remove_comment_action()
	{
		$comment_info = $this->model('article')->get_comment_by_id($_POST['comment_id']);
		if (!$comment_info || !$comment_info['message'])
		{
			H::ajax_json_output(AWS_APP::RSM(null, '-1', AWS_APP::lang()->_t('评论不存在')));
		}
		
		if ($this->user_id != $comment_info['uid'] AND!$this->user_info['permission']['edit_article'])
		{
			H::ajax_json_output(AWS_APP::RSM(null, '-1', AWS_APP::lang()->_t('对不起, 你没有删除评论的权限')));
		}

		// 只清空不删除
		// TODO: implement update_comment_action()
		$this->model('article')->remove_article_comment($comment_info, $this->user_id);

		H::ajax_json_output(AWS_APP::RSM(array(
			'url' => get_js_url('/article/' . $comment_info['article_id'])
		), 1, null));
	}

}