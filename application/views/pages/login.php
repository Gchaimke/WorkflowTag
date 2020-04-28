<form id="login-form" class="form-signin" onsubmit="login()">
        <img class="mb-4" src="assets/img/7.jpg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
        <label for="inputUser" class="sr-only">User Name</label>
        <input type="text" id="inputUser" class="form-control" placeholder="User Name" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <div class="checkbox mb-3">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit" >Sign in</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2017-2019</p>
</form>