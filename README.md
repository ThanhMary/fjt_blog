# fjt_blog

# [Report(link)](https://docs.google.com/document/d/1cCGVpjxGDtKQDu-Muiq8BVfK6UllhJbDCEEcH2SbcU4/edit?usp=sharing)
Class diagramm and other additional files are in [/public/report_files/](https://github.com/ThanhMary/fjt_blog/tree/main/public/report_files)

# init project
Fisrt clone repo + vhost + create bdd(.env)
/!\Should have the last version of node or at least a 12.0 or more
```bash
composer install && npm install && php bin/console make:migration && php bin/console doctrine:migration:migrate && php bin/console doctrine:fixtures:load && npm run build
```

## Connections

|| user   | admin |
| ------------- | ------------- | ------------- |
| identifiers |  user@gmail.com |admin@gmail.com |
| password | user  | admin |

## Routes

| Without account                                 |      User     |        Admin  |
| -------------                                 | ------------- |------------- |
| [/](http://blog.com/) | [/profile](http://blog.com/profile) |[/article/new](http://blog.com/article/new) |
| [/propos](http://blog.com/propos) |[/profile/liked_articles](http://blog.com/profile/liked_articles) |[/article/](http://blog.com/article) |
| [/contact/new](http://blog.com/contact/new) | [/profile/shared_articles](http://blog.com/profile/shared_article) | [/contact/](http://blog.com/contact) |
| [/login](http://blog.com/login) | [/profile/comment_articles](http://blog.com/profile/comment_articles) | |

## Steps to reproduce :

- 1 )
Test pages without connecting

- 2 )
Test pages by connecting with user account

- 3 )
Test pages by connecting to the admin
