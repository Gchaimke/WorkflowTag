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
        <?php
        if (isset($message_display)) {
            echo "<div class='alert alert-success' role='alert'>";
            echo $message_display . '</div>';
        }
        if (isset($notes)) { ?>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col"><i class="fa fa-user"></i></th>
                            <th scope="col">QC</th>
                            <th scope="col" class="mobile-hide">Client</th>
                            <th scope="col">Project</th>
                            <th scope="col">Row</th>
                            <th scope="col">Checklist</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Trash</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($notes as $note) {
                        echo "<tr id='$note->id'>";
                        echo "<td>" . $users[$note->assembler_id] . "</td>";
                        echo "<td>" . $users[$note->qc_id] . "</td>";
                        echo "<td  class='mobile-hide'>" . $clients[$note->client_id] . "</td>";
                        echo "<td>$note->project</td>";
                        echo "<td>$note->row</td>";
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
</script>