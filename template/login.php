<form action="#" method="post">
    <?php if ($bad_credential): ?>
        <p>Bad credential.</p>
    <?php endif; ?>
    <input type="text" name="username" placeholder="Username"/><br/>
    <input type="password" name="password" placeholder="Password"/><br/>
    <input type="submit"/>
</form>