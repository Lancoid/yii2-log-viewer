<?php

/* @var \yii\web\View $this */

/* @var \lancoid\yii2LogViewer\Module $module */
$module = $this->context->module;
$messages = $module->messages;

$this->title = $messages['logsTitle'];
$this->params['breadcrumbs'][] = $this->title;

\lancoid\yii2LogViewer\assets\LogViewerAsset::register($this);
?>

<div class="log-viewer-view">
    <div class="col-md-10 table-container">
        <table id="table-log" class="table table-striped">
            <thead>
            <tr>
                <th><span class="float-left">Уровень</span></th>
                <th><span class="float-left">Контент</span></th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($logs as $key => $log) { ?>
                <tr data-display="stack-<?= $key ?>">
                    <td class="nowrap text-right">
                        <i class="fa fa-<?= $log['error-type'] ?> faa-flash animated faa-slow text-<?= $log['error-type'] ?>" aria-hidden="true"></i>
                        <span class="text-<?= $log['error-type'] ?>"><?= $log['error-type'] ?></span>
                        <br>
                        <i class="fa fa-globe" aria-hidden="true"></i>
                        <?= $log['ip'] ?>
                    </td>
                    <td class="text">
                        <span class="float-left"><?= $log['date'] ?></span>
                        <button type="button"
                                class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                                data-display="stack-<?= $key ?>"
                        >
                            <span class="fa fa-search"></span>
                        </button>
                        <pre class="pre-codded-title"><?= $log['error'] ?></pre>
                        <?php if (key_exists('details', $log)) { ?>
                            <div class="stack" id="stack-<?= $key ?>">
                                <?php foreach ($log['details'] as $k => $line) {
                                    echo $line . '<br>';
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
    $('.table-container tr').on('click', function() {
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
