# K-means
CS174 final project

Your web application will implement a k-means based clustering machine learning algorithm, letting the users train and test a personal model with their input data.

You will have to:

Build a web page that:

Ensures a secure Session mechanism. Allows the user to sign in and log in. For training: Allows the logged in users to submit a text file (extension .txt ONLY), containing "scores" to train the model. The scores can come from other ML algorithms, but this doesn't concern your application. You can test your program with made up scores. Allows the user to submit such scores in an input text box too. Allows the user to give a name to the uploaded model. For testing: Allows the logged in users to submit a text file (extension .txt ONLY), containing "scores" to test the model, if, and only if, an already existent model is stored in the database for that specific user. If more than one model is stored, the user should be able to select the one that wants to use selecting the name of such model.

Build a web application that:

Applies the training and testing mechanism for k-means clustering.

Build a MySQL database that:

Stores the input models and their names, for any user. Stores the information related to the user account's username, password and email in the most secure way of your knowledge. All these fields must be validated: The username can contain English letters (capitalized or not), digits, and the characters '_' (underscore) and '-' (dash). Nothing else. The email must be well formatted. The password can have limitations of your choice, if you think it's worth it.
