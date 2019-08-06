#
# See slapd.conf(5) for details on configuration options.
# This file should NOT be world readable.
#
{$schemas}
# Define global ACLs to disable default read access.
# Do not enable referrals until AFTER you have a working directory
# service AND an understanding of referrals.
#referral       ldap://root.openldap.org

pidfile         /var/run/openldap/slapd.pid
argsfile        /var/run/openldap/slapd.args

# Load dynamic backend modules:
modulepath      /usr/lib/openldap/openldap
moduleload      ppolicy.so
# moduleload    back_sock.so
# moduleload    back_shell.so
# moduleload    back_relay.so
# moduleload    back_perl.so
# moduleload    back_passwd.so
# moduleload    back_null.so
# moduleload    back_monitor.so
# moduleload    back_meta.so
# moduleload    back_ldap.so
# moduleload    back_dnssrv.so

# Sample security restrictions
#       Require integrity protection (prevent hijacking)
#       Require 112-bit (3DES or better) encryption for updates
#       Require 63-bit encryption for simple bind
# security ssf=1 update_ssf=112 simple_bind=64

# Sample access control policy:
#       Root DSE: allow anyone to read it
#       Subschema (sub)entry DSE: allow anyone to read it
#       Other DSEs:
#               Allow self write access
#               Allow authenticated users read access
#               Allow anonymous users to authenticate
#       Directives needed to implement policy:
# access to dn.base="" by * read
# access to dn.base="cn=Subschema" by * read
# access to *
#       by self write
#       by users read
#       by anonymous auth
#
# if no access controls are present, the default policy
# allows anyone and everyone to read anything but restricts
# updates to rootdn.  (e.g., "access to * by * read")

access to attrs="userPassword"
by anonymous auth
by self write
by * none

access to attrs="shadowLastChange"
by self write
by * read

access to dn.subtree="ou=htd,dc=xetid,dc=uim"
by set="[cn=administradores,ou=Grupos,ou=htd,dc=xetid,dc=uim]/memberUid & user/uid" write
by * read

access to *
by * read

#
# rootdn can always read and write EVERYTHING!
#######################################################################
# BDB database definitions
#######################################################################

database        {$dbtype}
suffix          "{$base}"
#
<kbyte>
    <min>
        checkpoint 32 30
        rootdn "cn={$user},{$base}"
        # Cleartext passwords, especially for the rootdn, should
        # be avoid. See slappasswd(8) and slapd.conf(5) for details.
        # Use of strong authentication encouraged.
        rootpw {$pass}
        # The database directory MUST exist prior to running slapd AND
        # should only be accessible by the slapd and slap tools.
        # Mode 700 recommended.
        directory /var/lib/openldap-data
        # Indices to maintain
        index objectClass eq
        index uid,cn eq,sub,pres,approx
        index uidNumber,memberUid,gidNumber eq
        ### ppolicy overlay information ###
        overlay ppolicy
        ppolicy_default "{$ppolicy}"
