pipeline {
    agent any

    options {
        skipDefaultCheckout()
    }

    environment {
        COMPOSE_CMD = './docker/bin/sail'
    }

    stages {
        stage('Clean Workspace') {
            steps {
                cleanWs()
            }
        }

        stage('Checkout') {
            steps {
                git branch: 'docking',
                    credentialsId: 'github-ssh',
                    url: 'git@github.com:DEINSI-DEVELOP/fashion-conspiracy.git'
            }
        }

        stage('Preparar') {
            steps {
                sh 'chmod +x ./docker/bin/sail'
                sh 'chmod +x ./docker/laravel/sail/bin/sail'
            }
        }

        stage('Copiar .env') {
            steps {
                withCredentials([file(credentialsId: 'fashion-env', variable: 'LARAVEL_ENV')]) {
                    sh '''
                        rm -f .env || true
                        cp "$LARAVEL_ENV" .env
                    '''
                }
            }
        }

        stage('Desplegar Imagen') {
            steps {
                sh 'docker compose up --build -d'
            }
        }

        stage('Reiniciar Imagen') {
            steps {
                sh 'docker compose restart'
            }
        }
    }

    post {
        failure {
            echo '❌ Falló el proceso de despliegue.'
        }
        success {
            echo '✅ Despliegue exitoso.'
        }
    }
}
