<?php
$status = ['new', 'on check', 'done'];
$colors = ['success', 'warning', 'info'];
$type = isset($_GET['type']) ? $_GET['type'] : 'rma';
$client =  isset($_GET['client']) ? $_GET['client'] : 'avdor';
$project =  isset($_GET['project']) ? $_GET['project'] : 'All';

?>
<main role="main">
    <div class="jumbotron">
        <div class="container">
            <center>
                <h2 class="display-3"><?php echo $project . " " . ucwords($type)  ?> Forms</h2>
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
                <a class="btn btn-warning" href="<?= "/forms/add?type=" . $type . "&client=" . $client . "&project=" . $project; ?>"><i class="fa fa-file-text"></i></a>
            </ul>
            <?php if (isset($links)) {
                echo $links;
            } ?>
        </nav>
        <?php if (isset($forms)) { ?>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="mobile-hide"><i class="fa fa-calendar"></i></th>
                            <th scope="col">Form Number</th>
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
                        if (is_array($forms)) {
                            foreach ($forms as $data) {
                                if (strpos($data->project, 'Trash') !== false) {
                                    continue;
                                }
                                if ($project != "All" && $data->project != $project) {
                                    continue;
                                }
                        ?>
                                <tr id='<?php echo $data->id ?>'>
                                    <td class="mobile-hide"><?php echo $data->date ?></td>
                                    <td><?php echo $data->number ?></td>
                                    <td><?php echo $data->project ?></td>
                                    <td class="mobile-hide"><?php echo $data->serial ?></td>
                                    <td><?php echo $data->product_num ?></td>
                                    <td class="mobile-hide"><?php echo $data->user ?></td>
                                    <td><?php echo $data->pictures ?></td>
                                    <td><a href='<?= "/forms/edit?type=" . $type . "&id=" . $data->id ?>' class='btn btn-info'><i class="fa fa-edit"></i></a></td>
                                    <td><span class="status btn btn-<?= $colors[$data->status] ?>" data-id="<?= $data->id ?>"><?= $status[$data->status] ?></span></td>
                                    <td><button id='<?php echo $data->id ?>' class='btn btn-danger' onclick='trash(this.id,"<?php echo $data->project; ?>","<?php echo $data->number; ?>")'><i class="fa fa-trash"></i></button></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
    </div>
    <div><?= $type ?> forms not found.</div>
<?php } ?>
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

    function trash(id, project, number) {
        var r = confirm("Trash Form " + number + "?");
        if (r == true) {
            $.post("/<?= $type ?>/trash_<?= $type ?>", {
                id: id,
                project: project,
                number: number
            }).done(function(o) {
                location.reload();
            });
        }
    }
    $('.status').on('click', function() {
        var id = $(this).attr('data-id')
        $.post("/<?= $type ?>/update_status", {
            id: id
        }).done(function(o) {
            location.reload();
        });
    })
</script>