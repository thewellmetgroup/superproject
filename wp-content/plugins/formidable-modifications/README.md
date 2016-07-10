# Formidable modifications plugin
This plugin enhances default formidable plugin functionality, such as:

* Organizes file uploads by creating directories labeled by USERIDs (wp-content/uploads/applications/YEAR/USERID/USESRID-APPLICATION-SLUG.pdf)
* Creates better UX and presentation layer
* Creates word counters for fields where word count needs to be enforced
* Adds a PDF generator library that saves a PDF of the application into the user folder on your server
* Adds PDF highliting functionality enabling you to select which fields are displayed more prominently on the generated PDF document
* Enables you to send email reminders to users who have saved drafts of their forms, but haven't submitted them.

## Installation
* Make sure the “Formidable” plugin is installed and activated. It’s required for this plugin to function.
* Install the "formidable-modifications" plugin in your plugins directory.
* Go to your Plugins.
* Activate the plugin through the ‘Plugins’ menu.

## Use
* To enable word count enforcement: add field description with instructions to each field where you want to enforce word count. Add two classes to fields where you want word count enforced: "enforcecount" "20" where 20 is the number you wish to enforce, separate these class name values with a space, omit quotes).
* File uploads are saved into your uploads directory under "applications."
* To highlight specific fields in the PDF output, find the field key (Forms->Build->Field Options) for the field you wish to highlight and enter it in the Field Highlighter field as a comma separated list.
* To email draft owners, go to “Send email reminder” page, select the application form, fill in your subject and message to send a note to all users who have started the application but have not submitted it.