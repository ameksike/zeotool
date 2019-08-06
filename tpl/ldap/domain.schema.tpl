attributetype ( 2.5.4.555.1 NAME 'Politica'
DESC 'CDSI: Definición políticas de dominio LDAP'
EQUALITY caseExactMatch
SUBSTR caseExactSubstringsMatch
SYNTAX 1.3.6.1.4.1.1466.115.121.1.15{32768} )

objectclass ( 2.5.6.555.2 NAME 'Politica'
DESC 'CDSI: Definición de políticas de dominio LDAP'
SUP top AUXILIARY
MUST Politica )

attributetype ( 2.5.4.555.3 NAME 'uuid'
DESC 'CDSI: Definición políticas de dominio LDAP'
EQUALITY caseExactMatch
SYNTAX 1.3.6.1.4.1.1466.115.121.1.15{36} )

attributetype ( 2.5.4.555.5 NAME 'DirIP'
DESC 'CDSI: Definición políticas de dominio LDAP'
EQUALITY caseExactMatch
SYNTAX 1.3.6.1.4.1.1466.115.121.1.15{15} )

attributetype ( 2.5.4.555.6 NAME 'lshw-serial'
DESC 'CDSI: Definición políticas de dominio LDAP'
EQUALITY caseExactMatch
SYNTAX 1.3.6.1.4.1.1466.115.121.1.15{1000} )

attributetype ( 2.5.4.555.8 NAME 'lshw-full'
DESC 'CDSI: Definición políticas de dominio LDAP'
EQUALITY caseExactMatch
SYNTAX 1.3.6.1.4.1.1466.115.121.1.15{40000} )

attributetype ( 2.5.4.555.7 NAME 'Clasificacion'
DESC 'CDSI: Definición políticas de dominio LDAP'
EQUALITY caseExactMatch
SYNTAX 1.3.6.1.4.1.1466.115.121.1.15{1} )

objectclass ( 2.5.6.555.4 NAME 'Computadora'
DESC 'CDSI: Estación de trabajo unida al dominio'
SUP top STRUCTURAL
MUST ( uuid $ name )
MAY ( lshw-serial $ lshw-full $ DirIP $ Clasificacion ) )

attributetype ( 2.5.6.555.10 NAME ( 'nomb' 'nombre' )
DESC 'CPA: Definición de atributos para Zimbra'
SUP name )

attributetype ( 2.5.6.555.11 NAME ( 'apell' 'apellido' )
DESC 'CPA: Definición de atributos para Zimbra'
SUP name )

objectclass ( 2.5.6.555.9 NAME 'ZimbraCPA'
DESC 'CPA: Definición de atributos necesarios para Zimbra'
SUP top AUXILIARY
MAY ( mail $ nomb $ apell ) )
