# comp30132017
This is a private repo #sarcasm
##The Directory struture
All php scripts that will have HTML should go in the App folder.
All Models should go in the Models folder
All Core PHP scripts like base classes, should go in the Core folder
##Using Models
Every table in the database has to have a PHP class, named after the table.
Every such class, must extend the base Model class.
###The Model Class
All php files for the classes should go in the Models directory
This class provides direct methods for queries.
As we go along, and new queries are required, we can add more query methods.
There is also a RAW query methods, which allows us to pass in direct SQL statement to be executed directly on the database.
The Model class extends the DB class, so you don't have to worry about connecting to the database at all!

###The Db class
This class contains all necessary information to connect to the database.
The class is implemented using the Singleton design pattern.
