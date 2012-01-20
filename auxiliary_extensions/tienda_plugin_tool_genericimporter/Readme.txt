Importing Coupons - Using the Coupon Importer
===========================================
Installation

Just use the Joomla installer to install the provided install file. Do NOT unzip the file first.


Creating the import file

We suggest using the sample import CSV file provided. Just delete the 2 data rows after you have taken a look at them to see how data should be entered.


File Structure / Field Notes

Here's the file structure for the import file. The file must be in standard CSV format.

Field		Type		











NOTE: You can NOT include any reference to the specific products in the import if you choose the 'Per Product' coupon_type, you will need to manually set those relations after you import the coupon file.

ALSO: Be aware that when you export CSV files using Excel (and some other spreadsheets) your date fields may not be in a proper order to be imported. To be properly imported the date must be in the format of "YYYY-MM-DD HH:MM:SS". Excel,by default, will export your date as "MM/DD/YYYY HH:MM:SS". If you try to import a file with dates like this the coupon will have a blank date/time upon import.


Importing your coupon import file

Once you have your coupon import file ready to go, just follow these steps:
1. Open the Generic Importer - You can do this in one of two ways:
	a. Click the 'Generic Importer' toolbar button from the Tienda Dashboard
	b. Click Tools And Reports | Select Tools | Click on Tool-Generic Importer
2. Make sure that 'Coupon Import' is selected in the 'Choose type of import
3. Click the SUBMIT button in the toolbar
4. Use the Choose File button to locate your import file; make sure the separator is set to "," (a comma); check the 'Skip Frist Row" box
5. Click the SUBMIT button in the toolbar
6. Review the import information and if all is correct, click the SUBMIT button in the toolbar
7. Review the list of migration results, esp. noting any errors listed. If there are serious errors click the close button in the toolbar and correct your import file, otherwise click the SUBMIT button to apply the import to your site.
	NOTE: At the time of this document the importer will display a 'warning' error "TiendaTableCoupons does not support ordering". This is NOT a critical error and your coupons will import properly (as long as there are no other errors.
8. Click the Coupons menu to review the coupons you have imported. If you have any errors, just select the imported coupons, delete them, then correct your import file and repeat this process.
