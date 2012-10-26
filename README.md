PHP-IMAP
========

PHP-IMAP is used to retrieve messages from an IMAP server. It uses the PHP IMAP extension to access a given IMAP server and can execute several operations to retrieve different parts of the available messages. Currently it can retrieve information about the mail box, message headers, message data, and attachments.

PHP-IMAP came about a few years ago due to a complete lack of quality libraries for working with PHPs IMAP functions, particularly dealing with attachments. I've just now gotten around to putting the project on github.

Features
------
*	Connects to IMAP server
*	Returns mailbox information
*	Access e-mail headers
*	List mailboxes
*	Read e-mails including attachments
*	Save attachments from remote imap server locally

Dependancies
------
* PHP 5.x
* php5-imap

Installation
------

You can either clone the project, download the project, or just copy & paste imap.class.php from github.

Documentation
------

When you download or clone, move the code into a directory you can run apache from. Load up the index.php page (example http://localhost/php-imap/index.php). This is a demo application. Each operation it executes is documented with the method it calls and the parameters it accepts. You can view the demo source to see how to use the class. I'll add more documentation later.

Licensing
------
Code is licensed under the MIT License.
