###################
SCManagerについて
###################

本PGは、スケジュールに登録した予定をドラッグ＆ドロップ
することで日付変更することが出来る機能をHTML5とjQueryで実現した
サンプルプログラムになります。

言語 PHP 7.2
フレームワーク Codeigniter3
CSRF対策(フレームワークの機能を使用)

■ドラッグ＆ドロップした要素を置くエリアの設定
<td id="drp-area-1" class="droppable" ondragover="DragOver(event)" ondrop="Drop(event)" data-cnt="1">1</td>

idを「drp-area-(日付)」と設定
classを「droppable」と設定
ondragoverを「DragOver(event)」と設定
ondropを「Drop(event)」と設定
data-cntとして日付を設定

■ドラッグ＆ドロップしたい要素の設定
<div class="drag" data-id="1" id="drag-ev-1" draggable="true" ondragstart="DragStart(event)">予定(1)</div>

idを「drag-ev-(予定番号)」と設定
classを「drag」と設定
draggableを「true」に設定
ondragstartを「DragStart(event)」と設定
data-idとして予定番号を設定

予定のドラッグ&ドロップが行われた際に、「Drop」イベントが走り、
ajaxで対象予定IDのデータの日付の更新を行います。

■動作確認用

http://otbis.php.xdomain.jp/SCManager/

====================================================

`CodeIgniter License
Agreement <https://github.com/bcit-ci/CodeIgniter/blob/develop/user_guide_src/source/license.rst>`_.