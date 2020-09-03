pipeline {
agent any

stages {
    stage('Build') {
        steps {
            sh 'docker exec laradock_workspace_1 composer install'
            sh 'docker exec laradock_workspace_1 npm install'
            sh 'docker exec laradock_workspace_1 npm run dev'
            sh 'cp .env.example .env'
            sh 'docker exec laradock_workspace_1 php artisan key:generate'
            sh 'docker exec laradock_workspace_1 php artisan migrate:fresh --seed'
            sh 'docker exec laradock_workspace_1 php artisan storage:link'
            
        }
    }
    stage('Test') {
        steps {
            
            sh 'docker exec laradock_workspace_1 vendor/bin/phpunit'

        }
    }
    
}
}
