
<!-- a simple view, outputing all the comments -->
<br style="padding-bottom: 20%">
 <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                       
                        <h2 class="pull-left">Sports Details</h2>
                        <a href="index.php?controller=<?php echo $_GET['controller']?>&action=insert" class="btn btn-success pull-right">Add New Sports</a>
                    </div>
                    <?php
                    if(count($array_result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";                                        
                                        echo "<th>Sports Category</th>";
                                        echo "<th>Sports Name</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                foreach ($array_result as $row) {
                                   
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";                                        
                                        echo "<td>" . $row['category'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>";
                                        echo "<a href='index.php?controller=".$_GET['controller']."&action=update&id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'>Edit</i></a>";
                                        echo " <a href='index.php?controller=".$_GET['controller']."&action=delete&id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'>Delete</i></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                    
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                           // mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    ?>
                </div>
            </div>        
        </div>
    </div>



<script>
    $(document).ready(function(){

        $("#productForm").submit(function(e) {

            var url = "index.php?controller=product&action=add"; // the script where you handle the form input.

            $.ajax({
                type: "POST",
                url: url,
                data: $("#productForm").serialize(), // serializes the form's elements.
                success: function(data)
                {
                    //alert(data); // show response from the php script.
                }
            });
            e.preventDefault(); // avoid to execute the actual submit of the form.
            showProducts();
        });


    });


    function showProducts() {
        $.get("http://localhost/webP/ex2/index.php?controller=product&action=showAll", function (data) {
            $("#products").html(data);
        });
    }

    function deleteProductAJAX($event) {
        $.ajax({
                url: $event,
                success: function(response) {
                    //alert(response);
                }
            });
        event.preventDefault();
        showProducts();
    }


</script>

<br>
<br>
<br>
<br>
<br>

