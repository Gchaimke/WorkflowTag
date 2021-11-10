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
            <div class="row mb-3">
                <div class="col">
                    <div class="form-floating">
                        <select class='form-select' name="assembler_id">
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
                        <label for="assembler_id" class="m-1">Assembler</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="text" name="row" placeholder="checklist row" class="form-control col mb-2" value="<?= $note->row ?>" />
                        <label for="row" class="m-1">Row</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select class='form-select' name="fault">
                            <option value='0'>Select</option>
                            <?php
                            $faults = array("Cables Routing", "Connector Connection", "Screws", "Assembly", "Labels & Documentetion", "Scratches & Stains");
                            foreach ($faults as $fault) {
                                if ($fault == $note->fault) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                echo "<option value='$fault' $selected>$fault</option>";
                            }
                            ?>
                        </select>
                        <label for="row" class="m-1">Fault Description</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select class='form-select' name="action">
                            <option value='0'>Select</option>
                            <?php
                            $actions = array("Repaired", "Replaced", "Returned");
                            foreach ($actions as $action) {
                                if ($action == $note->action) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                echo "<option value='$action' $selected>$action</option>";
                            }
                            ?>
                        </select>
                        <label for="row" class="m-1">Action</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <textarea name="note" placeholder="note" class="form-control" style="height: 150px;"><?= $note->note ?></textarea>
                        <label for="note" class="m-1">Note</label>
                    </div>
                </div>
            </div>
            <button type='submit' class="btn btn-success" value="Save"><i class="fa fa-save me-1"></i>Save</button>
            <a href="/production/notes" class="btn btn-danger text-white" value="Close"><i class="fa fa-close me-1"></i>Close</a>
            <?php echo form_close() ?>
    </div>
<?php } ?>
</div>
</main>