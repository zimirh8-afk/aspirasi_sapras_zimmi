<?php $user = $_SESSION['user'] ?? null; ?>

<?php if ($user): ?>
    </div><!-- end page-body -->
    <footer class="footer"></footer>
</div><!-- end main-content -->
</div><!-- end app-wrapper -->
<?php else: ?>
</div><!-- end auth-page -->
<?php endif; ?>

<script src="/prasarana/public/js/app.js?v=<?= time() ?>"></script>
<script>
if (typeof lucide !== 'undefined') lucide.createIcons();

function openSidebar() {
    var s = document.getElementById('sidebar');
    var o = document.getElementById('sidebarOverlay');
    if (s) s.classList.add('open');
    if (o) o.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    var s = document.getElementById('sidebar');
    var o = document.getElementById('sidebarOverlay');
    if (s) s.classList.remove('open');
    if (o) o.classList.remove('show');
    document.body.style.overflow = '';
}
</script>
</body>
</html>
