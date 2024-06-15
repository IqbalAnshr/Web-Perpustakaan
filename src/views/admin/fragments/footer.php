<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
        Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../src/views/admin/templates/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../src/views/admin/templates/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../src/views/admin/templates/dist/js/adminlte.min.js"></script>

<script>
    $(document).ready(function () {
        var currentPath = window.location.pathname;

        $('.nav-sidebar a').each(function () {
            var linkPath = $(this).attr('href');

            if (currentPath === linkPath) {

                $(this).addClass('active');
                $(this).parents('.nav-item').addClass('menu-open');
                $(this).parents('.nav-treeview').prev('.nav-link').addClass('active');
            }
        });
    });
</script>
</body>

</html>