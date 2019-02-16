<?php

/* @var \yii\web\View $this */
/* @var array $log */

/* @var \lancoid\yii2LogViewer\Module $module */
$module = $this->context->module;
$messages = $module->messages;

$this->title = $messages['logsTitle'];
$this->params['breadcrumbs'][] = $this->title;

\lancoid\yii2LogViewer\assets\LogViewerAsset::register($this);
?>

<div class="log-viewer-view">
    <div class="col-md-12 table-container">
        <table id="table-log" class="table table-striped">
            <tbody>
            <?php foreach ($log as $key => $value) { ?>
                <tr>
                    <td class="text-right">
                        <span class="glyphicon glyphicon-<?= $value['error-icon'] ?> <?= $value['error-color'] ?>"></span>
                        <span class="<?= $value['error-color'] ?>"><?= $value['error-type'] ?></span>
                        <br>
                        <span><?= $value['ip'] ?></span>
                    </td>
                    <td>
                        <span class="float-left"><?= $value['date'] ?></span>
                        <button type="button"
                                class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                                data-display="stack-<?= $key ?>"
                        >
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                        <br>
                        <?= $value['error'] ?>
                        <?php if (key_exists('details', $value)) { ?>
                            <div id="stack-<?= $key ?>" style="display: none;">
                                <?php foreach ($value['details'] as $k => $line) {
                                    echo '<span style="white-space: pre;">' . $line . '</span><br>';
                                } ?>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
  $(document).ready(function() {
    $('.table-container button').on('click', function() {
      $('#' + $(this).data('display')).toggle();
    });
    $('#table-log').DataTable({
      'language': {
        'processing': 'Подождите...',
        'lengthMenu': 'Показать _MENU_ записей',
        'info': 'Записи с _START_ до _END_ из _TOTAL_ записей',
        'infoEmpty': 'Записи с 0 до 0 из 0 записей',
        'loadingRecords': 'Загрузка записей...',
        'zeroRecords': 'Записи отсутствуют.',
        'emptyTable': 'В таблице отсутствуют данные',
        'paginate': {
          'first': 'Первая',
          'previous': 'Предыдущая',
          'next': 'Следующая',
          'last': 'Последняя',
        },
      },
      'searching': false,
      'ordering': false,
      'stateSave': true,
      'stateSaveCallback': function(settings, data) {
        window.localStorage.setItem('datatable', JSON.stringify(data));
      },
      'stateLoadCallback': function(settings) {
        var data = JSON.parse(window.localStorage.getItem('datatable'));
        if (data) { data.start = 0; }
        return data;
      },
    });
  });
</script>
