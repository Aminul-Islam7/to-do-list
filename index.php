<?php include 'database.php' ?>

<?php

  // Fetch data
  $sql = 'SELECT * FROM tasks';
  $result = mysqli_query($conn, $sql);
  $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);


  
  // Update task
  if (isset($_POST['update-task'])) {
    $taskId = $_POST['task-id'];
    $update = $_POST['body'];

    $sql = "UPDATE tasks SET `body` = '$update' WHERE id = $taskId";

    if (mysqli_query($conn, $sql)) {
      // Success
      header('Location: index.php');
    }
    else {
      // Error
      echo 'Error ' . mysqli_error($conn);
    }
  }
  

  // Complete task
  if (isset($_POST['complete-task'])) {
    $taskId = $_POST['task-id'];

    // Delete from database
    $completeQuery = "SELECT `complete` FROM tasks WHERE id = $taskId";
    $completeResult = mysqli_query($conn, $completeQuery);

    $row = mysqli_fetch_assoc($completeResult);
    $complete = $row['complete'];

    $complete = $complete ? 0 : 1;

    $sql = "UPDATE tasks SET `complete` = $complete WHERE id = $taskId";

    if (mysqli_query($conn, $sql)) {
      // Success
      header('Location: index.php');
    }
    else {
      // Error
      echo 'Error ' . mysqli_error($conn);
    }
  }
  
  

  // Delete task
  if (isset($_POST['delete-task'])) {
    $taskId = $_POST['task-id'];
    echo $taskId;

    // Delete from database
    $sql = "DELETE FROM tasks WHERE id = $taskId";

    if (mysqli_query($conn, $sql)) {
      // Success
      header('Location: index.php');
    }
    else {
      // Error
      echo 'Error ' . mysqli_error($conn);
    }
  }


  // Add Task
  if(isset($_POST['add-task'])) {

    if(!empty($_POST['body'])) {
      $body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      // Add to database
      $sql = "INSERT INTO tasks (body) VALUES ('$body')";

      if(mysqli_query($conn, $sql)) {
        // Success
        header('Location: index.php');
      }
      else {
        // Error
        echo 'Error ' . mysqli_error($conn);
      }
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>To Do List</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  
</head>
<body>

  <div class="container">
    <div class="task-list">
      <?php foreach($tasks as $task): ?>
      
      <form class="task-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">


        <input class="updatetask-textbox <?php echo $task['complete'] ? 'strikethrough' : null ?>" type="text" placeholder="<?php echo $task['body'] ?>" name="body" value="<?php echo $task['body']; ?>">

        <input style="display: none;" type="text" name="task-id" value="<?php echo $task['id']; ?>">

        <button style="display: none;" class="updatetask-button" type="submit" value="" name="update-task"><i class="fas fa-trash"></i></i></button>
        
        <button class="completetask-button <?php echo $task['complete'] ? 'complete' : null; ?>" type="submit" value="" name="complete-task"><i class='fas fa-check'></i></button>

        <button class="deletetask-button" type="submit" value="" name="delete-task"><i class="fas fa-trash"></i></i></button>

      </form>

      <?php endforeach ?>
    </div>

    <form class="addtask-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

      <input class="addtask-button" type="submit" value="+" name="add-task">

      <input class="addtask-textbox" type="text" placeholder="Add a task" name="body">

    </form>
    
  </div>

</body>
</html>
