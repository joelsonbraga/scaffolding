<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Scaffolding</title>
</head>
<body>

<div class="container">
    <br/>
    <!-- Content here -->
    <div class="alert  alert-success <?php echo isset($_GET['msg'])?null:'d-none'?>" role="alert">
        <?php echo isset($_GET['msg'])? $_GET['msg']:'d-none'?>
    </div>
    <form class="form-horizontal"  method="post"  action="request/run.php">
        <input type="hidden" name="action" value="generate">
        <fieldset>

            <!-- Form Name -->
            <legend>Request Generate</legend>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="class_name">Model Name</label>
                <div class="col-md-5">
                    <input id="className" name="className" type="text" placeholder="Class Name" class="form-control input-md" required="">
                    <span class="help-block">Model Name</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="requestClasses">Requests</label>
                <div class="col-md-4">
                    <div class="checkbox">
                        <label for="requestClasses-0">
                            <input type="checkbox" checked name="requests[store]" id="requestClasses-0" value="store">
                            Store
                        </label>
                    </div>
                    <div class="checkbox">
                        <label for="requestClasses-1">
                            <input type="checkbox" checked name="requests[index]" id="requestClasses-1" value="index">
                            Index
                        </label>
                    </div>
                    <div class="checkbox">
                        <label for="requestClasses-2">
                            <input type="checkbox" checked name="requests[update]" id="requestClasses-2" value="update">
                            Update
                        </label>
                    </div>
                </div>
            </div>
            <!-- Textarea -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="fields">Fields</label>
                <div class="col-md-4">
                    <textarea class="form-control" id="fields" name="fields">Separate fields by , Ex. field_1, field_1, field_1</textarea>
                </div>
            </div>

            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="generate"></label>
                <div class="col-md-4">
                    <button id="generate" name="generate" class="btn btn-primary">Generate</button>
                </div>
            </div>


        </fieldset>
    </form>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
