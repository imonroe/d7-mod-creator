# d7-mod-creator
A PHP CLI script for generating basic scaffolding for a Drupal 7 module

What we have here is a couple of little scripts to quickly build boilerplate code for developing modules for Drupal 7. 
I mostly use these in my own development work, and I'm making them available for anyone else who might like a little timesaver or two.

Prerequisites: PHP-CLI (version 5.2 or higher, PHP7.x friendly), and sufficient access to run PHP command-line scripts.

To install: download the repo to any convenient location on your machine.

Usage:  In a terminal window, navigate to the location you put the scripts.  Issue the command
'''php module_creator.php'''
	
Follow the prompts.  Your boilerplate module will be generated, and written where you ask.  Jump in and start coding your new module!

If you need to generate a form, and really, you're going to need to generate a form at some point or another, then just use the form builder:

'''php form_builder.php'''
	
Follow the prompts.  Your Drupal 7 Form API-compatible form will be generated and written to the file you specify.  Why not drop that bad boy into your new module?

Not all form elements are supported.  Currently supported elements: textfield, textarea, select, hidden, submit, radios, date, markup

For more information about how to work with the Drupal 7 Form API, check out the docs here: https://api.drupal.org/api/drupal/developer!topics!forms_api_reference.html/7.x



