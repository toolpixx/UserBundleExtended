# FOSUserBundleExtended
Add and change/overwrite some functions stuff for  [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle)

* Add dashboard (with symfony related feed) and forward after login.
* Add automatically <i>role (ROLE_CUSTOMER)</i> after registration. (FOSUserBundle doesn't)
* Remove ask for <i>current_password</i> at profile edit page.
* Add <i>avatar</i> for user incl. upload.
* Add TwigExtension to show the <i>avatar</i>.

* Add <i>username</i> to the session after login or profile edit. Because if profile edit will be error ocurred the username doesn't correct.

* Add <i>avatar</i> to the session after login or profile edit. Because if profile edit will be error ocurred the avatar doesn't correct.

* Demonstrate how <i>overwrite</i> FOSUserBundle-Controller and add FOSUserBundle-EventListener.
* Forward to the <i>dashboard</i> if user is logged in when he is called the /login, /register or /resetting/request
* Remove the route /profile/show
* Delete profile-picture now


## Installation:

Please check if your parameter.yml has correct parameter for mysql-database and email. (needed)

<pre>
sudo bin/composer.phar install
</pre>

<pre>
sudo app/console cache:clear
</pre>

<pre>
sudo app/console doctrine:schema:update --force
</pre>

<pre>
sudo app/console assets:install
</pre>

<pre>
sudo mkdir web/uploads/user/profilepics
# is generated automatically / change permissions if needed
</pre>

<pre>
sudo app/console server:run
</pre>

<pre>
http://127.0.0.1:PORT
</pre>
