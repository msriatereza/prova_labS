<?php

require 'authentication.php'; 

$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == NULL || $security_key == NULL) {
    header('Location: index.php');
}

$user_role = $_SESSION['user_role'];

$task_id = $_GET['task_id'];



if(isset($_POST['update_task_info'])){
    $obj_admin->update_task_info($_POST,$task_id, $user_role);
}

$page_name="Edit Task";
include("include/sidebar.php");

$sql = "SELECT a.*, b.fullname 
FROM task_info a
LEFT JOIN tbl_admin b ON(a.t_user_id = b.user_id)
WHERE task_id='$task_id'";
$info = $obj_admin->manage_all_info($sql);
$row = $info->fetch(PDO::FETCH_ASSOC);

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">



    <div class="row">
      <div class="col-md-12">
        <div class="well well-custom">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <div class="well">
                <h3 class="text-center bg-primary" style="padding: 7px;">Detalhes das tarefas</h3><br>

                      <div class="row">
                        <div class="col-md-12">

                        	 <div class="table-responsive">
				                  <table class="table table-bordered table-single-product">
				                    <tbody>
				                      <tr>
				                        <td>Titulo</td><td><?php echo $row['t_title']; ?></td>
				                      </tr>
				                      <tr>
				                        <td>Descrição</td><td><?php echo $row['t_description']; ?></td>
				                      </tr>
				                      <tr>
				                        <td>Data de inicio</td><td><?php echo $row['t_start_time']; ?></td>
				                      </tr>
				                      <tr>
				                        <td>Data limite</td><td><?php echo $row['t_end_time']; ?></td>
				                      </tr>
				                      <tr>
				                        <td>Feito por</td><td><?php echo $row['fullname']; ?></td>
				                      </tr>
				                      <tr>
				                        <td>Status</td><td><?php  if($row['status'] == 1){
											                        echo "Em progresso";
											                    }elseif($row['status'] == 2){
											                       echo "Completo";
											                    }else{
											                      echo "Incompleto";
											                    } ?></td>
				                      </tr>

				                    </tbody>
				                  </table>
				                </div>

                            <div class="form-group">

                              <div class="col-sm-3">
                                <a title="Update Task"  href="task-info.php"><span class="btn btn-success-custom btn-xs">Voltar</span></a>
                              </div>
                            </div>
                          </form> 
                        </div>
                      </div>

              </div>
            </div>
          </div>

        </div>
      </div>
    </div>


<?php


include("include/footer.php");

?>

