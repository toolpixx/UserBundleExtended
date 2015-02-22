# UserBundleExtended


## What is UserBundleExtended?

UserBundleExtended is not a plugin for [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle). It's an application that only will
demonstrate how you can use FOSUserBundle-Method's and configuration. I have change many
things for my needed privacy project for user and security. But many things is, what you
search for on stackoverflow, too.

Now you can install this application for your sandbox. Play with it and you can see what
you get, if you change configuration or methods.

I hope this application can answer your questions about FOSUB.

[Checkout the video for visual demonstration at youtube](https://www.youtube.com/watch?v=Ogyof5WTp3c&feature=youtu.be)

## I have integrate the follow functions:

- Avatar-Support
- Locale-Support
- Subuser-Support
- Admin-Support for management all users of the application
- Admin-Support with switch user
- Small Dashboard to demonstrate how i can cache rss-feed.
- Enquiry-Form with file upload and without database-entity. The entity is only for validations

- Technical from FOSUB: Change forms, add event-listener, overwrite action-methods

- and many other things.

## Installation:

* If your system is mac with yosemite please check that your php has
gd-support. If not, please have a look here [Install/Update php with gd-support](http://stackoverflow.com/questions/26493762/yosemite-php-gd-mcrypt-installation/26505558#26505558)

## Very important note:

* Please check if your parameter.yml has correct parameter for mysql-database and email. If not composer will ask for them.
* Please checkout, that you create backup fro existing database.

<pre>
#
# Clone the project from github
#
git clone git@github.com:toolpixx/UserBundleExtended.git
</pre>

<pre>
#
# Install all dependencies
#
sudo bin/composer.phar install
</pre>

<pre>
#
# Update your database-schema
#
sudo app/console doctrine:schema:update --force
</pre>

<pre>
#
# Install web-assets
#
sudo app/console assets:install
</pre>

<pre>
#
# Create upload-path for profile-pictures
# and copy the default-picture to the
# profile-pictures.
#
sudo bin/setup.sh
</pre>

<pre>
#
# Create admin-user
#
# user, email, password
#
sudo app/console fos:user:create admin demo@example.com 123456

#
# Add/promote role for admin-user
#
sudo app/console fos:user:promote admin ROLE_ADMIN
</pre>


<pre>
#
# Run internal php-server
#
sudo app/console server:run
</pre>

<pre>
#
# Look the port from step before and
# browse to this url.
#
http://127.0.0.1:PORT
</pre>

# Configuration?

Checkout the config_dev.yml, i have activated <b>chromephp</b> If you wan't use it, please comment it out.

[chromephp](https://github.com/ccampbell/chromephp)

Checkout the config.yml, i have activated <b>PhpStormOpener</b> If you wan't use it, please comment <b>ide</b> out.
[PhpStormOpener](https://github.com/pinepain/PhpStormOpener)

ide: "pstorm://%%f:%%l"
