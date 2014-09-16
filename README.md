SimpleTableEditor
=================

This is a super-lightweight jQuery/PHP plugin that adds simple editable functionality to HTML tables.  It's not very fancy looking, but of course can be further embellished through CSS styling or additional plugins, such as [Uniform](http://uniformjs.com/) and the like.  In general, it's a very simple and convenient way to make a database-backed table interactive and updatable.  The syntax of naming the inputs with the `name[]`
convention is specifically aimed at PHP server-side processing, since the data is collated into an array when
the inputs are named with brackets at the end.

The attached PHP class can be utilized to automatically create an HTML table with all the data from a MySql database table, or fill an existing HTML table with said data. If the PHP code is used to create the `table`, then the column headers (`th` elements) are determined by the database column names.  In order to use other column headers (or none at all), it is necessary to create an empty HTML table, and use the PHP to fill it with data.
The PHP code also can process databass record insertion, deletion, and update directly from the HTML <form>.

Implementation
--------------

Implementing the plugin is super-simple.

__PHP__

First, upload the 'TableLoader.php' file which contains the TableLoaderUpdater class.

Next, instantiate the class and specifiy your database parameters in order to enable the class to interact with the database.  It should look something like this:

    $myTableLoader = new TableLoaderUpdater();
    $myTableLoader->IDField = "recordID";
    $myTableLoader->table = "mytable";
    $myTableLoader->dbName = "my_db";
    $myTableLoader->dbServer = "localhost";
    $myTableLoader->dbUser = "username";
    $myTableLoader->password = "password";
  
Alternatively, all the parameters can be detemined in the constructor:

    $myTableLoader = new TableLoaderUpdater("recordID","mytable","my_db","localhost","username","password");
  
After that, there are two different ways to implement the class: by associating it with an existing HTML <table> element, or by using to create the <table>.

Using an existing table

Inside the <table> element, use the _fillTable()_ method of the class:

    <table class="editable" id-column="1">
      <?php
        $myTableLoader->fillTable();
      ?>
    </table>

Creating a new table

First, change the _makeTable_ field to true (default is false):

    <?php
      $myTableLoader->makeTable = true;
    ?>
  
If you want columns to be automatically named after the database table columns, you can set the _addColumnHeaders_ field to true as well:

    $myTableLoader->addColumnHeaders = true;
  
When creating a new `<table>` element, there is an option to give it an 'id' attribute, by using the _newTableID_ field:

    $myTableLoader->newTableID = "my_table";


Then, anywhere in the HTML document, in the position you want the table to appear, use the _fillTable()_ method:

    <?php
      $myTableLoader->fillTable();
    ?>
  
The 'class' and 'id-column' attributes will be automatically added.  Now the table is linked to the database, and the editable functionality will affect the original database records.  

__Note:__ The <table> element, in either case, must be contained in a <form> whose 'action' attribute is set to specify the file that contains the PHP class instantiation (usually the same file).

__JS__

1. Upload the JavaScript file (form_editor.js) to your file tree.
2. Include it on the web page where you want to use it: `<script src="form_editor.js"></script>` for instance.
3. Add `class="editable"` to the `<table>` tag. (unnecessary if the table was created automatically by the PHP code)
4. If you want to be able to update and delete database records, add the special attribute "id-column" to the `<table>` tag, with the value being the column number that contains a unique record identifier.  Column number means the index, counting from the beginning of the table, the column that contains the buttons having index 0, and the column immediately after it having index 1.

Dependencies
------------
It seems to work with jQuery 1.7 and later.

PHP-- I built it with PHP 5.3.10.1, but it should with versions as earlier as 4.1

Notes
-----

Currently the database update and insertion functions use *mysqli_query()* without providing any string escaping, which leaves the database vulnerable to SQL injection. In the near future I hope to take care of this issue.  In the mean time, be very careful to whom you give access to your database-linked tables!

Planned Features to be Added
----------------------------

- Incorporate full escaping for strings inserted/added to database
- Option to make the entire database editable on page load, not just line by line

Enjoy!
