   
      <?php if(isset($_SESSION['student'])): ?>

         <div class="center">
            <a href="dashboard.php">Dashboard</a> | 
            <a href="logout.php">Logout</a>
         </div>

      <?php elseif(isset($_SESSION['admin'])): ?>

         <div class="center">
            <a href="admin-dashboard.php">Dashboard</a> | 
            <a href="logout.php">Logout</a>
         </div>

      <?php endif; ?>

   </div>

   <pre><?php print_r($_GET); ?></pre>
   <pre><?php print_r($_POST); ?></pre>
   <pre><?php print_r($_SESSION); ?></pre>

</body>
</html>
