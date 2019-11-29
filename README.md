# Установака:
  
## Клонируйте репозиторий проекта
    
    git clone git@cdpdev.lpr.jet.msk.su:rosreestr/egrn/rr-egron-pkurp/arm-kd.git

    cd arm-kd


## Переключиться в ветку gitsource

    git checkout gitsource


## Установите необходимые зависимости:

### gem gdal

MacOS: 
    
    brew install gdal

Ubuntu:
    
    sudo add-apt-repository ppa:ubuntugis/ubuntugis-unstable
    sudo apt-get update
    sudo apt-get install libgdal-dev


## Отредактируйте имена файлов в папке config

Файлы с именем *.yml.examlpe* скопируйте с таким же именем удалив из имени *.examlpe*

    cp config/database.yml.example config/database.yml

    cp config/secrets.yml.example config/secrets.yml
    
    cp config/settings.yml.example config/settings.yml


## Отредактировать database.yml

  Закомментитировать строку в database.yml: 
  
    # url: <%= ENV['DATABASE_URL'] || 'postgres://popd_writer:popd_writer@192.168.24.37/postgres'%>


## Запустите Bundler

    bundle install

> TODO:

  описать подключение к БД
  
    rake db:create

    rake db:migrate
