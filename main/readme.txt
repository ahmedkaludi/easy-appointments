=== Easy Appointments ===
Contributors: loncar
Donate link: https://easy-appointments.com/
Tags: appointment, appointments, Booking, calendar, reservation
Requires at least: 3.7
Tested up to: 6.6
Requires PHP: 5.3
Stable tag: 3.12.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add Booking system to your WordPress site and manage Appointments with ease. Extremely flexible time management and custom email notifications.

== Description ==
Add Booking Appointments system to your WordPress site and manage Appointments with ease. Extremely flexible time management. Multiple location, services and workers. Email notifications.

Can be used for : <strong>Lawyers</strong>, <strong>Salons</strong>, <strong>Mechanic</strong>, <strong>Cleaning services</strong>, <strong>Doctors</strong>, <strong>Spas</strong>, <strong>Personal trainers </strong>, <strong>Private Lessons</strong>, <strong>Escape rooms</strong> etc,

= Live Demo =
<a href="https://easy-appointments.com/responsive-single-column-layout/">**Responsive Appointment form**</a><br>
<a href="https://easy-appointments.com/responsive-two-columns/">**Responsive Appointment form - two column layout**</a><br>
<a href="https://easy-appointments.com/full-calendar/">**Full calendar NEW**</a><br>
<a href="https://easy-appointments.com/demo-standard-single-column-layout/">**Standard Appointment form**</a><br>

= Doc =
<a href="https://easy-appointments.com/documentation/">Documentation</a>

= Features =

* Multiple **Locations**
* Multiple **Services**
* Multiple **Workers**
    - Create dedicated calendar for one location / service / worker
* Create time slots by connecting location – service – worker and date/time
    - Multiple time slots
    - Fine granular option for creating even most complex time table
    - **Bulk connections builder**
* **Extremely flexible time table**
* **Email notifications :**
    - Send email notification to customer on creation and update of appointment
    - Send email notification to predefined list of admin users
    - Send email notification to employee
    - Custom content and subject
    - Custom admin email
    - Confirm booking via link provided inside email
    - Cancel booking via link provided inside email
    - HTML content via WYSIWYG editor
    - Custom emails for different status of appointments : pending, reservation, canceled, confirmed
    - Include any information from booking inside email content even from custom fields
* **Single Column Responsive Bootstrap Layout** for Appointment form
* **Two Column Responsive Bootstrap layout**
* **Custom form fields :**
    - **Create your own custom form fields in a few clicks**
    - textarea
    - select
    - input
    - Make fields required
    - Drag and drop order
    - Google reCAPTCHA v2
    - Google reCAPTCHA v3 **NEW**
    - **NEW** use current logged in user data sa default value for custom field.
* **Internationalization** - support for translations (you can create your own translation <a href="https://easy-appointments.com/documentation/#translate">>> tutorial <<</a>)
    - German translation (thanks to Matthias)
    - Romanian translation (thanks to Vlad)
    - Polish translation (thanks to <a href="mailto:maciej@bauza.pl" target="_blank">Maciej Bauza</a>)
    - Finnish translation thanks to Maija
    - Portuguese translation thanks to Antonio
    - Portuguese Brazil translation thanks to seniweb
* Labels
    - Hide price
    - Add custom currency
    - Set currency before/after price
    - Custom style
* Localization of **datepicker for 77 different languages** (day of week, months)
* Reports
    - Time table overview
    - **Export to CSV (for Calc, Excel...)**

= Need even more like Google Calendar, iCalendar, WooCommerce or SMS support? =
There is extension plugin that you can buy and add to your Easy Appointments plugin :

* Google Calendar with 2 way sync
* iCalendar
* Notifications SMS & Mail (custom template for each status as well for reminder and follow up messages)
    - Twilio SMS
    - Omnicom SMS (Greece 🇬🇷)
    - Mail
* WooCommerce integration
* PayPal integration

For more info follow the link for <a href="https://easy-appointments.com/#extension">Extension plugin</a>

= HomePage =
<a href="https://easy-appointments.com/">easy-appointments.com</a>

== Installation ==

= Install process is quite simple : =

– After getting plugin ZIP file log onto WP admin page.
– Open Plugins >> Add new.
– Click on “Upload plugin” beside top heading.
– Drag and drop plugin zip file.

There is a really good non-official step-by-step video tutorial on https://www.youtube.com/watch?v=H7Hj4jfMDik

= Shortcode =
In order to have Appointments form in your Page or Post insert following shortcode
<code>
[ea_bootstrap]
</code>

Options :

width : default value 400px
scroll_off : default value off
layout_cols : default value 1

<a href="https://easy-appointments.com/documentation/#bootstrap">all available shortcode options</a>

example : [ea_bootstrap width="800px" scroll_off="true" layout_cols="2"]

== Frequently Asked Questions ==

= How to set custom cron task for clearing reserved slots =
Create cron task on your host that have this link : `wget -q -O - <STIE_URL>/?_ea-action=clear_reservations > /dev/null 2>&1` . This will delete all reservations older than 6min.

= How to translate labels = 

Form labels can change in settings page but if you want to translate rest of it you need to create translation file. Here is video tutorial for that : <a href="https://www.youtube.com/watch?v=yOnta9_Ysno"> Screencast </a>

= How to hide service / location / worker in front end part of form? =

To do this you must create one location / worker or service and set Name that starts with underscore. For example : *_dummy*, *_location*...

= In admin panel all pages from plugin are blank? =

You have probably turned on option in PHP called asp_tags, you need to turn it off in order to plugin work properly.

= I can't edit or delete any settings? =

Your hosting is probably blocking HTTP PUT and DELETE method. You must mark option called 'Compatibility mode' in settings.

= How to set multiple slots for one combination of location, service, worker? =

To add more slots per (location, service, worker) combination just clone the existing one. For two slots you need to
have that connection twice.

= How to insert Easy Appointments widget on Page/Post? =

Place following shortcode into your Page/Post content:

<code>
[ea_bootstrap]
</code>

For bootstrap there are options :
width : default value 400px
scroll_off : default value off
layout_cols : default value 1

<a href="https://easy-appointments.com/documentation/#bootstrap">all available shortcode options</a>

Example :
`[ea_bootstrap width="800px" scroll_off="true" layout_cols="2"]`

= How to set form in two columns? =

You can set bootstrap form in two columns with `layout_cols` option. Example :

<code>
[ea_bootstrap width="800px" scroll_off="true" layout_cols="2"]
</code>

= How to create calendar only for one worker / service / location =
If you want to have separate calendars base on worker for example. You can do that by setting default worker inside short code.
`[ea_bootstrap worker="1"]`
Value is worker #id number. Examples :

`[ea_bootstrap worker="1"]`
`[ea_bootstrap worker="1" location="1"]`
`[ea_bootstrap worker="1" location="1" service="1"]`

Note: you can have only one calendar on one page.

== Screenshots ==

1. Responsive front end two column `[ea_bootstrap layout_cols="2"]` - part1
2. Responsive front end two column `[ea_bootstrap layout_cols="2"]` - part2
3. Responsive front end shortcode `[ea_bootstrap]` - part1
4. Responsive front end shortcode `[ea_bootstrap]` - part2
5. Standard front end form for Appointment `[ea_standard]`
6. Full Calendar short code
7. Admin panel - Appointments list
8. Admin panel - Settings Location. Define your Locations
9. Admin panel - Settings Services. Define your Services
10. Admin panel - Settings Workers. Define your Workers
11. Admin panel - Settings Connection. Set single combination for location, service, worker
12. Admin panel - Settings - Bulk connection creation
13. Admin panel - Settings - Customize General
14. Admin panel - Settings - Customize - Email notifications / templates
15. Admin panel - Settings - Customize - Custom labels
16. Admin panel - Settings - Customize - Date & Time format
17. Admin panel - Settings - Customize - Custom fields
18. Admin panel - Settings - Customize - Google Captcha
19. Admin panel - Settings - Customize - Custom styles and redirects
20. Admin panel - Settings - Customize - GDPR
21. Admin panel - Settings - Customize - Money format
22. Admin panel - Tools page
23. Admin panel - Report
24. Admin panel - Report - Time table overview
25. Admin panel - Report - Export page

== Changelog ==

= 3.12.3 (2024-10-20)
* Fixed bug with Vacation page not loading (due to plan permalinks settings)

= 3.12.2 (2024-10-03)
* Fixed bug with ordering of options (location, service, worker)ameba33

= 3.12.1 (2024-09-08) =
* Added new option to load default Admin email template to mail editor

= 3.12.0 (2024-09-06) =
* Fixed issue with DI52 dependency conflict between versions with other plugins

= 3.11.22 (2024-08-06) =
* Moved from .NET to .COM so new home for EasyAppointments is https://easy-appointments.com

= 3.11.21 (2024-06-09) =
* Small style improvements on notification email for admin

= 3.11.20 (2024-06-09) =
* Fixed issue with warning messages inside log file

= 3.11.19 (2024-03-26) =
* Improved security for canceling reservation bookings
* Escaped `hide_cancelled` param for FullCalendar shortcode

= 3.11.18 (2024-03-20) =
* Added cache buster for customers form, in some cases on mobile screen calls were cached and booking failed. (`error message Undefined, then slot taken`)

= 3.11.17 (2024-02-14) =
* Fixed issue with error log records regarding missing duration on services.

= 3.11.16 (2024-01-30) =
* Fixed issue with deletion of advance redirect options inside Settings
* Added advance option to pass form data into redirect url, just add field slug in this format `{{slug_name}}` to the redirect url.

= 3.11.15 (2024-01-24) =
* Added option for user access for Reports page in admin part. You can select user role that can access that page.

= 3.11.14 (2024-01-22) =
* Fixed issue with loading images in admin panel for WP running under sub folder

= 3.11.13 (2024-01-20) =
* Extend connection in bulk updated to caver 2024 and all other new years

= 3.11.12 (2023-12-08) =
* Added new option inside bootstrap short code `auto_select_slot` for auto selecting slot if there is only one available during the day.

= 3.11.11 (2023-08-13) =
* Fixed bug with selection of long services that were blocked when they should be possible.

= 3.11.10 (2023-05-28) =
* Improved security for Admin section by adding nonce to all ajax requests

= 3.11.9 (2023-05-03) =
* Added new param for FullCalendar to hide cancelled events from being displayed in calendar (`hide_cancelled="0 or 1"` default `0`)

= 3.11.8 (2023-17-03) =
* Fixed issue with custom form fields that could not be deleted or edited

= 3.11.7 (2023-03-03) =
* Fixed issue with custom redirect settings on booking

= 3.11.6 (2023-02-13) =
* Fixed couple of code issues suggested by WP (part 2)

= 3.11.5 (2023-02-09) =
* Escaped mail content for admin notification (official WP guideline suggestion - part 1s)

= 3.11.4 (2022-12-31) =
* Added option to extend all connection in bulk that ends on `2022-12-31` for one more year :)

= 3.11.3 (2022-12-27) =
* Fixed issue with service name in options

= 3.11.2 (2022-12-25) =
* Fixed small label issue

= 3.11.1 (2022-12-25) =
* Security Patch: FullCalendar shortcode didn't have escaped props that could be misused by contributor. Not that is fixed now.
* Small breaking changes: Now HTML elements in settings will be also escaped meaning that it will not be rendered but just shown if you are using it.

= 3.10.7 (2022-11-21)
* Fixed issue with free slots calculation that didn't include block before/after time

= 3.10.6 (2022-11-17) =
* Fixed issue with open time slots that are after closing hours defined in connections

= 3.10.5 (2022-09-05) =
* Fixed issue with masked input field

= 3.10.4 (2022-08-15) =
* Added Vacation days to backend Appointments form. Now it will gray out those days when employee is on vacation

= 3.10.3 (2022-06-05) =
* Fixed smaller render issues with Connections table

= 3.10.2 (2022-06-01) =
* Fixed issue with wrong status color of connection rows in table.

= 3.10.1 (2022-05-10) =
* Fixed bug with PHP error during free slot calculation.

= 3.10.0 (2022-05-08) =
* Added options to customise user access rights for EasyAppointments admin pages.

= 3.9.3 (2022-04-01) =
* Fixed issue with possible overbooking in case service duration and slot step are not the same values.

= 3.9.2 (2022-03-20) =
* Added new option for calculating free slots based on Employee that can provide only single service type and be on that one selected location.

= 3.9.1 (2022-03-06) =
* Fixed issue with masked field

= 3.9.0 (2022-03-06) =
* Fixed issue with Theme Twenty Twenty-Two and inline JS content that was not rendered within shortcode

= 3.8.1 (2022-03-03) =
* Fixed issue with missing file

= 3.8.0 (2022-03-03) =
* Updated dependencies to prevent conflict with Events Tickets Plus plugin

= 3.7.3 (2022-02-07) =
* Added additional hook for admin ajax access rights. By adding additional code you can now have fine control of user access rights to settings resources.

= 3.7.2 (2022-02-06) =
* Improved callback hook when appointment is created in `ea_bootstrap`s shortcode

= 3.7.1 (2022-01-20) =
* Added additional filter that control user capabilities for Admin menu items. Now you can customize access based on that filter and logged in user.

= 3.7.0 (2022-01-16) =
* Added option to select how busy slots are going to be calculated. For example now you can set Location as shared resource between service providers. Use case when you have like one classroom and multiple teachers that are doing lectures in same space. Once location is booked other teachers are not free for booking.

= 3.6.1 (2021-12-27) =
* Added events color on new reports time table pages

= 3.6.0 (2021-12-26) =
* Added color for Services as option, visible on FullCalendar shortcode with param `color=service`

= 3.5.9 (2021-11-14) =
* Added option to search Appointments by custom field values

= 3.5.8 (2021-10-18) =
* Fixed issue with false limit reached when customer tries to book appointment
* Fixed issue with PHP8 warning message on booking

= 3.5.7 (2021-09-26) =
* Bulk option for adding new Connections

= 3.5.6 (2021-08-29) =
* Now when form is not valid it will automatically scroll to first invalid input field.
* Improved styles for New Reports > Time table
* Fixed date/time format on Connections admin page in table

= 3.5.5 (2021-08-04) =
* NEW Allow confirmation by email link for bookings that have status reservation in case it is default status.

= 3.5.4 (2021-07-25) =
* NEW Daily limit option on service. You can now set hard limit for number of booking for particular service during one day
* Fixed issue with warning message on Confirm/Cancel action from mail

= 3.5.3 (2021-06-21) =
* NEW Reports page - early access
* Services - now it is possible to have slot step with values 3,4 if duration of service is less then 5 minutes

= 3.5.2 (2021-05-16) =
* Improved Slot Step service field
* Fixed issue with date selector in Connections forms

= 3.5.1 (2021-05-08) =
* Added option to render custom form fields as hidden in customers form

= 3.5.0 (2021-05-02) =
* Fixed issues with translation part for new pages in admin section

= 3.4.13 (2021-04-14) =
* Fixed issue with saving of settings in some cases
* Extended hook for custom mail template

= 3.4.12 (2021-04-04) =
* Improved Appointments page with new two time select period
    - Tomorrow
    - Next 7 days
    - Next 30 days
* Also selected time period is saved in browser so next page visit will have that time period preselected
* Translation: Updated POT file

= 3.4.11 (2021-03-31) =
* Advance cancel redirect now also apply on cancel action from mail link.

= 3.4.10 (2021-03-29) =
* Fixed issue with default 0 values in Admin Service form

= 3.4.9 (2021-03-27) =
* Added option to add custom redirect when customer hit cancel button for bootstrap shortcode

= 3.4.8 (2021-03-24) =
* Security update - for admin part users will need to have `edit_posts` capability in order to make calls
* Fixed bug with sorting

= 3.4.7 (2021-03-23) =
* Fixes issue with some admin pages not responding to save/edit action

= 3.4.6 (2021-03-15) =
* Fixes issue with admin pages not responding to save action

= 3.4.5 (2021-03-13) =
* Vacation settings now works on ea_standard shortcode
* Added option to change `slug` value for custom field in form

= 3.4.4 (2021-03-10) =
* Added Google recaptcha V3 for bootstrap version of shortcode

= 3.4.3 (2021-02-15) =
* Fixed issue with empty connections page

= 3.4.2 (2021-02-13) =
* Added responsive styles for FullCalendar popup window for small screens
* Added `translate="no"` to avoid Google auto translate

= 3.4.1 (2021-02-12) =
* Fixed issue with block before/after service time

= 3.4.0 (2021-02-11) =
* New design for Connections and Tools admin page

= 3.3.2 (2021-02-05) =
* Masked field can be set as required

= 3.3.1 (2021-02-03) =
* Added hook for time selection in form `easyappslotselects`.

= 3.3.0 (2021-01-31) =
* Added option to style Slot select field in form as (from-to) time. Example `10:00 am - 11:00 am`
* Added `id` values for custom form fields

= 3.2.5 (2021-01-28) =
* Fixed issue with cutting price value on large numbers > 1000

= 3.2.4 (2021-01-19) =
* Added values for cancel and confirm links to FullCalendar event template

= 3.2.3 (2021-01-10) =
* Added Id column to Locations, Services and Workers admin page

= 3.2.2 (2021-01-04) =
* Fixed issue with editing services and missing values in form

= 3.2.1 (2020-12-28) =
* Compatibility with older WP versions (before 5)

= 3.2.0 (2020-12-27) =
* Admin - new settings pages for Locations, Workers and Services
* Updated translations
* Fixed issue with error warning on cron execution

= 3.1.4 (2020-12-08) =
* Translation files updated

= 3.1.3 (2020-12-07) =
* Fixed issues with masked field when you are not logged in.

= 3.1.2 (2020-12-04) =
* Added new field type - Masked input field. Now you can create your own format of input field. Useful for area code, phone numbers etc.

= 3.1.1 (2020-11-29) =
* Fixed issue with missing image resources on vacation screen

= 3.1.0 (2020-11-29) =
* Added Vacation settings in Admin panel. Now you can select vacation days for employees that will be block on `ea_bootstrap` shortcode.

= 3.0.15 (2020-11-22) =
* Added option for fullcalendar shortcode to select current logged employee `worker="logged"`
* Improved Service/Location/Worker accessibility via tab key
* Improved styles

= 3.0.14 (2020-11-03) =
* Fixed issue with `captcha` warning message

= 3.0.13 (2020-10-30) =
* Fixed issue with Worker and Location mixed in form

= 3.0.12 (2020-10-29) =
* Fixed Security issues with endpoints

= 3.0.11 (2020-10-23) =
* Fixed issue with PHP NOTICE message on Appointment creation
* Improvements regarding invalid date on Appointments overview

= 3.0.10 (2020-10-21) =
* Admin appointments page now can be navigate via Tab key (edit, clone, delete appointment)

= 3.0.9 (2020-10-20) =
* Fixed issue with label above field that was not working in [ea_bootstrap]
* Fixed issue with German translation and span element

= 3.0.8 (2020-10-16) =
* Fixed small issues JS errors on customers form
* Custom styles are now applied to FullCalendar shortcode as well

= 3.0.7 (2020-10-06) =
* Added GDPR options that auto remove customers data older then 6 months
* Settings improvement
* Block empty start time save for Appointments in Admin Panel

= 3.0.6 (2020-09-27) =
* Small Style improvements

= 3.0.5 (2020-09-21) =
* Added option for custom default label for Select field in [ea_bootstrap] shortcode called `select_placeholder`. Default value is '-'

= 3.0.4 (2020-08-23) =
* Added option for labels above fields for [ea_bootstrap] shortcode
* Added action to delete customers data older then 6 months (gdpr section)

= 3.0.3 (2020-08-08) =
* Added clear log button on EA Settings > Tools page
* Added attribute option for FullCalendar shortcode
* Added Reservation as short term status to default status list
* Added Swedish translation files (thanks to Daniel Moqvist)

= 3.0.2 (2020-07-29) =
* Added `block_days` and `block_days_tooltip` attr for bootstrap shortcode. Now you can add array of days that you want to block on customers form.

= 3.0.1 (2020-07-28) =
* Fixed issue on bootstrap form with `initialCall` error inside console that prevented bookings

= 3.0.0 (2020-07-26) =
* Added template engine for Event preview in FullCalendar short-code. Now you have custom preview and add complex logic like IF-ELSE based on event data, current language etc
* PHP Requirements are now 5.3+!

= 2.14.3 (2020-07-13) =
* Added option for Header customization on full calendar view for month, week, day
* Improvements regarding `fancy-select` issue

= 2.14.2 (2020-07-08) =
* Fixed issue with duplicated custom fields when you re-activate plugin
* Fixed issue when there is `fancy-select` active on page

= 2.14.1 (2020-06-14) =
* Added translatable title to email links prompt page introduced in 2.14
* Updated translation files

= 2.14.0 (2020-06-13) =
* Added option for additional user prompt when using links from email such as ` #link_confirm#, #link_cancel#`. By using this option you will prevent unwanted actions from Mail servers that wants to check every link inside emial.
* Fixed and updated labels and translations

= 2.13.7 (2020-06-08) =
* Fixed small issue introduced in previous release

= 2.13.6 (2020-06-08) =
* Added `Created` column for CSV export
* Improved bootstrap version of form

= 2.13.5 (2020-06-02) =
* Fixed issue with auto select option on bootstrap calendar shortcode

= 2.13.4 (2020-05-24) =
* Added shortcode option to prevent date auto select day when calendar is first step (`ea_bootstrap`)
* Added shortcode option for button label customization for full calendar (`ea_full_calendar`)

= 2.13.3 (2020-05-17) =
* Added new option for FullCalendar shortcode (`time_format`, `display_event_end`)
* Added option to hide price in select field in customers form (shortcode ea_standard ea_bootdstrap)
* Updated Italian translation (thanks to Massimo)

= 2.13.2 (2020-04-28) =
* Fixed issue with Phone field and pre selected country code

= 2.13.1 (2020-04-27) =
* Fixed issue with warning message when user is not logged in.

= 2.13.0 (2020-04-26) =
* NEW - Use current logged in user data sa default value for custom field. Available for bootstrap version of shortcode.

= 2.12.3 (2020-03-28) =
* Fixed issue with missing text field placeholder value in form

= 2.12.2 (2020-03-18) =
* Fixed issue with missing custom form label in templates after save action

= 2.12.1 (2020-03-01) =
* Fixed issue with custom form fields and unicode labels.

= 2.12.0 (2020-02-13) =
* Updated dependencies regarding PHP warning message.

= 2.11.2 (2020-02-12) =
* Fixed issue with missing EMAIL field when editing appointment inside Admin part.

= 2.11.1 (2020-02-06) =
* Removed leading zeros between Country Code and Phone number in customer form when submitted.

= 2.11.0 (2020-01-28) =
* Improved styles for calendar on customers form (bootstrap shortcode)
* Added default country code option for PHONE field type in custom form fields

= 2.10.2 (2020-01-12) =
* Added option for removing line breaks from shortcode template. Prevents wpautop

= 2.10.1 (2020-01-07) =
* Small Fix for connections slot number

= 2.10.0 (2020-01-05) =
* Added option for slot count on connection level. Now you don't need to have same connections to increase number of slots, just alter that value on connection level
* Now you can override booking overview template inside you theme under (`theme-folder/easy-appointments/booking.overview.tpl.php`)
* Added option for making FullCalendar shortcode public
* Added confirm popup for deleting appointments
* Small style improvements