   
      <?php if(isset($_SESSION['student'])): ?>

         <hr>

         <div class="left">
            <a href="dashboard.php">Dashboard</a> | 
            <a href="logout.php">Logout</a>
         </div>

      <?php elseif(isset($_SESSION['admin'])): ?>

         <hr>

         <div class="left">
            <a href="console-dashboard.php">Dashboard</a> | 
            <a href="logout.php">Logout</a>
         </div>

      <?php endif; ?>

   </div>

   <pre><?php print_r($_GET); ?></pre>
   <pre><?php print_r($_POST); ?></pre>
   <pre><?php print_r($_SESSION); ?></pre>

</body>
</html>
