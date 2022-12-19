CRUD-APP made by Symfony Framework

Symfony--version: 6.1.7

*NodeJs--version: 18.9.0

.env.local file is used instead of .env file (.gitignore could not ignore the .env file)

=> AdminController routes can be accessed only by the SUPER_ADMIN

=> All PersonneController routes are protected (config/packages/security.yaml)

=> ADMIN can manage the "personnes", but only if approved(enabled) by the SUPER_ADMIN

=> SUPER_ADMIN can manage both "personnes" and "admins" 

=> there is only one SUPER_ADMIN, and he must approve(enable) all admins registration requests
   before they can access their accounts and manipulate the "personnes".

=> SUPER_ADMIN can disbale or enable ADMINs, 

=> SUPER_ADMIN is the only one that has access to ADMINs list 




