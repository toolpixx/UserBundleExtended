# UserBundleExtended
Add and change/overwrite some functions stuff for  [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle)

* Add image-crop for profil-picture. (Experimental, it isn't the last source for that)

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

* If your system is mac with yosemite please check that your php has
gd-support. If not, please have a look here [Install/Update php with gd-support](http://stackoverflow.com/questions/26493762/yosemite-php-gd-mcrypt-installation/26505558#26505558)

## Very important note:

* Please check if your parameter.yml has correct parameter for mysql-database and email. If not composer will ask for them.
* Please checkout, that you create backup fro existing database.

<pre>
git clone git@github.com:toolpixx/UserBundleExtended.git
</pre>

<pre>
sudo bin/composer.phar install
</pre>

<pre>
sudo app/console doctrine:schema:update --force
</pre>

<pre>
sudo app/console assets:install
</pre>

<pre>
sudo bin/setup.sh
</pre>

<pre>
sudo app/console server:run
</pre>

<pre>
http://127.0.0.1:PORT
</pre>

# Configuration?

Checkout the config_dev.yml, i have activated <b>chromephp</b> If you wan't use it, please comment it out.

[chromephp](https://github.com/ccampbell/chromephp)

Checkout the config.yml, i have activated <b>PhpStormOpener</b> If you wan't use it, please comment <b>ide</b> out.
[PhpStormOpener](https://github.com/pinepain/PhpStormOpener)

ide: "pstorm://%%f:%%l"
