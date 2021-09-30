<?php
if (isset($this->session->userdata['logged_in'])) {
    if ($this->session->userdata['logged_in']['role'] == "Assembler") {
        header("location: /");
    }
}
?>
<main role="main">
    <div class="jumbotron">
        <div class="container">
            <center>
                <h2 class="display-3">Note <?= $note->id ?></h2>
            </center>
        </div>
    </div>
    <div class="container">
        <div id="form-messages" class='alert hidden' role='alert'></div>

        <?php
        if (isset($message_display)) {
            echo "<div class='alert alert-success' role='alert'>";
            echo $message_display . '</div>';
        }
        if (isset($note)) { ?>
            SN: <?= $note->checklist_sn ?>
            <?php echo form_open('production/edit_qc_note/', 'id=ajax-form'); ?>
            <input type="hidden" name="id" value="<?= $note->id ?>" />
            <input type="hidden" name="checklist_id" value="<?= $note->checklist_id ?>" />
            <input type="hidden" name="checklist_sn" value="<?= $note->checklist_sn ?>" />
            <input type="hidden" name="qc_id" value="<?= $note->qc_id ?>" />
            <input type="hidden" name="client_id" value="<?= $note->client_id ?>" />
            <input type="hidden" name="project" value="<?= $note->project ?>" />
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <select class='form-control' name="assembler_id">
                        <option value='0'>Select</option>
                        <?php foreach ($users as $id => $name) {
                            if ($id == $note->assembler_id) {
                                $selected = 'selected';
                            } else {
                                $selected = '';
                            }
                            echo "<option value=" . $id . " $selected>" . $name . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="text" name="row" placeholder="checklist row" class="form-control col-md-6 mb-2" value="<?= $note->row ?>" />
                <textarea name="note" placeholder="note" class="form-control col-md-12"><?= $note->note ?></textarea>
            </div>
            <button type='submit' class="btn btn-success" value="Save"><i class="fa fa-save me-1"></i>Save</button>
            <a href="/production/notes" class="btn btn-danger text-white" value="Close"><i class="fa fa-close me-1"></i>Close</a>
            <?php echo form_close() ?>
        <?php } ?>
    </div>
</main>