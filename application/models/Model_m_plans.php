<?php
/**
 * 予定情報(m_plans) モデル
 *
 * @author Suzuki
 *
 */
class Model_m_plans extends CI_Model {

	/**
	 * *************************************
	 * 対象年月の予定一覧の取得
	 *
	 * @return array 予定一覧
	 * *************************************
	 */
	public function get_plans_list()
	{
		// 日付分、配列を作る
		$result_ary = array();
		for($i = 1; $i <= 31; $i++)
		{
			$result_ary[$i] = array();
		}

		$this->db->select('id, date, title, detail');

		// 対象年月のデータのみ取得
		$this->db->where('ym', $this->session->userdata('target_ym'));

		$query = $this->db->get('m_plans');

		// 対象日付の配列にデータを挿入する
		foreach($query->result() as $row)
		{
			array_push($result_ary[$row->date], $row);
		}

		return $result_ary;
	}

	/**
	 * *************************************
	 * 対象年月の対象IDの予定情報の取得
	 *
	 * @return array 予定一覧
	 * *************************************
	 */
	public function get_plan_data()
	{
		$result_data = array(
			'result' => -1
		);

		// POST情報取得
		$ym = $this->input->post('ym');
		$id = $this->input->post('id');

		$this->db->select('title, detail');

		// 対象年月の対象IDのデータを取得
		$this->db->where('ym', $ym);
		$this->db->where('id', $id);

		$query = $this->db->get('m_plans');

		if($query->num_rows() > 0)
		{
			// 予定データが存在した場合は、セット
			$result_data['result'] = 0;
			$result_data['title'] = htmlspecialchars_decode($query->row()->title, ENT_QUOTES);
			$result_data['detail'] = htmlspecialchars_decode($query->row()->detail, ENT_QUOTES);
		}

		return $result_data;
	}

	/**
	 * *************************************
	 * 予定登録
	 *
	 * @return int 登録したデータのID(失敗時は「-1」)
	 * *************************************
	 */
	public function insert_plans()
	{
		// POST情報取得
		$ym = $this->input->post('ym');
		$date = $this->input->post('date');
		$title = $this->input->post('title');
		$detail = $this->input->post('detail');

		// 割り振るIDを設定
		$this->db->select('max(id) + 1 as new_id');
		$this->db->where('ym', $ym);

		$query = $this->db->get('m_plans');

		$new_id = 1;
		if($query->num_rows() > 0 && $query->row()->new_id != null)
		{
			$new_id = $query->row()->new_id;
		}

		$now_date = date('Y-m-d H:i:s');

		// 挿入するデータ設定
		$data = array(
			'ym' => $ym,
			'id' => $new_id,
			'date' => $date,
			'title' => htmlspecialchars($title, ENT_QUOTES),
			'detail' => htmlspecialchars($detail, ENT_QUOTES),
			'create_date' => $now_date,
			'update_date' => $now_date
		);

		// INSERT実行
		if(!$this->db->insert('m_plans', $data))
		{
			// 失敗した場合は、-1を返却
			return -1;
		}

		// 正常終了した場合は、挿入したデータのIDを返却
		return $new_id;
	}

	/**
	 * *************************************
	 * 予定更新(日付)
	 *
	 * @return boolean 更新結果
	 * *************************************
	 */
	public function change_plans_date()
	{
		// POST情報取得
		$ym = $this->input->post('ym');
		$id = $this->input->post('id');
		$date = $this->input->post('date');

		// 更新するデータ設定
		$data = array(
			'date' => $date,
			'update_date' => date('Y-m-d H:i:s')
		);

		// 更新するデータ条件設定
		$this->db->where('ym', $ym);
		$this->db->where('id', $id);

		// UPDATE実行
		if(!$this->db->update('m_plans', $data))
		{
			// 失敗した場合は、falseを返却
			return false;
		}

		// 正常終了した場合は、trueを返却
		return true;
	}

	/**
	 * *************************************
	 * 予定更新(タイトル、詳細)
	 *
	 * @return boolean 更新結果
	 * *************************************
	 */
	public function update_plans()
	{
		// POST情報取得
		$ym = $this->input->post('ym');
		$id = $this->input->post('id');
		$title = $this->input->post('title');
		$detail = $this->input->post('detail');

		// 更新するデータ設定
		$data = array(
			'title' => htmlspecialchars($title, ENT_QUOTES),
			'detail' => htmlspecialchars($detail, ENT_QUOTES),
			'update_date' => date('Y-m-d H:i:s')
		);

		// 更新するデータ条件設定
		$this->db->where('ym', $ym);
		$this->db->where('id', $id);

		// UPDATE実行
		if(!$this->db->update('m_plans', $data))
		{
			// 失敗した場合は、falseを返却
			return false;
		}

		// 正常終了した場合は、trueを返却
		return true;
	}

	/**
	 * *************************************
	 * 予定削除
	 *
	 * @return boolean 削除結果
	 * *************************************
	 */
	public function delete_plans()
	{
		// POST情報取得
		$ym = $this->input->post('ym');
		$id = $this->input->post('id');

		// 削除データの条件セット
		$this->db->where('ym', $ym);
		$this->db->where('id', $id);

		// DELETE実行
		if(!$this->db->delete('m_plans'))
		{
			// 失敗した場合は、falseを返却
			return false;
		}

		// 成功した場合は、trueを返却
		return true;
	}
}