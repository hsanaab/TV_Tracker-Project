TV Tracker
Welcome to TV Tracker, a web application where users can log, rate, comment on, and track their favorite TV shows!

📚 Project Overview
TV Tracker allows users to:

Add TV shows to a shared database

Rate and comment on their favorite shows

Track watch status ("Watching", "Watched", "To Watch")

Search other users' reviews

View other users' watchlists

Manage personal accounts through a simple username/email/password system

✨ Features
Secure user login and signup

Add TV shows if not already in the database

Attach multiple genres (up to 3) per show

Rate shows on a 1-5 scale and add comments

Search for reviews by Title, Genre, or Rating

View your own or other users' watchlists

Password hashing for secure user storage

Inline error handling (no blank screens!)

Built-in database triggers, indexes for performance

🛠️ Technologies Used
PHP (Core backend)

MySQL (Database)

HTML/CSS (Frontend)

JavaScript (for interactivity)

Apache Server (via XAMPP for localhost)

🗄️ Database Design
The project database is normalized to 3NF.
Key tables:

Users: Stores usernames, emails, hashed passwords

TV_Shows: Master list of TV shows

Genres: List of genres

Show_Genres: Many-to-many table linking shows to genres

User_Watchlist: Tracks user's watch statuses

User_Show_Data: Stores user ratings and comments

📈 ERD (Entity-Relationship Diagram) provided separately.

Triggers:

Enforce max 3 genres per show

Auto-fill dates when inserting reviews

Prevent duplicate show entries

Maintain data integrity across tables

Indexes:

Created on Title, Genre_ID, User_ID, and Rating for faster queries

🛠️ Setup Instructions
Install XAMPP and start Apache and MySQL.

Import the provided SQL file into your MySQL server.

Place project folder (TV_Tracker) into /htdocs/.

Open http://localhost/TV_Tracker/ in your browser.

Create a new user or log in to start adding shows!

🌟 Future Enhancements
Improve page styling (cleaner CSS, mobile responsiveness)

Profile pages for each user

Password reset functionality

Like/upvote system for user reviews

Full admin dashboard to manage shows and users

Email validation during signup

Enhanced genre tagging with autocomplete

📣 Author
Built with ❤️  by Sanaa Byron.
