<footer>
    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
</footer>

<script>
  function openPopupMenu() {
    document.getElementById('popupMenu').style.display = 'flex';
  }

  function closePopupMenu() {
    document.getElementById('popupMenu').style.display = 'none';
  }

  // Optional: Close popup on Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closePopupMenu();
  });
</script>

<?php wp_footer(); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
