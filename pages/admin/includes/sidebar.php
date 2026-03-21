<?php 
$role = $_SESSION['role'] ?? '';
?>

<aside class="left-sidebar" data-sidebarbg="skin5">
<div class="scroll-sidebar">

<nav class="sidebar-nav">
<ul id="sidebarnav" class="pt-4">


<li class="sidebar-item">
    <a href="<?php echo $site_url; ?>pages/admin/index.php" class="sidebar-link">
        <i class="mdi mdi-view-dashboard"></i>
        <span class="hide-menu">Dashboard</span>
    </a>
</li>


<?php if($role == "admin" || $role == "instructor"): ?>

<li class="sidebar-item">
    <a href="<?php echo $site_url; ?>pages/admin/managequiz.php" class="sidebar-link">
        <i class="mdi mdi-receipt"></i>
        <span class="hide-menu">Quizzes</span>
    </a>
</li>

<?php endif; ?>


<?php if($role == "admin"): ?>

<li class="sidebar-item">
    <a href="<?php echo $site_url; ?>pages/admin/manageinstructor.php" class="sidebar-link">
        <i class="mdi mdi-face"></i>
        <span class="hide-menu">Instructor</span>
    </a>
</li>
<li class="sidebar-item">
    <a href="<?php echo $site_url; ?>pages/admin/manageuser.php" class="sidebar-link">
        <i class="mdi mdi-face"></i>
        <span class="hide-menu">Participant</span>
    </a>
</li>



<?php endif; ?>



<?php if($role == "participant"): ?>

<li class="sidebar-item">
    <a class="sidebar-link" href="<?php echo $site_url; ?>pages/admin/availablequizez.php">
        <i class="mdi mdi-receipt"></i>
        <span class="hide-menu">Available Quizzes</span>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link" href="<?php echo $site_url; ?>pages/admin/myattempts.php">
        <i class="mdi mdi-receipt"></i>
        <span class="hide-menu">Attempted Quizzes</span>
    </a>
</li>
<?php endif; ?>


</ul>
</nav>

</div>
</aside>