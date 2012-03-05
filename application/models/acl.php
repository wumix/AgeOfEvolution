<?php
//creazione ACL
$acl=new Zend_Acl();
//creazione ruoli
$acl->addRole(new Zend_Acl_Role("guest"));// naviga solo nella parte principale del sito
$acl->addRole(new Zend_Acl_Role("user"),"guest");//può acedere al server
$acl->addRole(new Zend_Acl_Role("staff"),"user");
$acl->addRole(new Zend_Acl_Role("owner"),"user");// proprietario account
$acl->addRole(new Zend_Acl_Role("sharer"),"owner");// sharer
$acl->addRole(new Zend_Acl_Role("civless"),"user");// senza civiltà
$acl->addRole(new Zend_Acl_Role("wait"),"civless");// in attesa
$acl->addRole(new Zend_Acl_Role("debuger"),"staff");//privilegi di debug, visualizzazione log e debug tool
$acl->addRole(new Zend_Acl_Role("mh"),"staff");// accesso a pannello mh (da implementare)
$acl->addRole(new Zend_Acl_Role("admin"));//tutti i privilegi

//creazione risorsa
//	modulo s1
$acl->addResource("s1");
//controller
$acl->addResource("building","s1");
$acl->addResource("ev","s1");
$acl->addResource("map","s1");
$acl->addResource("market","s1");
$acl->addResource("message","s1");
$acl->addResource("movements","s1");
$acl->addResource("profile","s1");
$acl->addResource("report","s1");
$acl->addResource("sharer","s1");
$acl->addResource("trainer","s1");
$acl->addResource("processing","s1");
$acl->addResource("stats","s1");

//	modulo admin
$acl->addResource("admin");
//controller
//@todo definire risorse admin
//modulo default
$acl->addResource("default");
//controller
$acl->addResource("account","default");
$acl->addResource("track","default");
//altro
$acl->addResource("debug");


//permessi del guest
$acl->allow("guest","default");
$acl->deny("guest","account");
$acl->deny("guest","track");
//permetto al guest di avviare l'E.P. e di leggere le stats
$acl->allow("guest","processing");
$acl->allow("guest","stats");
//permessi dell'user
$acl->allow("user","default");
//permessi staff
//@todo definire permessi staff
//permessi civless
$acl->allow("civless","s1");
$acl->deny("civless","ev");
$acl->deny("civless","map");
$acl->deny("civless","market");
$acl->deny("civless","message");
$acl->deny("civless","movements");
$acl->deny("civless","profile");
$acl->deny("civless","report");
$acl->deny("civless","sharer");
$acl->deny("civless","trainer");

//permessi owner
$acl->allow("owner","s1");
//permessi sharer
$acl->deny("sharer","sharer");
//@todo implementare permessi sharer dal db
//permessi debugger
$acl->allow("debuger","debug");
//permessi admin
$acl->allow("admin");// do tutti i poteri all'admin
?>