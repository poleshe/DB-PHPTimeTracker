# Time Tracker Project for DB

The project has been done under Symfony(PHP), Mysql & Nginx as the server. All the project and services are under Dockers, using docker-compose.

I have chosen Symfony because it really makes PHP development easier with the Routing and Controllers, and also ORM. Also, I thought it was a nice framework to use keeping in mind the opportunity specifications.

There's three Controllers on the project, and one DB using ORM. There is also a main page using Symfony's template system (twig).

Thanks a lot! Have a great day.

Pol Estecha, polestecha14@gmail.com



## Installation

For this project you will need: Docker, Docker-Compose, Git, and if you want to execute the PHP Script from the terminal, you'll also need the PHP & php-curl packages. 

Also, make sure that you have the ports 3306 and 8001 free, or the dockers will not be able to bind to them, and won't get up.

To install, first download the repository. Then, get inside, and execute:

```bash
sudo docker-compose up -d --build
```
Then wait until they are up. When they are up, get into the PHP docker using:

```bash
sudo docker exec -it db-phptimetracker_php_1 bash
```
(WARNING! The name may vary between O.S. Check the docker name using 'sudo docker ps -a'. If it's not the same, it will be very very similar).

And install the composer packages with:
```bash
composer install
```
When it finishes, the project is ready to go.

## Usage

Once the installation steps have been completed, open the browser and go to the URL:

```bash
localhost:8001/
```
Here you will find the main page of the project. Browser usage is as described on the PDF. Type in a Task Name, and press Start. 

You can then stop the timer pressing Stop. A row will appear downside of the timer with the Task data. If the task already exists, the row will update with the new time.

## PHP Script

There is one PHP Script that allows the usage of the project via terminal. It's called TimeTracker.php, on the main folder of the repo. Usage:

```bash
php TimeTracker.php start nameofthetask -- Starts a new Time Tracker Task with the given name.

php TimeTracker.php stop nameofthetask -- Stops a task with the given name. If the task is not found, it will do nothing.

php TimeTracker.php list -- Prints out a List of all the Tasks inside the DB, with all their information.

php TimeTracker.php help, or no argument, will print help about the script, similar to this one.


```

## License
[MIT](https://choosealicense.com/licenses/mit/)
