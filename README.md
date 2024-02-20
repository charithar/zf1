RELEASE INFORMATION
===================

This is a fork from Zend Framework 1.12.21dev Release.

PURPOSE
---------------------------

Contains Zend Framework 1 plus performance improvements and bug fixes.

SYSTEM REQUIREMENTS
===================

Zend Framework requires PHP 7.4 or later. 

INSTALLATION
============

Please see [INSTALL.md](INSTALL.md).

LICENSE
=======

The files in this archive are released under the Zend Framework license.
You can find a copy of this license in [LICENSE.txt](LICENSE.txt).


docker compose exec zf1_test ../bin/phpunit --stderr -d memory_limit=-1 Zend/RegistryTest.php
