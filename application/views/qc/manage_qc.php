<?php
$status = ['new', 'on check', 'done'];
$colors = ['success', 'warning', 'info'];
?>
<main role="main">
    <div class="jumbotron">
        <div class="container">
            <center>
                <h2 class="display-3"><?php echo $client . " " . $project; ?> QC Forms</h2>
            </center>
        </div>
    </div>
    <div class="container">
        <?php
        if (isset($message_display)) {
            echo "<div class='alert alert-success' role='alert'>";
            echo $message_display . '</div>';
        }
        ?>
        <nav aria-label="qc navigation">
            <ul class="pagination left">
                <a class="btn btn-warning" href="/qc/add_qc/<?php echo $client . "/" . $project; ?>"><i class="fa fa-file-text"></i></a>
            </ul>
            <?php if (isset($links)) {
                echo $links;
            } ?>
        </nav>
        <?php if (isset($results)) { ?>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="mobile-hide"><i class="fa fa-calendar"></i></th>
                            <th scope="col">QC Number</th>
                            <th scope="col">Project</th>
                            <th scope="col" class="mobile-hide">Serial Number</th>
                            <th scope="col">Part Number</th>
                            <th scope="col" class="mobile-hide"><i class="fa fa-user"></i></th>
                            <th scope="col"><i class="fa fa-picture-o"></i></th>
                            <th scope="col">Edit</th>
                            <th scope="col"><i class="fa fa-check"></i></th>
                            <th scope="col">Trash</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if (is_array($results)) {
                            foreach ($results as $data) { 
                                if (strpos($data->project, 'Trash') !== false) {
                                    continue;
                                } ?>
                                <tr id='<?php echo $data->id ?>'>
                                    <td class="mobile-hide"><?php echo $data->date ?></td>
                                    <td><?php echo $data->number ?></td>
                                    <td><?php echo $data->project ?></td>
                                    <td class="mobile-hide"><?php echo $data->serial ?></td>
                                    <td><?php echo $data->product_num ?></td>
                                    <td class="mobile-hide"><?php echo $data->user ?></td>
                                    <td><?php echo $data->pictures ?></td>
                                    <td><a id='edit_qc' href='/qc/edit_qc/<?php echo $data->id ?>' class='btn btn-info'><i class="fa fa-edit"></i></a></td>
                                    <td><span class="status btn btn-<?= $colors[$data->status] ?>" data-id="<?= $data->id ?>"><?= $status[$data->status] ?></span></td>
                                    <td><button id='<?php echo $data->id ?>' class='btn btn-danger' onclick='trash_qc(this.id,"<?php echo $data->project; ?>","<?php echo $data->number; ?>")'><i class="fa fa-trash"></i></button></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <div>No qc Form(s) found.</div>
        <?php } ?>
    </div>
    <div id='show-log' style='display:none;'>
        <div id="show-log-header">
            <div id="serial-header"></div>Click here to move<button type="button" class="close" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        </div>
        <ul class="list-group list-group-flush">
        </ul>
    </div>
</main>
<script>
    var client = '<?php echo $client ?>';

    function trash_qc(id, project, number) {
        var r = confirm("Trash qc Form " + number + "?");
        if (r == true) {
            $.post("/qc/trash_qc", {
                id: id,
                project: project,
                number: number
            }).done(function(o) {
                //$('[id^=' + id + ']').remove();
                location.reload();
            });
        }
    }
    $('.status').on('click', function() {
        var id = $(this).attr('data-id')
        $.post("/qc/update_status", {
            id: id
        }).done(function(o) {
            //$('[id^=' + id + ']').remove();
            //console.log(o);
            location.reload();
        });
    })
</script>