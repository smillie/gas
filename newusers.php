<?php require 'ldapconnect.php'; ?>
<?php
    if (!isUserInGroup($con, $user, "gsag")) {
        header( 'Location: index.php' );
    }

    $pageTitle = ' - New Members';

    
    $mysqli = new mysqli($conf['db_host'], $conf['db_user'], $conf['db_pass'], $conf['db_name']);
      if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit();
      }
      
      if ($_GET['action'] == 'create') {
          
          $returnMessage = addUser($_GET['uid'], $_GET['first'], $_GET['last'], $_GET['stuno'], $_GET['email']);
          if (substr($returnMessage, 0, 1) =='S') {
              $success = substr($returnMessage, 2);
          } else {
              $error = $returnMessage;
          }
          
          if (isset($success) && $stmt = $mysqli->prepare("UPDATE newusers SET IS_DELETED=true WHERE id = ?")) {
          
                $stmt->bind_param('i', $_GET['id']);
          
                $stmt->execute();
          
                $stmt->close(); 
          }
          else {
                  /* Error */
          }
          
      } elseif ($_GET['action'] == 'delete') {
                
          if ($stmt = $mysqli->prepare("DELETE FROM newusers WHERE id = ?")) {
          
                $stmt->bind_param('i', $_GET['id']);
          
                $stmt->execute();
          
                $stmt->close(); 
          }
          else {
                  /* Error */
          }
          
          $success = "Deleted user from approval queue.";
          
      }
      
      /* Create the prepared statement */
        if ($stmt = $mysqli->prepare("SELECT ID, firstname, lastname, username, studentnumber, email FROM newusers WHERE IS_DELETED = false")) {

                /* Execute the prepared Statement */
                $stmt->execute();

                $stmt->bind_result($id, $first, $last, $uid, $stuno, $email);
                while ($stmt->fetch()) {
                      // printf("%s %s %s %i %s\n", $first, $last, $uid, $stuno, $email);
                      $u['id'] = $id;
                      $u['first'] = $first;
                      $u['last'] = $last;
                      $u['uid'] = $uid;
                      $u['stuno'] = $stuno;
                      $u['email'] = $email;

                      $users[] = $u;
                  }

                /* Close the statement */
                $stmt->close(); 
        }
        else {
                /* Error */
        }


?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

    <div class="span10">

        <?php if (count($users) == 0) : ?>
            <div class="row">
                <div class="span5">
                    <?php if (isset($error)) : ?>
                      <div class="alert alert-error">
                        <?php echo "$error"; ?>
                      </div>
                    <?php elseif (isset($success)) : ?>
                      <div class="alert alert-success">
                        <?php echo "$success"; ?>
                      </div>
                    <?php endif; ?>
                    <feildset>
                        <legend>Approve New Members</legend>
                    </feildset>
                    <p>There are no new members to approve - get recruiting!</p>
                </div>
            </div>
        <?php else : ?>
            <table class="table ">
                <thead>
                  <tr>
                    <th></th>
                    <th>Account Name</th>
                    <th>Full Name</th>
                    <th>Student Number</th>
                    <th>Email</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                <?php if (isset($error)) : ?>
                  <div class="alert alert-error">
                    <?php echo "$error"; ?>
                  </div>
                <?php elseif (isset($success)) : ?>
                  <div class="alert alert-success">
                    <?php echo "$success"; ?>
                  </div>
                <?php endif; ?>
                

                    <?php foreach ($users as $user) : ?>
                        <tr>
                        <td><a href="newusers.php?action=create&uid=<?php echo $user['uid']."&first=".$user['first']."&last=".$user['last']."&stuno=".$user['stuno']."&email=".$user['email']."&id=".$user['id']; ?>" class="btn btn-mini">Create</a></td>
                            <td><?php echo $user['uid']; ?></td>
                            <td><?php echo $user['first'] . " " . $user['last']; ?></td>
                            <td><?php echo $user['stuno']; ?></td>
                            <td><a href="mailto:<?php echo $user['email']; ?>"><?php echo $user['email']; ?></a></td>
                            <td><a href="newusers.php?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-danger btn-mini">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php foreach ($created as $user) : ?>
                        <tr class="muted">
                        <td></td>
                            <td><?php echo $user['uid']; ?></td>
                            <td><?php echo $user['first'] . " " . $user['last']; ?></td>
                            <td><?php echo $user['stuno']; ?></td>
                            <td><a href="mailto:<?php echo $user['email']; ?>"><?php echo $user['email']; ?></a></td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
              </table>
        <?php endif; ?>
        
                      <p><a href="memberreport.php">Download CSV of all new members</a></p>
    </div>
<?php require 'footer.php'; ?>
