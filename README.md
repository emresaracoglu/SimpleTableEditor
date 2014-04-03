SimpleTableEditor
=================

This is a super-small jQuery plugin that adds simple editable functionality to HTML tables.  It's not very
fancy looking, but of course can be further embellished through CSS styling or additional plugins, such as
[Uniform](http://uniformjs.com/) and the like.  In general, it's a very simple and convenient way to make a
database-backed table interactive and the updatable.  The syntax of naming the inputs with the `name[]`
convention is specifically aimed at PHP server-side processing, since the data is collated into an array when
the inputs are named with brackets at the end.

Implementation
--------------

Implementing the plugin is super-simple.

1. Upload the JavaScript file (form_editor.js) to your file tree.
2. Include it on the web page where you want to use it: `<script src="form_editor.js"></script>` for instance.
3. Add `class="editable"' to the `<table>` tag.
4. If you want to be able to update and delete database records, add the special attribute "id-column" to the
`<table>` tag, with the value being the column number that contains a unique record identifier.

Planned Features to be Added
----------------------------

- PHP class to implement display or data and update of database
- Option to make the entire database editable on page load, not just line by line

Enjoy!
