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

        if(isset($client[0]['name'])){
            $current_client = $client[0]['name'];
        }else{
            $current_client ='Avdor';
        }
        ?>
        <nav aria-label="rma navigation">
            <ul class="pagination left">
                <a class="btn btn-warning" href="/rma/add_rma/<?php echo $project; ?>"><i class="fa fa-file-text"></i></a>
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
                            <td><?php echo $data->number ?></td>
                            <td><?php echo $data->project ?></td>
                            <td class="mobile-hide"><?php echo $data->serial ?></td>
                            <td class="mobile-hide"><?php echo $data->assembler ?></td>
                            <td><a id='edit_rma' href='/rma/edit_rma/<?php echo $data->id ?>' class='btn btn-info'><i class="fa fa-edit"></i></a></td>
                            <td><button id='<?php echo $data->id ?>' class='btn btn-danger' onclick='trash_rma(this.id,"<?php echo $data->project; ?>","<?php echo $data->number; ?>")'><i class="fa fa-trash"></i></button></td>
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
    var client = '<?php echo $current_client?>';

    function trash_rma(id, project, number) {
        var r = confirm("Trash RMA Form " + number + "?");
        if (r == true) {
            $.post("/rma/trash_rma", {
                id: id,
                project: project,
                number: number
            }).done(function(o) {
                //$('[id^=' + id + ']').remove();
                location.reload();
            });
        }
    }
</script>