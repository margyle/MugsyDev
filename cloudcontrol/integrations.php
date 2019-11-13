<?php
session_start();
include 'inc/inc.cloudControlMain.php';
$keyResult = $_GET['created'];
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cloud Control | Integrations</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/6884ddcb45.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css" />

    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="skin-1">

    <div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">

                    <li class="active">
                        <a href="index.php"><i class="fas fa-tachometer-alt"></i> <span class="nav-label">Status</span></a>
                    </li>
                    <li>
                        <a href="community.php"><i class="fas fa-globe-americas"></i> <span class="nav-label">Community</span>
                        </a>
                    </li>
                    <li>
                        <a href="integrations.php"><i class="fas fa-link"></i> <span class="nav-label">Integrations</span>
                        </a>
                    </li>
                    <li>
                        <a href="settings.php"><i class="fas fa-cog"></i><span class="nav-label">Settings</span>
                        </a>
                    </li>
                    
                </ul>

            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#" style="background-color: #7c75b2; border-color: #7c75b2;"><i class="fa fa-bars"></i> </a>
                        <span style="font-family: bebas;font-size: 28pt;color: #707070;">Cloud Control</span>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <a href="#">
                                <i class="fa fa-wifi" style="color: #2ab071"></i> Online
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-coffee" style="color: #e33647"></i> <span style="font-family: avenir;font-size: 12pt;color: #707070;"> Not Ready To Brew</span>
                            </a>
                        </li>
                    </ul>

                </nav>
            </div>
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="m-t-lg">
                            <h1 style="font-family:bebas;font-size: 20pt;">
                                Integrations: <span style="font-family:avenir;font-size: 20pt;">Key Management</span>
                            </h1>
                                <hr>
                                <!-- start key creation notification -->
                                <?php
                                //need to add error notification
                                    if ($keyResult == 1) {
                                        echo '<div class="alert alert-success alert-dismissible fade show shadow col-lg-8" role="alert">
                                                <strong>Success! Key created!</strong>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>';
                                        }
                                ?>
                                <!-- end key creation notification -->
                        </div>
                        <!-- start new key form-->
                        <div class="row">
                            <div class="col-lg-8" id="newKeyForm">
                                <p>
                                    <button class="btn btn-primary btn-block bg-mugsyPurple shadow" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                        Create New Key
                                    </button>
                                </p>
                                <div class="collapse" id="collapseExample">
                                    <div class="card card-body">
                                        <form action="inc/inc.createKey.php" method="post">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1">Select Key Type</label>
                                                    <select class="form-control" id="integrationType" name="integrationType">
                                                        <option value="Coffee Now">Coffee Now</option>
                                                        <option value="D.E.C.A.F API">D.E.C.A.F API</option>
                                                        <option value="Developer">Developer</option>
                                                        <option value="test">test</option>
                                                    </select>
                                            </div>
                                                <hr>
                                            <button class="btn btn-primary btn-block shadow" id="recipeListStartButton">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <!-- start coffee now -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-t-lg">
                            <h1 style="font-family:bebas;font-size: 20pt;">
                                Key Detective
                            </h1>
                            <hr>
                        </div>
                    </div>
                </div>
                <!-- start data table -->
               
                <div class="tableContainer">
                    <table id="table" data-search="true" data-click-to-select="true">
                        <thead>
                            <tr>
                                <th data-field="apiKey">Key</th>
                                <th data-field="integrationType" data-searchable="true">Type</th>
                                <th data-field="creationDate">Created Date</th>
                                <th data-field="machineId">Machine ID</th>
                                <th data-field="requestCount">Usage</th>
                                <th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents">Revoke</th>


                            </tr>
                        </thead>
                    </table>
                </div>


            </div>
            <!-- end data table  -->
        </div>
        <div class="footer">

            <div>
                ArgyleLABS &copy; 2019
            </div>
        </div>

    </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>


    <!-- table JS -->
    <script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
    <script>
        var $table = $('#table');
        var mydata = <?php echo $keyDetectiveResponse;?>

        function operateFormatter(value, row, index) {
            return [
                '<a class="remove" href="javascript:void(0)" title="Remove">',
                '<center><i class="fa fa-trash" style="color:#e33647;"></i></center>',
                '</a>'
            ].join('')
        }

        window.operateEvents = {
            'click .remove': function(e, value, row, index) {
                $table.bootstrapTable('remove', {
                    field: 'id',
                    values: [row.id]
                })
            }
        }

        $(function() {
            $('#table').bootstrapTable({
                data: mydata
            });
        });
    </script>

</body>

</html>
