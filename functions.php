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
            $ircmessage = $conf['ircChannel']." $message";
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

?>
