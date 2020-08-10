<?php
$project =  explode("/", $_SERVER['REQUEST_URI'])[3];
?>
<main role="main">
    <div class="jumbotron">
        <div class="container">
            <center>
                <h2 class="display-3"><?php echo urldecode($project); ?> RMA Froms</h2>
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
        <nav aria-label="Checklist navigation">
            <ul class="pagination left">
                <a class="btn btn-warning" href="/production/add_checklist/<?php echo $project; ?>"><i class="fa fa-file-text"></i></a>
            </ul>
            <?php if (isset($links)) {
                echo $links;
            } ?>
        </nav>
        <?php if (isset($results)) { ?>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="mobile-hide">Date</th>
                        <th scope="col">RMA Number</th>
                        <th scope="col">Project</th>
                        <th scope="col" class="mobile-hide">Serial Number</th>
                        <th scope="col" class="mobile-hide">Created by</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Trash</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($results as $data) { ?>
                        <tr id='<?php echo $data->id ?>'>
                            <td class="mobile-hide"><?php echo $data->date ?></td>
                            <td class="mobile-hide"><?php echo $data->number ?></td>
                            <td class="mobile-hide"><?php echo $data->project ?></td>
                            <td class="mobile-hide"><?php echo $data->serial ?></td>
                            <td><?php echo $data->assembler ?></td>
                            <td><a id='edit_checklist' target="_blank" href='/production/edit_checklist/<?php echo $data->id ?>?sn=<?php echo $data->serial ?>' class='btn btn-info'><i class="fa fa-edit"></i></a></td>
                            <td><button id='<?php echo $data->id ?>' class='btn btn-danger' onclick='trashChecklist(this.id,"<?php echo urldecode($project); ?>","<?php echo $data->serial; ?>")'><i class="fa fa-trash"></i></button></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div>No RMA Form(s) found.</div>
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
    var client = '<?php echo $client[0]['name'] ?>';

    function trashRMA(id, project, serial) {
        var r = confirm("Trash RMA Form " + serial + "?");
        if (r == true) {
            $.post("/production/trashRMA", {
                id: id,
                project: project,
                serial: serial
            }).done(function(o) {
                //$('[id^=' + id + ']').remove();
                location.reload();
            });
        }
    }
</script>