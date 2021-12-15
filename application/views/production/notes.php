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
                <h2 class="display-3">QC Notes</h2>
            </center>
        </div>
    </div>
    <div class="container">
        <div>
            <?php
            if (isset($message_display)) {
                echo "<div class='alert alert-success' role='alert'>";
                echo $message_display . '</div>';
            }
            ?>
            <nav class="pagination-nav" aria-label="Checklist navigation">
                <ul class="pagination-nav-menu">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#csv_month_selector"><i class="fas fa-file-alt"></i></button>
                </ul>
                <?php if (isset($links)) {
                    echo $links;
                } ?>
            </nav>
        </div>
        <?php
        if (isset($notes)) { ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col"><i class="fa fa-user"></i></th>
                            <th scope="col">Date</th>
                            <th scope="col">QC</th>
                            <th scope="col" class="mobile-hide">Client</th>
                            <th scope="col">Project</th>
                            <th scope="col">Row</th>
                            <th scope="col">Fault</th>
                            <th scope="col">Action</th>
                            <th scope="col">Checklist</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Trash</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($notes as $note) {
                        echo "<tr id='$note->id'>";
                        echo "<td>" . $note->date . "</td>";
                        echo "<td>" . $users[$note->assembler_id] . "</td>";
                        echo "<td>" . $users[$note->qc_id] . "</td>";
                        echo "<td  class='mobile-hide'>" . $clients[$note->client_id] . "</td>";
                        echo "<td>$note->project</td>";
                        echo "<td>$note->row</td>";
                        echo "<td>$note->fault</td>";
                        echo "<td>$note->action</td>";
                        echo "<td><a class='btn btn-info' href='/production/edit_checklist/$note->checklist_id'><i class='fa fa-list'></td>";
                        echo "<td><a href='/production/edit_note/$note->id' class='btn btn-info'><i class='fa fa-edit'></i></a></td>";
                        echo "<td><button id='$note->id' class='btn btn-danger' onclick='trashNote(this.id)'><i class='fa fa-trash'></i></button></td>";
                        echo "</tr>";
                    }
                }
                    ?>

                    </tbody>
                </table>
            </div>
    </div>
    <div class="modal fade" id="csv_month_selector" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select month</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Month:</label>
                        <select type="text" class="form-control" id="csv-month">
                            <option value="13">All</option>
                            <?php for ($i=1; $i < 13; $i++) { 
                                echo "<option value='$i'>$i</option>";
                            }?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary export_csv" data-bs-dismiss="modal">Send message</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    function trashNote(id) {
        var r = confirm("Trash checklist " + id + "?");
        if (r == true) {
            $.post("/production/trash_qc_note", {
                id: id,
            }).done(function(o) {
                location.reload();
            });
        }
    }
    $('.export_csv').on('click', function() {
        let month = $('#csv-month').val();
        window.location.replace("/production/export_csv/" + month);
    })
</script>