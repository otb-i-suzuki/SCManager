<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * メイン画面
 * カレンダーと予定を表示する
 *
 * @author Suzuki
 *
 */
class Schedule extends CI_Controller {

	/**
	 * *************************************
	 * 画面表示
	 * *************************************
	 */
	public function index()
	{
		if(empty($this->input->get('ym'))
			|| !ctype_digit($this->input->get('ym'))
			|| strlen($this->input->get('ym')) != 6
			|| substr($this->input->get('ym'), 4) > 12
			|| $this->input->get('ym') < 0)
		{
			// GETパラメタチェック
			// 空、数値でない、月が不正、6桁でない、マイナス設定の場合は
			// 今月を対象月として設定
			$this->session->set_userdata('target_ym', date('Ym'));
		}
		else
	   {
		   // 引数で設定された年月を設定
		   $this->session->set_userdata('target_ym', $this->input->get('ym'));
		}

		// viewに渡すデータセット用
		$option = array();

		// 対象月をセット
		$option['target_ym'] = $this->session->userdata('target_ym');

		// 対象月をDateTimeオブジェクト化
		$target_date = new DateTime($option['target_ym']. '01');
		$option['target_date'] = clone $target_date;

		// 月末の日付を取得
		$option['em'] = $target_date->format('t');
		// 前月をセット
		$option['back_ym'] = $target_date->modify("-1 month")->format('Ym');
		// 翌月をセット
		$option['next_ym'] = $target_date->modify("+2 month")->format('Ym');

		// 対象月の予定データを取得
		$this->load->model('Model_m_plans');
		$option['plans_data'] = $this->Model_m_plans->get_plans_list();

		$this->load->view('view_schedule', $option);
	}

	/**
	 * *************************************
	 * 予定情報取得(ajax)
	 * *************************************
	 */
	public function get_plan_data()
	{
		$result_ary = array(
			'result' => -1
		);

		// POSTデータチェック
		if($this->input->post('ym') == null
			|| $this->input->post('id') == null)
		{
			echo json_encode($result_ary);
			exit();
		}

		// 予定情報取得
		$this->load->model('Model_m_plans');
		$result_ary = $this->Model_m_plans->get_plan_data();

		echo json_encode($result_ary);
	}

	/**
	 * *************************************
	 * 予定登録(ajax)
	 * *************************************
	 */
	public function insert_data()
	{
		$result_ary = array(
			'result' => -1
		);

		// POSTデータチェック
		if($this->input->post('ym') == null
			|| $this->input->post('date') == null
			|| $this->input->post('title') == null
			|| empty($this->input->post('title')))
		{
			echo json_encode($result_ary);
			exit();
		}

		// INSERT処理実行
		$this->load->model('Model_m_plans');
		$id = $this->Model_m_plans->insert_plans();

		if($id == -1)
		{
			// INSERT処理に失敗
			$result_ary['result'] = -2;
			echo json_encode($result_ary);
			exit();
		}

		// 結果セット
		$result_ary['id'] = $id;
		$result_ary['title'] = htmlspecialchars($this->input->post('title'));
		$result_ary['result'] = 0;

		echo json_encode($result_ary);
	}

	/**
	 * *************************************
	 * 予定更新(日付)(ajax)
	 * *************************************
	 */
	public function change_date()
	{
		$result_ary = array(
			'result' => -1
		);

		// POSTデータチェック
		if($this->input->post('ym') == null
			|| $this->input->post('id') == null
			|| $this->input->post('date') == null)
		{
			echo json_encode($result_ary);
			exit();
		}

		// 予定更新(日付)
		$this->load->model('Model_m_plans');
		if($this->Model_m_plans->change_plans_date())
		{
			$result_ary['result'] = 0;
		}

		echo json_encode($result_ary);
	}

	/**
	 * *************************************
	 * 予定更新(タイトル、詳細)(ajax)
	 * *************************************
	 */
	public function update_data()
	{
		$result_ary = array(
			'result' => -1
		);

		// POSTデータチェック
		if($this->input->post('ym') == null
			|| $this->input->post('id') == null
			|| $this->input->post('title') == null
			|| empty($this->input->post('title')))
		{
			echo json_encode($result_ary);
			exit();
		}

		// 予定更新(タイトル、詳細)
		$this->load->model('Model_m_plans');
		if($this->Model_m_plans->update_plans())
		{
			$result_ary['result'] = 0;
		}

		echo json_encode($result_ary);
	}

	/**
	 * *************************************
	 * 予定削除(ajax)
	 * *************************************
	 */
	public function delete_data()
	{
		$result_ary = array(
			'result' => -1
		);

		// POSTデータチェック
		if($this->input->post('ym') == null
			|| $this->input->post('id') == null)
		{
			echo json_encode($result_ary);
			exit();
		}

		// 予定削除
		$this->load->model('Model_m_plans');
		if($this->Model_m_plans->delete_plans())
		{
			$result_ary['result'] = 0;
		}

		echo json_encode($result_ary);
	}
}
