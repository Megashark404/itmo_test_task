# Тестовое задание на PHP Dev
Сделать CRUD-админку для управления авторами и книгами. У автора может быть много книг, книга может иметь много авторов. Визуальная составляющая страниц значения не имеет.  
Поля автора: фамилия, имя и отчество в отдельных полях. Не должно быть двух авторов с одинаковыми ФИО.  
Поля книги: название, год издания, ISBN, количество страниц. Не должно быть двух книг с одинаковыми сочетаниями названия и ISBN или названия и года издания. Для хранения названия книги достаточно будет 255 байт.

## Использование
1. Склонировать репозиторий
2. Зайти в папку проекта, запустить комаду make init. В браузере должна открыться страница с заданием (если сайт по каким-нибудь причинам не откроется, открыть в браузере http://mysite.local:8590/book)

## Наполнение базы данных
Для первичного наполнения БД приложен SQL-дамп в папке sqldump. Он должен импортироваться при первом запуске контейнера, но, если что, то вы знаете, что он тут )
