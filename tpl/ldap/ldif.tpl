DN: $LDAPDN
objectClass: dcobject
objectClass: organizationalUnit
dc: ${SUFIJO[0]}
ou: ${SUFIJO[0]}

DN: cn=$data_modify,$LDAPDN
objectClass: person
objectClass: top
cn: $data_modify
sn: $data_modify
description: Administrador del Dominio LDAP
userPassword: $passwd
pwdPolicySubentry: cn=ClaveAdmin,ou=Politicas,$LDAPDN

DN: ou=Politicas,$LDAPDN
objectClass: organizationalUnit
objectClass: top
ou: Politicas

DN: cn=ClaveDef,ou=Politicas,$LDAPDN
objectClass: pwdPolicy
objectClass: pwdPolicyChecker
objectClass: person
objectClass: top
cn: ClaveDef
pwdAllowUserChange: TRUE
pwdAttribute: userPassword
pwdCheckQuality: 2
pwdCheckModule: check_password.so
pwdExpireWarning: 1728000
pwdFailureCountInterval: 0
pwdGraceAuthnLimit: 0
pwdInHistory: 5
pwdLockout: TRUE
pwdLockoutDuration: 900
pwdMaxAge: 7776000
pwdMaxFailure: 3
pwdMinAge: 0
pwdMinLength: 8
pwdMustChange: TRUE
pwdSafeModify: FALSE
sn: Politicas de Autenticacion
description: Overlay Ppolicy

DN: cn=ClaveAdmin,ou=Politicas,$LDAPDN
objectClass: pwdPolicy
objectClass: pwdPolicyChecker
objectClass: person
objectClass: top
cn: ClaveAdmin
pwdAllowUserChange: TRUE
pwdAttribute: userPassword
pwdCheckQuality: 2
pwdCheckModule: check_password.so
pwdExpireWarning: 0
pwdFailureCountInterval: 0
pwdGraceAuthnLimit: 0
pwdInHistory: 5
pwdLockout: FALSE
pwdLockoutDuration: 0
pwdMaxAge: 0
pwdMaxFailure: 0
pwdMinAge: 0
pwdMinLength: 8
pwdMustChange: FALSE
pwdSafeModify: FALSE
sn: Politicas de Autenticacion Administradores
description: Overlay Ppolicy

DN: ou=
<PLANTILLA>,$LDAPDN
    objectClass: organizationalUnit
    objectClass: top
    ou:
    <PLANTILLA>

        DN: ou=Computadoras,ou=
        <PLANTILLA>,$LDAPDN
            objectClass: organizationalUnit
            objectClass: top
            ou: Computadoras

            DN: ou=Usuarios,ou=
            <PLANTILLA>,$LDAPDN
                objectClass: organizationalUnit
                objectClass: top
                ou: Usuarios

                DN: uid=admin,ou=Usuarios,ou=
                <PLANTILLA>,$LDAPDN
                    objectClass: account
                    objectClass: posixAccount
                    objectClass: top
                    objectClass: shadowAccount
                    cn: Administrador
                    description: Administrador del Dominio
                    gidNumber: 1500
                    homeDirectory: /home/admin
                    loginShell: /bin/bash
                    uid: admin
                    uidNumber: 1500
                    userPassword: {SSHA}1/4v8zT+Bzd0IqzC5kn516Fj4SGn1wbu
                    pwdPolicySubentry: cn=ClaveAdmin,ou=Politicas,$LDAPDN

                    DN: ou=Grupos,ou=
                    <PLANTILLA>,$LDAPDN
                        objectClass: organizationalUnit
                        objectClass: top
                        ou: Grupos

                        DN: cn=administradores,ou=Grupos,ou=
                        <PLANTILLA>,$LDAPDN
                            objectClass: posixGroup
                            objectClass: top
                            cn: administradores
                            description: Administradores del dominio
                            gidNumber: 1500
                            memberUid: admin

                            DN: cn=Usuarios,ou=Grupos,ou=
                            <PLANTILLA>,$LDAPDN
                                objectClass: posixGroup
                                objectClass: top
                                cn: Usuarios
                                description: Grupo de Usuarios
                                gidNumber: 100

                                DN: cn=Audio,ou=Grupos,ou=
                                <PLANTILLA>,$LDAPDN
                                    objectClass: posixGroup
                                    objectClass: top
                                    cn: Audio
                                    description: Grupo de Audio
                                    gidNumber: 18

                                    DN: cn=Video,ou=Grupos,ou=
                                    <PLANTILLA>,$LDAPDN
                                        objectClass: posixGroup
                                        objectClass: top
                                        cn: Video
                                        description: Grupo de Video
                                        gidNumber: 27

                                        DN: cn=Polkitd,ou=Grupos,ou=
                                        <PLANTILLA>,$LDAPDN
                                            objectClass: posixGroup
                                            objectClass: top
                                            cn: Polkitd
                                            description: Grupo de Polkitd
                                            gidNumber: 101

                                            DN: cn=Plugdev,ou=Grupos,ou=
                                            <PLANTILLA>,$LDAPDN
                                                objectClass: posixGroup
                                                objectClass: top
                                                cn: Plugdev
                                                description: Grupo de Plugdev
                                                gidNumber: 999
