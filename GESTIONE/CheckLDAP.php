<?php

function ldap_connect_moodle($host_url, $ldap_version, $user_type, $bind_dn, $bind_pw, $opt_deref, &$debuginfo, $start_tls=false) {
    if (empty($host_url) || empty($ldap_version) || empty($user_type)) {
        $debuginfo = 'No LDAP Host URL, Version or User Type specified in your LDAP settings';
        return false;
    }

    $debuginfo = '';
    $urls = explode(';', $host_url);
    foreach ($urls as $server) {
        $server = trim($server);
        if (empty($server)) {
            continue;
        }

        $connresult = ldap_connect($server); // ldap_connect returns ALWAYS true

        if (!empty($ldap_version)) {
            ldap_set_option($connresult, LDAP_OPT_PROTOCOL_VERSION, $ldap_version);
        }

        // Fix MDL-10921
        if ($user_type === 'ad') {
            ldap_set_option($connresult, LDAP_OPT_REFERRALS, 0);
        }

        if (!empty($opt_deref)) {
            ldap_set_option($connresult, LDAP_OPT_DEREF, $opt_deref);
        }

        if ($start_tls && (!ldap_start_tls($connresult))) {
            $debuginfo .= "Server: '$server', Connection: '$connresult', STARTTLS failed.\n";
            continue;
        }

        if (!empty($bind_dn)) {
            $bindresult = @ldap_bind($connresult, $bind_dn, $bind_pw);
        } else {
            // Bind anonymously
            $bindresult = @ldap_bind($connresult);
        }

        if ($bindresult) {
            return $connresult;
        }

        $debuginfo .= "Server: '$server', Connection: '$connresult', Bind result: '$bindresult'\n";
    }

    // If any of servers were alive we have already returned connection.
    return false;
}

?>