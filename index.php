<?php

// Include the database configuration file

require_once 'database.php';

// Handle status messages

if (!empty($_GET['status'])) {

    switch ($_GET['status']) {

        case 'succ':

            $statusType = 'alert-success';

            $statusMsg = 'Members data imported successfully.';

            break;

        case 'err':

            $statusType = 'alert-danger';

            $statusMsg = 'An error occurred. Please try again.';

            break;

        case 'invalid_file':

            $statusType = 'alert-danger';

            $statusMsg = 'Please upload a valid CSV file.';

            break;

        default:

            $statusType = '';

            $statusMsg = '';
    }
}
?>
<!DOCTYPE html>

<html lang="en-US">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Import and Export CSV using PHP and MySQL</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>

<body>

    <?php if (!empty($statusMsg)) { ?>

        <div class="col-xs-12">

            <div class="alert <?= $statusType ?>"><?= $statusMsg ?></div>

        </div>

    <?php } ?>

    <div class="row">

        <div class="col-md-12 head">

            <div class="float-right">

                <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrm');"><i
                        class="plus"></i> Import</a>

                <a href="exportcsvfile.php" class="btn btn-primary"><i class="exp"></i> Export</a>

            </div>

        </div>

        <div class="col-md-12" id="importFrm" style="display: none;">

            <form action="importcsvfile.php" method="post" enctype="multipart/form-data">

                <input type="file" name="file" />

                <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">

            </form>

        </div>

        <table class="table table-striped table-bordered">

            <thead class="thead-dark">

                <tr>

                    <th>#ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>Phone</th>

                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $result = $db->query("SELECT * FROM users ORDER BY id DESC");

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {

                ?>

                        <tr>

                            <td><?= $row['id'] ?></td>

                            <td><?= $row['name'] ?></td>

                            <td><?= $row['email'] ?></td>

                            <td><?= $row['phone'] ?></td>

                            <td><?= $row['status'] ?></td>

                        </tr>

                    <?php }
                } else { ?>

                    <tr>
                        <td colspan="5">No records found...</td>
                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

    <script>
        function formToggle(ID) {

            var element = document.getElementById(ID);

            if (element.style.display === "none") {

                element.style.display = "block";

            } else {

                element.style.display = "none";

            }

        }
    </script>

</body>

</html>