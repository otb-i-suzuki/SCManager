<!DOCTYPE html>
<html lang="jp">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- jQuery -->
	<script src="/SCManager/js/jquery.min.js"></script>
	<script src="/SCManager/js/jquery.cookie.js"></script>

	<!-- Bootstrap -->
	<link href="/SCManager/bootstrap/css/bootstrap.css" rel="stylesheet">
	<script src="/SCManager/bootstrap/js/bootstrap.min.js"></script>

	<!-- custom -->
	<link href="/SCManager/css/schedule.css" rel="stylesheet">
	<script src="/SCManager/js/schedule.js"></script>

	<title>SCManager</title>
</head>

<body>
	<div class="container">

		<div class="modal fade" data-backdrop="static" data-keyboard="false" id="createModal" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title"><label>予定追加</label></h4>
					</div>
					<div class="modal-body">
						<div>
							<label>日付</label>
							<select id="select_date" class="form-control" name="date">
							<?php
								for($date_cnt=1; $date_cnt<=$em; $date_cnt++)
								{
									echo "<option value='".$date_cnt."'>".$date_cnt."</option>";
								}
							?>
							</select><br>
							<label>タイトル(必須)</label>
							<input id="title" type="text" class="form-control" name="title" maxlength="20"><br>
							<label>詳細</label>
							<textarea id="detail" class="form-control" rows="3" name="detail" id="modal-detail"></textarea><br>
						</div>
					</div>
					<div class="modal-footer" style="text-align:center;">
						<button id="add_btn" type="button" class="btn btn-primary" data-dismiss="modal">追加</button>
						<button type="button" class="btn btn-normal" data-dismiss="modal">閉じる</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" data-backdrop="static" data-keyboard="false" id="editModal" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title"><label>予定変更</label></h4>
					</div>
					<div class="modal-body">
						<div>
							<label>タイトル(必須)</label>
							<input id="upd_title" type="text" class="form-control" name="title" maxlength="20"><br>
							<label>詳細</label>
							<textarea id="upd_detail" class="form-control" rows="3" name="detail" id="modal-detail"></textarea><br>
							<input type="hidden" id="edit_id">
						</div>
					</div>
					<div class="modal-footer" style="text-align:center;">
						<button id="upd_btn" type="button" class="btn btn-primary" data-dismiss="modal">変更</button>
						<button id="del_btn" type="button" class="btn btn-danger" data-dismiss="modal">削除</button>
						<button type="button" class="btn btn-normal" data-dismiss="modal">閉じる</button>
					</div>
				</div>
			</div>
		</div>

		<div class="row" style="text-align:center;">
			<h2><a href="/SCManager/Schedule">SCManager</a></h2>
		</div>,
		<div class="row" style="text-align:center; margin-bottom:20px;">
			<button id="new_plan_btn" type="button" class="btn btn-primary">予定追加</button>
		</div>
		<div class="row" style="text-align:center;">
			<a href="/SCManager/Schedule?ym=<?php echo $back_ym; ?>">◀</a>&nbsp;
			<?php echo $target_date->format('Y年m月'); ?>
			<input type="hidden" id="target_ym" value="<?php echo $target_date->format('Ym'); ?>">
			&nbsp;<a href="/SCManager/Schedule?ym=<?php echo $next_ym; ?>">▶</a>
		</div>

		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">

						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th width="14%">Sun</th>
									<th width="14%">Mon</th>
									<th width="14%">Tue</th>
									<th width="14%">Wed</th>
									<th width="14%">Thu</th>
									<th width="14%">Fri</th>
									<th width="14%">Sat</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php
										$now_date = new DateTime();
										for($date_cnt = 1; $date_cnt <= $em; $date_cnt++)
										{
											if($date_cnt == 1)
											{
												// 曜日を取得
												$w_date = $target_date->format('w');

												if($w_date != 0)
												{
													// 1日が日曜日以外の場合は、ブロック補完
													$cnt = 0;
													while($cnt != $w_date)
													{
														echo "<td></td>";
														$cnt++;
													}
												}
											}

											// ブロック生成対象の日付をDateTimeオブジェクト化
											if($date_cnt < 10)
											{
												// 1桁の場合の調整
												$day = new DateTime($target_ym. '0'. $date_cnt);
											}
											else
										   {
												$day = new DateTime($target_ym. $date_cnt);
											}

											// 曜日を取得
											$w_date = $day->format('w');

											// ブロック生成
											echo '<td id="drp-area-'.$date_cnt.'" class="droppable';
											if($day->format('Ymd') == $now_date->format('Ymd'))
											{
												echo " n-date-dp";
											}
											echo '" ondragover="DragOver(event)" ondrop="Drop(event)" data-cnt="'. $date_cnt. '">';

											echo $date_cnt;

											// DBに登録されている予定を出力
											foreach($plans_data[$date_cnt] as $plan)
											{
												echo '<div class="drag" data-id="';
												echo $plan->id;
												echo '" id="drag-ev-';
												echo $plan->id;
												echo '" draggable="true" ondragstart="DragStart(event)">';
												echo $plan->title;
												echo '</div>';
											}

											echo "</td>";

											// 土曜日の場合は、次の行へ
											if($w_date == 6)
											{
												echo "</tr>";
												echo "<tr>";
											}
										}
										// ブロック補完
										if($w_date != 6)
										{
											while($w_date < 6)
											{
												echo "<td></td>";
												$w_date++;
											}
										}
								?>
								</tr>
							</tbody>
						</table>

				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body" style="text-align:center;">
					予定をドラッグ&ドロップで移動することができます。
				</div>
			</div>
		</div>
	</div>
</body>