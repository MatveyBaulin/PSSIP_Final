Данные для проверки front-end можно взять файлы (кроме index.html) из репозитория: https://github.com/MatveyBaulin/YtYt
Обязательно должен быть установлен xampp и все файлы должны быть расположены внутри папки htdocs каталога xampp!
Для работы сайта нужно включить в xampp Apache и MySQL.
Для успешной работы с отправкой сообщений на электронную почту, нужно изменить следующие параметры:

1. В php.ini (папка php из каталога xampp):

[mail function]

; For Win32 only.
; https://php.net/smtp
;SMTP=localhost
; https://php.net/smtp-port
;smtp_port=587

; For Win32 only.
; https://php.net/sendmail-from
;sendmail_from = me@example.com

; For Unix only.  You may supply arguments as well (default: "sendmail -t -i").
; https://php.net/sendmail-path
sendmail_path = "C:\xampp\sendmail\sendmail.exe -t"

2. В sendmail.ini (папка sendmail из каталога xampp):

smtp_server=smtp.gmail.com
smtp_port=587
mtp_ssl=auto
hostname=localhost
auth_username=[ваша электронная почта]
auth_password=[16-значный пароль приложения, сгенерированный Google]
