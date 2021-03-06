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
            <thead>
            <tr>
                <th><span class="float-left"><?= $messages['viewLogType'] ?></span></th>
                <th><span class="float-left"><?= $messages['viewLogDetails'] ?></span></th>
            </tr>
            </thead>

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
                        <?php if (key_exists('details', $value)) { ?>
                            <button type="button"
                                    class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                                    data-display="stack-<?= $key ?>"
                            >
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        <?php } ?>
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
        'processing': '<?= $messages['datatablesProcessing'] ?>',
        'search': '<?= $messages['datatablesSearch'] ?>',
        'lengthMenu': '<?= $messages['datatablesLengthMenu'] ?>',
        'info': '<?= $messages['datatablesInfo'] ?>',
        'infoEmpty': '<?= $messages['datatablesInfoEmpty'] ?>',
        'infoFiltered': '<?= $messages['datatablesInfoFiltered'] ?>',
        'loadingRecords': '<?= $messages['datatablesLoadingRecords'] ?>',
        'zeroRecords': '<?= $messages['datatablesZeroRecords'] ?>',
        'emptyTable': '<?= $messages['datatablesEmptyTable'] ?>',
        'paginate': {
          'first': '<?= $messages['datatablesFirstPage'] ?>',
          'previous': '<?= $messages['datatablesPreviousPage'] ?>',
          'next': '<?= $messages['datatablesNextPage'] ?>',
          'last': '<?= $messages['datatablesLastPage'] ?>',
        },
      },
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
