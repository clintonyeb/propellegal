<?php
setcookie("token", "", 1, '/');
?>

<h5 class="title is-5">Redirecting you to login page</h5>

<script type='text/javascript'>
  localStorage.removeItem('token');
  location.href = '/user_login';
</script>
