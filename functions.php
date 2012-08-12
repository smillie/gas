<?php

    function generatePassword ($length = 8) {
        $password = "";
        $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
        $maxlength = strlen($possible);
        if ($length > $maxlength) {
            $length = $maxlength;
        }
        $i = 0; 
        while ($i < $length) { 
            $char = substr($possible, mt_rand(0, $maxlength-1), 1);
            if (!strstr($password, $char)) { 
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }


    function isUserInGroup($con, $user, $group) {
        $group_search = ldap_search($con, "cn=$group,ou=groups,dc=geeksoc,dc=org", "(memberUid=$user)");
        if (ldap_count_entries($con, $group_search) >= 1) {
          return true;
        } else {
          return false;
        }
    }

    function getAllGroups($con) {
        $group_search = ldap_search($con, "ou=groups,dc=geeksoc,dc=org", "(objectClass=posixGroup)");
        ldap_sort($con, $group_search, 'cn');
        $results = ldap_get_entries($con, $group_search);

        return array_slice($results, 1);
    }

    function getGroupsForUser($con, $user) {
        $groups = array();
        foreach (getAllGroups($con) as $groupEntry) {
            if (isUserInGroup($con, $user, $groupEntry['cn'][0])) {
                $groups[] = $groupEntry['cn'][0];
            }
        }
        return $groups;
    }

    function getStatus($expiry, $paid) {
        $day = intval(time()/(60*60*24));
        $i = (int)$expiry;
        if (!isset($expiry)) $i=99999;
        $status = "Active";
        $stat_icon = "icon-ok";
        if ($paid == "FALSE") {
          $status = "Not Paid";
          $stat_icon = "icon-exclamation-sign";
        } 
        if ($i <= ($day+60)) {
          $status = "Expiring";
          $stat_icon = "icon-time";
        }
        if ($i <= $day) {
          $status = "Expired";
          $stat_icon = "icon-time";
        }
        if ($i == 1) {
          $status = "Administratively Disabled";
          $stat_icon = "icon-ban-circle";
        }
        return "<i class='$stat_icon'></i> $status";
    }

    function ircNotify($message) {
        global $conf;
        
        if ($conf['ircNotifications']) {
            $ircmessage = $conf['ircChannel']." [GAS] $message";
            $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_connect($sock, $conf['ircServer'], $conf['ircBotPort']);
            socket_write($sock, $ircmessage, strlen($ircmessage));
            socket_close($sock);
        }
    }
    
    function mailNotify($to, $subject, $message) {
        global $conf;
        $from = $conf['mailFrom'];
        
        if ($conf['mailNotifications']) {
            mail($to, $subject, $message, "From: $from");
        }
    }
    
    function addUser($uid, $first, $last, $stuno, $email) {
        global $con;
        global $dn;
        global $user;
        
        // echo "$uid, $first, $last, $stuno, $email";
        
        if (ldap_count_entries($con, ldap_search($con, $dn, "(uid=$uid)")) >= 1) {
            $return = "Username '$uid' already exists.";
        } else {

        //compute uid
            $users = ldap_get_entries($con, ldap_search($con, $dn, "(objectclass=posixaccount)"));
            $uidno = 10000;
            foreach($users as $u) {
                if ($u['uidnumber'][0] > $uidno) 
                    $uidno = $u['uidnumber'][0];
            }
            $uidno += 1;

        //compute expiry date
            $expiry = 15988;

        //generate password
            $pass = generatePassword(); 
            mt_srand((double)microtime()*1000000);
            $salt = pack("CCCCCCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand());
            $hashedpass = "{SSHA}" . base64_encode( sha1( $pass . $salt, true) . $salt );

        //assemble object
            //$newuser['dn'] = "uid=$uid,$dn";
            $newuser['objectclass'][0] = "inetOrgPerson";
            $newuser['objectclass'][1] = "organizationalPerson";
            $newuser['objectclass'][2] = "person";
            $newuser['objectclass'][3] = "top";
            $newuser['objectclass'][4] = "posixAccount";
            $newuser['objectclass'][5] = "shadowAccount";
            $newuser['objectclass'][6] = "gsAccount";
            $newuser['cn'] = "$first $last";
            $newuser['sn'] = $last;
            $newuser['givenName'] = $first;
            $newuser['title'] = "Member";
            $newuser['uid'] = $uid;
            $newuser['uidnumber'] = (int) $uidno;
            $newuser['gidNumber'] = (int) 500;
            $newuser['homeDirectory'] = "/home/$uid";
            $newuser['loginShell'] = "/bin/bash";
            $newuser['gecos'] = "$first $last,,,";
            $newuser['shadowLastChange'] = (int) 10877;
            $newuser['shadowMax'] = (int) 99999;
            $newuser['shadowWarning'] = (int) 7;
            $newuser['mail'] = $email;
            $newuser['studentNumber'] = (int) $stuno;
            $newuser['hasPaid'] = "TRUE";
            $newuser['hasSignedTOS'] = "TRUE";
            $newuser['shadowExpire'] = (int) $expiry;
            $newuser['userpassword'] = $hashedpass;

        //add to directory
            ldap_add($con,"uid=$uid,$dn", $newuser);
            if (ldap_error($con) != "Success") {
                $return = ldap_error($con);
            }
        //adduser to members group
            $newmember['memberUid'] = $uid;
            ldap_mod_add($con, "cn=members,ou=groups,dc=geeksoc,dc=org", $newmember);
            if (ldap_error($con) != "Success") {
                $return = ldap_error($con);
            }

        //email user confirmation
            $userEmail = <<<EOT
Welcome to GeekSoc $first!

You may find your new account details below, but please change your password at http://accounts.geeksoc.org/ as soon as possible.

Username: $uid 
Password: $pass

You may login to the shell server via SSH at shell.geeksoc.org on port 22. IRC may be found at irc.geeksoc.org on port 6667 - #geeksoc is the official channel.

On Windows the program PuTTY may be used to login to the SSH server, while Mac/Linux users will already have SSH installed and may connect using the 'ssh' command from a terminal.

The recommended way of accessing IRC is setting up a persistent connection on Shell using screen and irssi, see http://quadpoint.org/articles/irssi for details on how to set this up.

Have fun, but please be responsible and abide with the terms of service.

GeekSoc
http://www.geeksoc.org/
EOT;
            mailNotify($email, "[GeekSoc] Your account has been created", $userEmail);

        //email creation notice to gsag
            $adminEmail = <<<EOT
An account has been created by $user for $first $last:

Username: $uid
Email: $email
EOT;
            mailNotify("gsag@geeksoc.org", "[GeekSoc] New account created", $adminEmail);

        //irc creation notice (#gsag)
            ircNotify("Account created for $first $last: Username: $uid, Email: $email (by $user)");


            $return = "S Created user '$uid' with password '$pass'.";
        }
        
        return $return;
    }

?>
