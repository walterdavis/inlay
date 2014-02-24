Inlay
=====

A Freeway-centric CMS that turns any static page into a template for dynamic content.

These notes are based on the alpha1 version of Inlay, which is not feature-complete. It should do what is listed below, as long as you follow these notes explicitly.

###Requires:

* Apache or compatible Web server with `mod_rewrite` or equivalent
* PHP 5.3 or higher
* MySQL 5.1 or higher, with compatible PDO bindings installed in PHP
* Freeway Pro 6 or higher (or another HTML5 design tool and text editor to add data- attributes)

###Installation

1. Copy all files into the folder where you want the site to appear. (This can be your `htdocs` folder, or a subfolder within that.) Make sure that you have enabled "show hidden files" in your SFTP application, so you get the .htaccess file in there. You only need one copy of these files at the top-most level of the site where you want to use Inlay, any subfolders will automatically work.

2. Open the file `_inlay/config.inc.php` in a programmer's editor. At a **minimum**, edit line 9 to provide your database credentials. Other options are marked with comments, you may edit them as you like.

3. Visit the URL `[your server url]/_inlay/setup.php` in your browser. If you see any error messages, make the corrections requested there. Once you see the message "Inlay is ready to go!", then you are ready to continue to the next step.

4. Visit the URL `[your server url]/_inlay/sign_up.php` in your browser, and create your user account. Please note: anyone can do the same, and will be able to edit your test site. Best to not publicize it just yet -- more security is coming soon.

5. Make sure you have uploaded at least one template to the same folder (or a subfolder within) the folder on your server containing your `_inlay.php` file before you start using Inlay. You may read more about creating templates in the next section of this guide.

###Design

Design your template pages as you would any other HTML page, and save them with a normal .html file-type ending. Upload the pages to the site exactly as you would normally.

Each field on a page must have its own unique name. (Field names may be used over again on other pages, but if you use the same field name twice, you will only get one value -- repeated -- for that duplicate field.) The combination of the page URL and the field name is used to identify each data field, so there is no way to use the same data on more than one page.

If you are using Freeway Pro, the Inlay Action (item-action and inline-action variants) may be used to mark individual page elements or headings as editable. The Inlay Page Action may be used to mark the `title` tag as editable, too. Only the elements that you have marked in this fashion will be editable in Inlay.

If you are not using the Actions, you may identify each editable element in your template by adding the following HTML5 `data-` attributes:

* `data-inlay-source` = The name of the data field. (Must begin with a letter a-z, and may not contain any spaces or punctuation besides the underscore character.)
* `data-inlay-format` = Either `string` or `markdown`, indicating the output format. Content in Markdown format will always return a `<p>` or another block-level tag around its content, so you may not use this format in settings where that combination of tags would be illegal, such as inside another `<p>`.

Only pages that you want to use as templates need to be marked up in this manner. Inlay may be used in a mixed site, i.e. one containing a mix of static pages and either dynamic or virtual pages.

> ####Static Pages

> Just like any other HTML pages, these are static pages (or pages that use a different dynamic replacement system) and do not have any `data-inlay-source` or `data-inlay-format` markings within them.

> ####Dynamic Pages

> These are pages that have been marked up with the data- attributes noted above. The page is requested at its full (and actual) URL, and returned to the browser with each of the data- attributes replaced with the appropriate dynamic content.

> ####Virtual Pages

> These are pages that do not exist at all at the URL requested, but are fulfilled using one or another of the Dynamic pages as a template, and a unique set of content for each of the data- variables within that template. An unlimited number of virtual pages may be made from one template.

Once you have defined a page as a dynamic page, it is important that you keep all of the same field names when you edit that page. If you change the names of the fields, you will need to go through the data-entry process again on your server. It does not matter if you change the location of these fields within your design, and you are also free to add new fields if you like, but removing or renaming fields after you have entered data will cause your site to be missing that content.
    
###Editing and Content Entry

In your browser, visit the first page where you have created dynamic content areas. The dynamic areas will each show the same text: "[field name] is not defined". Enter the editing mode by adding ?edit=true to the URL, and add your desired content as follows:

In the editing mode, each area that can be edited will be surrounded by a blue dashed line. Click once on that area to edit in place. Click anywhere else on the page to return to the formatted content display. There is no need to press a "save" button or anything else, each time you click elsewhere, the database will save your changes.

The page title and meta tags may be edited by clicking their respective buttons in the sidebar. The large letter I at the top left returns you to the static page view (the same thing your visitors would see).

###Creating Virtual Pages

To create a virtual page, simply navigate to the URL where you want the page to be. A template chooser will appear, allowing you to pick any of the dynamic pages in your site as the template for this new page. Click 'Choose...' and you will enter the editing interface as above. 

Once you have saved this choice, the template chooser will not appear again.

##TODO:

* Change the template on a virtual page after it has been created
* Create global data elements (for footers or whatever)
* Create template areas on the page (for repeating loop structures)
* Markdown Toolbar
* Remove dependencies on JavaScript libraries.
* i18n
* ???
