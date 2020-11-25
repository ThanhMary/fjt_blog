# fjt_blog
Projet Full-stack Ynov

# [Compte rendu](https://docs.google.com/document/d/1cCGVpjxGDtKQDu-Muiq8BVfK6UllhJbDCEEcH2SbcU4/edit?usp=sharing)
Class diagramm and other additional files are in [/public/report_files/](https://github.com/ThanhMary/fjt_blog/tree/main/public/report_files)

## Connections

|| user   | admin |
| ------------- | ------------- | ------------- |
| identifiers |  user@gmail.com |admin@gmail.com |
| password | user  | admin |

## Routes

| Without account                                 |      User     |        Admin  |
| -------------                                 | ------------- |------------- |
| [/](http://blog.com) | [/profile](http://blog.com/profile) |[/article/new](http://blog.com/article/new) |
| [/propos](http://blog.com/propos) |[/profile/liked_articles](http://blog.com/profile/liked_articles) |[/article/](http://blog.com/article) |
| [/contact/new](http://blog.com/contact/new) |  | [/contact/](http://blog.com/contact) |
| [/login](http://blog.com/login) |  | 

## Steps to reproduce :

- 1 )
Test pages without connecting

- 2 )
Test pages by connecting with user account
- Access profile => See articles liked with filters

- 3 )
Test pages by connecting to teh admin
