inlay
=====

A Freeway-centric CMS that turns any static page into a template for dynamic content.

###Requires:

* Apache or compatible Web server with `mod_rewrite` or equivalent
* PHP 5.3 or higher
* MySQL 5.1 or higher, with compatible PDO bindings installed in PHP
* Freeway 6 or higher (or another HTML5 design tool and text editor to add data- attributes)

###Design

Design your template pages as you would any other HTML page, and save them with a normal .html file-type ending. If you are using Freeway, use the fwCMS Action to mark individual HTML boxes or inline elements as editable fields. Each field on a page must have its own unique name. The fwCMS Page Action may be used to mark the `title` tag as editable, too. If you are not using the Action, you may mark each editable element as follows:

* `data-source` = The name of the data field. (Must be a valid PHP variable name.)
* `data-format` = Either `string` or `markdown`, indicating the output format. Content in Markdown format will always return a `<p>` or another block-level tag around its content, so you may not use this format in settings where that combination of tags would be illegal, such as inside another `<p>`.

Only pages that you want to use as templates need to be marked up in this manner. fwCMS may be used in a mixed site, containing static pages and either dynamic or virtual pages.

> ####Static Pages

> Just like any other HTML pages, these are static pages (or pages that use a different dynamic replacement system) and do not have any `data-source` or `data-format` markings within them.

> ####Dynamic Pages

> These are pages that have been marked up with the data- attributes noted above. The page is requested at its full (and actual) URL, and returned to the browser with each of the data- attributes replaced with the appropriate dynamic content.

> ####Virtual Pages

> These are pages that do not exist at all at the URL requested, but are fulfilled using one or another of the Dynamic pages as a template, and a unique set of content for each of the data- variables within that template. An unlimited number of virtual pages may be made from one template.

Once you have defined a page as a dynamic page, it is important that you keep all of the same field names when you edit that page. If you change the names of the fields, you will need to go through the data-entry process again on your server. It does not matter if you change the location of these fields within your design, and you are also free to add new fields if you like, but removing or renaming fields will cause your site to display incorrectly until you update the database to match.

###Upload

Upload your pages to the server as usual. Inside the same folder as your top-level `index.html` page, upload the contents of the fwCMS package. Edit the config.inc.php file to include your database details:

	[line 9] define('MAR_DSN', 'mysql://db_user:db_password@db_server_name/db_name');

Create or select the database matching these credentials in your database server, and run the following SQL queries to generate the tables:

    CREATE TABLE `elements` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `signature` char(32) NOT NULL DEFAULT '',
      `source` varchar(255) DEFAULT '',
      `format` varchar(255) DEFAULT 'string',
      `content` text,
      `created_at` datetime NOT NULL,
      `updated_at` datetime NOT NULL,
      PRIMARY KEY (`id`),
      KEY `signature` (`signature`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE `pages` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `signature` char(32) NOT NULL DEFAULT '',
      `server` varchar(255) DEFAULT '',
      `template` varchar(255) DEFAULT '',
      `path` varchar(1024) DEFAULT NULL,
      `created_at` datetime NOT NULL,
      `updated_at` datetime NOT NULL,
      PRIMARY KEY (`id`),
      KEY `signature` (`signature`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE `sessions` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE `users` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `first_name` varchar(255) NOT NULL DEFAULT '',
      `last_name` varchar(255) NOT NULL DEFAULT '',
      `email` varchar(255) NOT NULL DEFAULT '',
      `encrypted_password` varchar(255) NOT NULL DEFAULT '',
      `created_at` datetime NOT NULL,
      `updated_at` datetime NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
###Editing and Content Entry

In your browser, visit the first page where you have created dynamic content areas. The dynamic areas will each show the same text: "[field name] is not defined". Enter the editing mode and add your desired content.

In the editing mode, each area that can be edited will be surrounded by a blue dashed line. Click once on that area to edit in place. Click anywhere else on the page to return to the formatted content display. There is no need to press a "save" button or anything else, each time you click elsewhere, the database will save your changes.

The page title is edited in the gray "eyebrow" that appears at the top of the page. The large arrow at the top left returns you to the static page view (the same thing your visitors would see).

###Creating Virtual Pages

To create a virtual page, simply navigate to the URL where you want the page to be. A template chooser will appear, allowing you to pick any of the dynamic pages in your site as the template for this new page. Click 'Choose...' and you will enter the editing interface as above. 

Once you have saved this choice, the template chooser will not appear again. 
