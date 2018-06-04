# DEPRECATED!

# degoo
600GB free backup space.

[degoo.com](https://degoo.com/) is a service offering 100GB backup space for free and option to add 500GB more inviting friedns to use it.
Using this script you will be able to add as many additional space as you want (up to 500GB).

##It is very easy to use.##
```
require_once 'degoo.class.php';
try{
    $degoo = new Degoo('email@email.com', 'XXXXXXX' );
    $result = $degoo->register('max');
    print_r($result);
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}
```
Where:

**email@email.com** is some valid email address used for base for the registrations.

**XXXXXXX** is your invitor id. This is very import to be corect.
You can get this id from https://degoo.com/me/invitemore . Last characters under your Degoo link is the id.

###Possible values for register function###
Parameter for ```register``` could be int number of GB you would like to add or 'max', which will add 500GB to your account.
```
 $result = $degoo->register('max');
 ```
 OR
 ```
 $result = $degoo->register(9);
```
