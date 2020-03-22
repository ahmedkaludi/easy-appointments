# EasyAppointment

EasyAppointments Booking plugin

## Intro

Requirements for running repo:
* docker
* docker-compose

First time (or on build change):

`docker-compose up --build`

Every other time just:

`docker-compose up`

_JS builds are done on each change_

## Web locations

### FrontEnd:

* Demo Bootstrap: http://127.0.0.1:8080/demo-bootstrap/
* Demo Standard: http://127.0.0.1:8080/demo-standard/
* Demo FullCalendar: http://127.0.0.1:8080/demo-fullcalendar/
* Demo WIzard : http://127.0.0.1:8080/wizard/

### Admin (`admin`/`password`)

* Appointments: http://127.0.0.1:8080/wp-admin/admin.php?page=easy_app_top_level
* Settings: http://127.0.0.1:8080/wp-admin/admin.php?page=easy_app_settings
* Reports: http://127.0.0.1:8080/wp-admin/admin.php?page=easy_app_reports

### MailHog

* http://127.0.0.1:8025/
