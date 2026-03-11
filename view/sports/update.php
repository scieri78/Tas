<!-- a simple view returning json data -->
<?php
include "view/header.php";
?>
<div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Sports</h2>
                    </div>
                    <p>Please fill this form and submit to add sports record in the database.</p>
                    <form action="index.php?controller=<?php echo $_GET['controller']?>&action=update" method="post" >
                        <div class="form-group <?php echo (!empty($sporttb->category_msg)) ? 'has-error' : ''; ?>">
                            <label>Sport Category</label>
                            <input type="text" name="category" class="form-control" value="<?php echo $sporttb->category; ?>">
                            <span class="help-block"><?php echo $sporttb->category_msg;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($sporttb->name_msg)) ? 'has-error' : ''; ?>">
                            <label>Sports Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $sporttb->name; ?> ">
                            <span class="help-block"><?php echo $sporttb->name_msg;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $sporttb->id; ?>"/>
                        <input type="submit" name="updatebtn" class="btn btn-primary" value="Submit">
                        <a href="index.php?controller=<?php echo $_GET['controller']?>&action=list" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
<?php
include "view/footer.php";
?>