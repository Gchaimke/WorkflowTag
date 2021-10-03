<?php
$status = ['receive', 'verification', 'on check', 'done'];
$colors = ['info', 'warning', 'danger', 'success'];
$type = isset($_GET['type']) ? $_GET['type'] : 'rma';
$client =  isset($_GET['client']) ? $_GET['client'] : 0;
$project =  isset($_GET['project']) ? $_GET['project'] : 'All';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '-1';
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
                <a class="btn btn-warning" href="<?= "/forms/add?type=$type&client=$client&project=$project" ?>"><i class="fas fa-file-alt"></i></a>
            </ul>
            <?php if (isset($links)) {
                echo $links;
            } ?>

        </nav>
        <div class="input-group mb-2">
            <span class="input-group-text">Status Filter:</span>
            <select class="form-select status-filter">
                <option value="-1" <?= $status_filter == -1 ? 'selected' : '' ?> class="font-weight-bolder">All</option>
                <?php
                foreach ($status as $key =>  $value) {
                    $selected =  $status_filter == $key ? 'selected' : '';
                    echo "<option value='$key' class='text-" . $colors[$key] . " font-weight-bold' $selected>$value</option>";
                }
                ?>
            </select>
        </div>
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
                            <th scope="col"><i class="fas fa-image"></i></th>
                            <th scope="col">Edit</th>
                            <th scope="col"><i class="fa fa-check"></i></th>
                            <th scope="col">Trash</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (is_array($forms)) {
                            foreach ($forms as $data) {
                                if ($status_filter != '-1' && $data->status != $status_filter) {
                                    continue;
                                }
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
                                    <td><a href='<?= "/forms/edit?type=$type&client=$client&project=$project&id=$data->id" ?>' class='btn btn-info'><i class="fa fa-edit"></i></a></td>
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
            $.post("/forms/trash", {
                id: id,
                project: project,
                number: number,
                type: '<?= $type ?>'
            }).done(function(o) {
                location.reload();
            });
        }
    }

    $('.status').on('click', function() {
        var id = $(this).attr('data-id')
        $.post("/forms/update_status", {
            id: id,
            type: '<?= $type ?>'
        }).done(function(o) {
            location.reload();
        });
    });

    $('.status-filter').on('change', function() {
        var url = updateQueryStringParameter(document.location.href, "status", $(this).val());
        document.location = url;
    })

    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            return uri + separator + key + "=" + value;
        }
    }
</script>