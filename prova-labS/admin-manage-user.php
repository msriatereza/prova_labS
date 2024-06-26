<?php
require 'authentication.php'; 

$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == NULL || $security_key == NULL) {
    header('Location: index.php');
}

$user_role = $_SESSION['user_role'];
if($user_role != 1){
  header('Location: task-info.php');
}


if(isset($_GET['delete_user'])){
  $action_id = $_GET['admin_id'];

  $task_sql = "DELETE FROM task_info WHERE t_user_id = $action_id";
  $delete_task = $obj_admin->db->prepare($task_sql);
  $delete_task->execute();

  $attendance_sql = "DELETE FROM attendance_info WHERE atn_user_id = $action_id";
  $delete_attendance = $obj_admin->db->prepare($attendance_sql);
  $delete_attendance->execute();
  
  $sql = "DELETE FROM tbl_admin WHERE user_id = :id";
  $sent_po = "admin-manage-user.php";
  $obj_admin->delete_data_by_this_method($sql,$action_id,$sent_po);
}

$page_name="Admin";
include("include/sidebar.php");

if(isset($_POST['add_new_employee'])){
  $error = $obj_admin->add_new_user($_POST);
}

?>




  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

  
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2 class="modal-title text-center">Adicionar novas informações do funcionario</h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <?php if(isset($error)){ ?>
                <h5 class="alert alert-danger"><?php echo $error; ?></h5>
                <?php } ?>
              <form role="form" action="" method="post" autocomplete="off">
                <div class="form-horizontal">

                  <div class="form-group">
                    <label class="control-label col-sm-4">Nome Completo</label>
                    <div class="col-sm-6">
                      <input type="text" placeholder="Enter Employee Name" name="em_fullname" list="expense" class="form-control input-custom" id="default" required>
                    </div>
                  </div>
                   <div class="form-group">
                    <label class="control-label col-sm-4">Username</label>
                    <div class="col-sm-6">
                      <input type="text" placeholder="Enter Employee username" name="em_username" class="form-control input-custom" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-4">Email</label>
                    <div class="col-sm-6">
                      <input type="email" placeholder="Enter Employee Email" name="em_email" class="form-control input-custom" required>
                    </div>
                  </div>
                  
                 
                  
                  <div class="form-group">
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-3">
                      <button type="submit" name="add_new_employee" class="btn btn-success-custom">Adicionar</button>
                    </div>
                    <div class="col-sm-3">
                      <button type="submit" class="btn btn-danger-custom" data-dismiss="modal">Cancelar</button>
                    </div>
                  </div>
                </div>
              </form> 
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>



    <div class="row">
      <div class="col-md-12">
        <div class="row">
            
        <div class="well well-custom">
          <?php if(isset($error)){ ?>
          <script type="text/javascript">
            $('#myModal').modal('show');
          </script>
          <?php } ?>
            <?php if($user_role == 1){ ?>
                <div class="btn-group">
                  <button class="btn btn-success btn-menu" data-toggle="modal" data-target="#myModal">Adicionar novo funcionario</button>
                </div>
              <?php } ?>
          <ul class="nav nav-tabs nav-justified nav-tabs-custom">
            <li><a href="manage-admin.php">Gerenciar Admin</a></li>
            <li class="active"><a href="admin-manage-user.php">Gerenciar Funcionario</a></li>
          </ul>
          <div class="gap"></div>
          <div class="table-responsive">
            <table class="table table-codensed table-custom">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Nome</th>
                  <th>Email</th>
                  <th>Username</th>
                  <th>Senha</th>
                  <th>Detalhes</th>
                </tr>
              </thead>
              <tbody>

              <?php 
                $sql = "SELECT * FROM tbl_admin WHERE user_role = 2 ORDER BY user_id DESC";
                $info = $obj_admin->manage_all_info($sql);
                $serial  = 1;
                $num_row = $info->rowCount();
                  if($num_row==0){
                    echo '<tr><td colspan="7">No Data found</td></tr>';
                  }
                while( $row = $info->fetch(PDO::FETCH_ASSOC) ){
              ?>
                <tr>
                  <td><?php echo $serial; $serial++; ?></td>
                  <td><?php echo $row['fullname']; ?></td>
                  <td><?php echo $row['email']; ?></td>
                  <td><?php echo $row['username']; ?></td>
                  <td><?php echo $row['temp_password']; ?></td>
                  
                  <td><a title="Update Employee" href="update-employee.php?admin_id=<?php echo $row['user_id']; ?>"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;<a title="Delete" href="?delete_user=delete_user&admin_id=<?php echo $row['user_id']; ?>" onclick=" return check_delete();"><span class="glyphicon glyphicon-trash"></span></a></td>
                </tr>
                
              <?php  } ?>


                
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>


<?php
if(isset($_SESSION['update_user_pass'])){

  echo '<script>alert("Senha atualizada com sucesso");</script>';
  unset($_SESSION['update_user_pass']);
}
include("include/footer.php");

?>