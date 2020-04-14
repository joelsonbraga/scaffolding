
<!doctype html>
<html lang="en">
<?php
require_once '../lib/header.php';
?>
<body>
<div class="container">
    <br/>
    <!-- Content here -->
    <div class="alert  alert-success <?php echo isset($_GET['msg'])?null:'d-none'?>" role="alert">
        <?php echo isset($_GET['msg'])? $_GET['msg']:'d-none'?>
    </div>
    <form class="form-horizontal"  method="post"  action="run.php">
        <input type="hidden" name="action" value="generate">
        <fieldset>
            <!-- Form Name -->
            <legend>Resources Generate</legend>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="class_name">Class Name</label>
                <div class="col-md-5">
                    <input id="className" name="className" type="text" placeholder="Class Name" class="form-control input-md" required="">
                    <span class="help-block">Class Name</span>
                </div>
            </div>
            <!-- Multiple Checkboxes -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="repositoryExceptions">Resources Exceptions</label>
                <div class="col-md-4">
                    <div class="checkbox">
                        <label for="repositoryExceptions-0">
                            <input type="checkbox" checked name="resources[resources]" id="resources-0" value="resources">
                            Resources
                        </label>
                    </div>
                    <div class="checkbox">
                        <label for="repositoryExceptions-1">
                            <input type="checkbox" checked name="resources[collection]" id="resources-1" value="collection">
                            Collection
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
<?php
require_once '../lib/footer.php';
?>
</body>
</html>
