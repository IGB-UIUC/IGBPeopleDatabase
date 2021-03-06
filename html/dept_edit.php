<?php 

$page_title = "IGB People Database"; 

require_once 'includes/header.inc.php';


if(isset($_GET['dept_id'])){$user_id = $_GET['dept_id'];}

$dept_list = department::get_all_departments($db);
$error_msg = "";

echo "<script>";

echo "var deptArr = [];\n"; 
foreach($dept_list as $dept) 
{
    echo "deptArr[\"".$dept->get_id()."\"] = new Array(\"".$dept->get_name()."\",\"".$dept->get_code()."\");\n";
}
echo "</script>"; 

/*
Add DEPT
*/

if (isset($_POST['add_dept'])){
	$dept_name = $_POST['dept_name'];
	$dept_code = $_POST['dept_code'];
	$error_msg = "";
	
	$key_exists = NULL;
	$temp_dept = new department($db);
        
	if (empty($dept_name)){  
		$error_msg .= "Please enter department name<br>";
		$error_count++;
		
	}
	if (empty($dept_code)){  
		$error_msg .= "Please enter department code<br>";
		$error_count++;	
	}
        
        if($temp_dept->dept_code_exists($dept_code) !== false) {
            $error_msg .= "A department with the code '$dept_code' already exists.<br>";
            $error_count++;
        }
        
        if($temp_dept->dept_name_exists($dept_name) !== false) {
            $error_msg .= "A department with the name '$dept_name' already exists.<br>";
            $error_count++;
        }
	
	if ($error_count == 0){

                $params = array("dept_name"=>$dept_name, "dept_code"=>$dept_code);
                
		$dept_id = department::add_dept($db, $dept_name, $dept_code);
		
		
		echo("<h3>Department added.</h3><BR>");
		unset($_POST['add_dept']);
                
                $dept_list = department::get_all_departments($db);

	}

}			
			
if (isset($_POST['submit_edit_dept'])){
        $dept_id = $_POST['edit_dept_drop'];
	$dept_name = $_POST['name'];
	$dept_code = $_POST['dept_code'];
        $edit_error_msg = "";

        $department = new department($db, $dept_id);
        
	if (!empty($dept_id)){
            
            if($department->dept_code_exists($dept_code) !== false) {
                $edit_error_msg .= "A department with the code '$dept_code' already exists.<br>";
                $error_count++;
            }

            if($department->dept_name_exists($dept_name) !== false) {
                $edit_error_msg .= "A department with the name '$dept_name' already exists.<br>";
                $error_count++;
            }

            if ($error_count == 0){

                $department->edit_dept($dept_name, $dept_code);

                unset($_POST['submit_edit_dept']);

                echo("<h3>Department information updated.</h3><BR>");
                
                $dept_list = department::get_all_departments($db);
            }
        }	
}			

/*
DEPT INFO TABLE HTML
*/

$dept_table = "<div class='left sixty'>
	
    <div class='noborder'>
            ".
                    html::dept_list_table($db)
                    ."

    </div>
    </div>
    <br>
    ";			

/*
DEPT ADD FORM HTML
*/
$dept_add_table = "<form method='post' action='dept_edit.php' name='add_dept'>

    <div class='right forty bordered'>
            <div class='profile_header'>
                    <p class='alignleft'>[ Add department ]</p>
            </div>
            <div class='noborder'>
                <label class='errormsg'>".$error_msg."</label><br>

                <table class = 'profile'>
                    <tr >
                      <td class='noborder'><label>Name </label><br> </td>
                      <td class='noborder'>
                            <input type='medium' name='dept_name' maxlength='50'  >
                      </td>
                    </tr>
                    <tr >
                      <td class='noborder'><label>Code</label><br> </td>
                      <td class='noborder'>
                            <input type='medium' name='dept_code' maxlength='12'  >
                      </td>
                    </tr>
                </table>

            </div>
            <div class='alignright'>
                <input type='submit' name='add_dept' id='add_dept' value='Add'  >
            </div >

            <br></div>

            </form>
            ";

$dropdown_list = array();
foreach($dept_list as $dept) {
    $dropdown_list[] = array($dept->get_id(), $dept->get_name());
}
$dept_edit_table = "<form method='post' action='dept_edit.php' name='submit_edit_dept' id='submit_edit_dept'>

    <div class='right forty bordered'>
            <div class='profile_header'>
                    <p class='alignleft'>[ Edit department ]</p>
            </div>
            <table class = 'profile'>
            <label class='errormsg'>".$edit_error_msg."</label><br>
                <tr >
                <tr >
                  <td class='noborder'><label>Department Name </label><br> </td>
                  <td class='noborder'>"
                        . html::dropdown("edit_dept_drop", $dropdown_list)
                  ."</td>
                </tr>
                  <td class='small'><label>Department Name</label><br> </td>
                  <td class = 'noborder'>
                    <input name='name' id='name'>
                  </td>
                </tr>
                <tr >
                  <td class='small'><label>Code</label><br> </td>
                  <td class = 'noborder'>
                    <input name='dept_code' id='dept_code'>
                  </td>
                </tr>

            </table>

            <br>

            <div class='alignright'>
                    <input type='submit' name='submit_edit_dept' id='submit_edit_dept' value='Edit Department'>
            </div>
            </div>
            </form>
            ";

$dept_edit_html2 = "<div id='dept_edit_html'>
    <div>
            <form method='post' action='dept_edit.php' name='dept_edit' id='dept_edit'>
            <label class='required'>Edit Department</label>	
                <br>
                <br>

                <table class = 'profile'>
                    <tr >
                    <tr >
                      <td class='noborder'><label>Department Name </label><br> </td>
                      <td class='noborder'>"
                            . html::dropdown("edit_dept_drop", $dropdown_list)
                      ."</td>
                    </tr>
                      <td class='small'><label>Department Name</label><br> </td>
                      <td class = 'noborder'>
                        <input name='name' id='name'>
                      </td>
                    </tr>
                    <tr >
                      <td class='small'><label>Code</label><br> </td>
                      <td class = 'noborder'>
                        <input name='dept_code' id='dept_code'>
                      </td>
                    </tr>

                </table>

            <br>
            </div>
            <div class='alignright'>
                    <input type='submit' name='submit_edit_dept' id='submit_edit_dept' value='Edit Department'>
            </div>
            </form>
            </div>";
        
        
?> 


 


<script>
$(document).ready(function(){

 $("ul#admin").show();
 $("ul#directory").hide();

});
</script>


<h1> departments </h1>

<h3>
[ see 
<a href='http://www.dmi.illinois.edu/ddd/mkexcel.asp'>
here</a> for additional department codes ]

</h3>
<?php 
	echo $dept_table;
	echo $dept_add_table;
        echo $dept_edit_table;
        echo "<div style='display:none'>";
        echo $dept_edit_html;
        echo("</div>");

?>


<div style='display:none'>
		<div id='theme_add_table'>

		</div>

</div>
<br>
<?php 

require_once ("includes/footer.inc.php"); 

?> 
