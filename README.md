## Prerequisites  
Node.js, php, and composer

## Installation Guide
1. Download the ZIP file and extract it
2. Open the command prompt/terminal and call the directory to the location by:
```
cd path\to\the\repo
//For example, if it's in the downloads folder:
cd downloads\be-exam-main
```    
3. Install the dependencies by running the commands:
```
npm install
```
Wait for it to finish downloading, then run the next command:
```
composer install
```
4. Locate and make a copy of the **.env.example** file in the same location
5. Open the **copy of the .env.example** file, edit it using notepad or other text editing software, and enter the correct database configuration:
```
//Sample Database configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=SampleDatabaseName
DB_USERNAME=root
DB_PASSWORD=SamplePassword
```
6. Once all the database configurations are done, save the file and rename the it to **.env**
7. Generate the key for the .env file by running:
```
php artisan key:generate
```
8. Create the database by running:
```
php artisan migrate
```
it will ask you to create a database and type:
```
yes 
```
Note: if you have the wrong database credentials, you might need to revisit step #5 and ensure all database configurations are correct

9. Seed the database by running:
```
php artisan db:seed
```
10. Finally, run the command below to make the system access the videos and images:
```
php artisan storage:link
```
11. Run the development server by typing the command
```
npm run dev
```
12. Open another command prompt/terminal and call the same directory and run the command
```
php artisan serve
```
13. Access the site at http://localhost:8000/

## Login credentials for the website:
Username: rey (or you can use the email: rey@gmail.com)

password: abc

Username: admin (or you can use the email: admin@gmail.com)

password: abc
 
